<?php

namespace admin\courses\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseCreateRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'language' => 'required',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'duration' => 'required|numeric|max:365',
            'price' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'instructor' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_highlight' => 'boolean',
            'status' => 'required|in:pending,approved,rejected',            
            
           //thumbnail_image image laravel validation
            //'thumbnail_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max

            // Fix the promo_video validation - remove 'video' rule
            //'promo_video' => 'required|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max

            //categories validation
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',

            // course_tags validation
            'course_tags' => 'nullable|array',
            'course_tags.*' => 'exists:tags,id',
            
            // Section validation
            'sections' => 'nullable|array',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.id' => 'nullable|integer|exists:course_sections,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.max' => 'The course title must not exceed 255 characters.',
            'slug.unique' => 'The slug is already taken.',
            'level.required' => 'The course level is required.',
            'level.in' => 'The course level must be Beginner, Intermediate, Advanced, or Expert.',
            'status.required' => 'The course status is required.',
            'status.in' => 'The course status must be pending, approved, or rejected.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price cannot be negative.',
            'max_students.integer' => 'The maximum students must be a whole number.',
            'max_students.min' => 'The maximum students must be at least 1.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'promo_video_file.file' => 'The promo video must be a valid file.',
            'promo_video_file.mimes' => 'The promo video must be a video file (mp4, avi, mov, wmv, flv, webm).',
            'promo_video_file.max' => 'The promo video file size must not exceed 100MB.',
            'categories.array' => 'The categories must be an array.',
            'categories.*.exists' => 'The selected category does not exist.',
            'course_tags.array' => 'The tags must be an array.',
            'course_tags.*.exists' => 'The selected tag does not exist.',
            'sections.array' => 'The sections must be an array.',
            'sections.*.title.required' => 'The section title is required.',
        ];
    }
}
