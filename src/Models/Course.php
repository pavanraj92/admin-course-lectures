<?php

namespace admin\courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class Course extends Model
{
    use HasFactory, Sortable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',        
        'language',
        'duration',
        'price',
        'max_students',
        'start_date',
        'end_date',
        'level',
        'is_highlight',
        'thumbnail_image',
        'promo_video',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_highlight' => 'boolean',
    ];

    /**
     * The attributes that should be sortable.
     */
    public $sortable = [
        'title',
        'level',
        'language',
        'status',
        'is_highlight',
        'created_at'
    ];

    /**
     * Define relationship with categories
     */
    public function categories()
    {
        return $this->belongsToMany(
            'admin\categories\Models\Category', 
            'course_category', 
            'course_id', 
            'category_id'
        )->using(CourseCategory::class)->withTimestamps();
    }

    /**
     * Define relationship with tags
     */
    public function courseTags()
    {
        return $this->belongsToMany(
            'admin\tags\Models\Tag', 
            'course_tag', 
            'course_id', 
            'tag_id'
        )->using(CourseTag::class)->withTimestamps();
    }

    /**
     * Define relationship with course sections
     */
    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    /**
     * Define relationship with course categories (pivot table access)
     */
    public function courseCategories()
    {
        return $this->hasMany(CourseCategory::class);
    }

    /**
     * Define relationship with course tags (pivot table access)
     */
    public function courseTagRelations()
    {
        return $this->hasMany(CourseTag::class);
    }

    /**
     * Scope to filter by title
     */
    public function scopeFilter($query, $title)
    {
        if ($title) {
            return $query->where('title', 'like', '%' . $title . '%');
        }
        return $query;
    }

    /**
     * Scope to filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope to filter by level
     */
    public function scopeFilterByLevel($query, $level)
    {
        if ($level) {
            return $query->where('level', $level);
        }
        return $query;
    }

    /**
     * Scope to filter by language
     */
    public function scopeFilterByLanguage($query, $language)
    {
        if ($language) {
            return $query->where('language', $language);
        }
        return $query;
    }

    /**
     * Scope to get highlighted courses
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlight', true);
    }

    /**
     * Scope to get approved courses
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get all lectures through sections
     */
    public function lectures()
    {
        return $this->hasManyThrough(
            Lecture::class,
            CourseSection::class,
            'course_id', // Foreign key on course_sections table
            'section_id', // Foreign key on lectures table
            'id', // Local key on courses table
            'id' // Local key on course_sections table
        )->ordered();
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get level display name
     */
    public function getLevelDisplayAttribute()
    {
        return ucfirst($this->level);
    }
}
