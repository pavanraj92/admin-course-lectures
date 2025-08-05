<?php

namespace admin\courses\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureCreateRequest extends FormRequest
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
            'section_id' => 'required|exists:course_sections,id',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'type' => 'required|in:video,audio,text,quiz',
            'video' => 'nullable',
            'attachment' => 'nullable|file|mimes:jpg,pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|max:51200', // 50MB max
            'duration' => 'nullable|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'is_preview' => 'boolean',
            'is_highlight' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'section_id.required' => 'Please select a section.',
            'section_id.exists' => 'The selected section does not exist.',
            'title.required' => 'Lecture title is required.',
            'title.max' => 'Lecture title cannot exceed 255 characters.',
            'short_description.max' => 'Short description cannot exceed 500 characters.',
            'type.required' => 'Lecture type is required.',
            'type.in' => 'Lecture type must be video, audio, text, or quiz.',
            'video.mimes' => 'Video must be a file of type: mp4, avi, mov, wmv, flv, webm.',
            'video.max' => 'Video file size cannot exceed 100MB.',
            'attachment.mimes' => 'Attachment must be a file of type: pdf, doc, docx, ppt, pptx, xls, xlsx, zip, rar.',
            'attachment.max' => 'Attachment file size cannot exceed 50MB.',
            'duration.integer' => 'Duration must be a number.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'order.integer' => 'Order must be a number.',
            'order.min' => 'Order cannot be negative.',
            'status.required' => 'Lecture status is required.',
            'status.in' => 'Status must be draft, published, or archived.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'section_id' => 'section',
            'is_preview' => 'preview access',
            'is_highlight' => 'highlight',
        ];
    }
}
