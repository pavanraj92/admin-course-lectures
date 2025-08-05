<?php

namespace admin\courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CourseSection extends Model
{
    protected $table = 'course_sections';

    protected $fillable = [
        'course_id',
        'title',
        'slug',
    ];

    protected $casts = [
        'course_id' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title if not provided
        static::creating(function ($section) {
            if (empty($section->slug) && !empty($section->title)) {
                $section->slug = Str::slug($section->title);
            }
        });

        static::updating(function ($section) {
            if (empty($section->slug) && !empty($section->title)) {
                $section->slug = Str::slug($section->title);
            }
        });
    }

    /**
     * Get the course that owns the section.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope a query to filter by course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope a query to filter by slug.
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get the lectures that belong to the section.
     */
    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'section_id')->ordered();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
