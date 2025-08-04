<?php

namespace admin\courses\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseCategory extends Pivot
{
    /**
     * The table associated with the model.
     */
    protected $table = 'course_category';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'course_id',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'course_id' => 'integer',
        'category_id' => 'integer',
    ];

    /**
     * Get the course that belongs to this relationship.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the category that belongs to this relationship.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo('admin\categories\Models\Category');
    }

    /**
     * Scope a query to filter by course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
