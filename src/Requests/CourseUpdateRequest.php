<?php

namespace admin\courses\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $courseId = $this->route('course')->id ?? $this->course;

        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug,' . $courseId,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:100',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'duration' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'max_students' => 'nullable|integer|min:1',
            'instructor' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_highlight' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',
            'thumbnail_url' => 'nullable|string|max:500',
            
            // Fix the promo_video validation - remove 'video' rule
            'promo_video_url' => 'nullable|string|max:500',
            'promo_video_file' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
            
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'course_tags' => 'nullable|array',
            'course_tags.*' => 'exists:tags,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'title.max' => 'Course title must not exceed 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'level.required' => 'Course level is required.',
            'level.in' => 'Course level must be Beginner, Intermediate, Advanced, or Expert.',
            'status.required' => 'Course status is required.',
            'status.in' => 'Course status must be pending, approved, or rejected.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'max_students.integer' => 'Maximum students must be a whole number.',
            'max_students.min' => 'Maximum students must be at least 1.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'promo_video_file.file' => 'Promo video must be a valid file.',
            'promo_video_file.mimes' => 'Promo video must be a video file (mp4, avi, mov, wmv, flv, webm).',
            'promo_video_file.max' => 'Promo video file size must not exceed 100MB.',
            'categories.array' => 'Categories must be an array.',
            'categories.*.exists' => 'Selected category does not exist.',
            'course_tags.array' => 'Tags must be an array.',
            'course_tags.*.exists' => 'Selected tag does not exist.',
        ];
    }
}
