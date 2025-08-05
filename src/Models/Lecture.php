<?php

namespace admin\courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;

class Lecture extends Model
{
    use HasFactory, Sortable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'short_description',
        'description',
        'content',
        'type',
        'video',
        'attachment',
        'duration',
        'order',
        'is_preview',
        'is_highlight',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'section_id' => 'integer',
        'duration' => 'integer',
        'order' => 'integer',
        'is_preview' => 'boolean',
        'is_highlight' => 'boolean',
    ];

    /**
     * The attributes that should be sortable.
     */
    public $sortable = [
        'title',
        'type',
        'status',
        'duration',
        'order',
        'created_at',
        'updated_at'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title if not provided
        static::creating(function ($lecture) {
            if (empty($lecture->slug) && !empty($lecture->title)) {
                $lecture->slug = Str::slug($lecture->title);
            }
        });

        static::updating(function ($lecture) {
            if (empty($lecture->slug) && !empty($lecture->title)) {
                $lecture->slug = Str::slug($lecture->title);
            }
        });
    }

    /**
     * Get the section that owns the lecture.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * Get the course through the section.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Scope a query to filter by keyword.
     */
    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('short_description', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeFilterByType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope a query to filter by section.
     */
    public function scopeForSection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Scope a query to filter by course through section.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->whereHas('section', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }

    /**
     * Scope a query to get published lectures.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to get highlighted lectures.
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlight', true);
    }

    /**
     * Scope a query to get preview lectures.
     */
    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    /**
     * Scope a query to order by lecture order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope a query to filter by slug.
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the video URL if video exists.
     */
    public function getVideoUrlAttribute()
    {
        if ($this->video) {
            return asset('storage/' . $this->video);
        }
        return null;
    }

    /**
     * Get the attachment URL if attachment exists.
     */
    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return asset('storage/' . $this->attachment);
        }
        return null;
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return 'N/A';
        }

        $hours = intval($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'draft' => 'badge-warning',
            'published' => 'badge-success',
            'archived' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Get type badge class for UI.
     */
    public function getTypeBadgeClassAttribute()
    {
        return match ($this->type) {
            'video' => 'badge-primary',
            'audio' => 'badge-info',
            'text' => 'badge-secondary',
            'quiz' => 'badge-warning',
            default => 'badge-light'
        };
    }

    /**
     * Check if lecture has video.
     */
    public function hasVideo()
    {
        return !empty($this->video);
    }

    /**
     * Check if lecture has attachment.
     */
    public function hasAttachment()
    {
        return !empty($this->attachment);
    }

    /**
     * Check if lecture is published.
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Check if lecture is draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if lecture is archived.
     */
    public function isArchived()
    {
        return $this->status === 'archived';
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}
