@extends('admin::admin.layouts.master')

@section('title', 'Lectures Management')

@section('page-title', isset($lecture) ? 'Edit Lecture' : 'Create Lecture')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.lectures.index') }}">Lecture Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($lecture) ? 'Edit Lecture' : 'Create Lecture' }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Start Lecture Content -->
    <form action="{{ isset($lecture) ? route('admin.lectures.update', $lecture) : route('admin.lectures.store') }}"
        method="POST" enctype="multipart/form-data" id="lectureForm">
        @csrf
        @if (isset($lecture))
        @method('PUT')
        @endif

        <div class="row">
            <div class="col-8">
                <!-- card section -->
                <div class="card bg-white">
                    <!--card header section -->
                    <div class="card-header bg-white border-bottom border-gray-200">
                        <h4 class="card-title">
                            Lecture Information
                        </h4>
                    </div>
                    <!--card body section -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Lecture Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title', $lecture->title ?? '') }}"
                                        placeholder="Enter lecture title" required>
                                    @error('title')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Short Description</label>
                            <textarea name="short_description" id="short_description" class="form-control" rows="2"
                                placeholder="Enter brief lecture description">{{ old('short_description', $lecture->short_description ?? '') }}</textarea>
                            @error('short_description')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="description" class="form-control ckeditor" rows="3"
                                placeholder="Enter lecture description">{{ old('description', $lecture->description ?? '') }}</textarea>
                            @error('description')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Duration (minutes)</label>
                                    <input type="number" name="duration" id="duration" class="form-control"
                                        value="{{ old('duration', $lecture->duration ?? '') }}"
                                        placeholder="Enter duration in minutes" min="1" required>
                                    @error('duration')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Order</label>
                                    <input type="number" name="order" id="order" class="form-control"
                                        value="{{ old('order', $lecture->order ?? 0) }}"
                                        placeholder="Enter display order" min="0">
                                    @error('order')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                {{ isset($lecture) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('admin.lectures.index') }}" class="btn btn-secondary">Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-4">
                <!-- card section -->
                <div class="card bg-white">
                    <!--card header section -->
                    <div class="card-header bg-white border-bottom border-gray-200">
                        <h4 class="card-title">
                            Section and Type
                        </h4>
                    </div>
                    <!--card body section -->
                    <div class="card-body">
                        <!--courses display in the select2 dropdown -->
                        @if (request()->query('course'))
                        <input type="hidden" name="course_id" id="course_id" value="{{ request()->query('course') }}">
                        @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Course <span class="text-danger">*</span></label>
                                    <select name="course_id" id="course_id" class="form-control select2" required>
                                        <option value="">Select Course</option>
                                        @foreach ($courses as $id => $title)
                                        <option value="{{ $id }}"
                                            {{ old('course_id', $lecture->course_id ?? '') == $id ? 'selected' : '' }}>
                                            {{ $title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Section <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-control select2" required>
                                        @if (isset($sections) && count($sections) > 0)
                                        {{-- Sections are already fetched --}}
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $sectionId => $sectionTitle)
                                        <option value="{{ $sectionId }}"
                                            {{ old('section_id', $lecture->section_id ?? '') == $sectionId ? 'selected' : '' }}>
                                            {{ $sectionTitle }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">No Sections Available</option>
                                        @endif

                                    </select>
                                    @error('section_id')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control select2" required>
                                        @foreach ($types as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $lecture->type ?? 'video') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                </div>

                <!-- Media Files card section -->
                <div class="card bg-white">
                    <!--card header section -->
                    <div class="card-header bg-white border-bottom border-gray-200">
                        <h4 class="card-title">
                            Media Files
                        </h4>
                    </div>
                    <!--card body section -->
                    <div class="card-body">

                        <!-- Audio Upload -->
                        <div class="form-group type-field" id="audioField" style="display: none;">
                            <label>Audio File <span class="text-danger">*</span></label>
                            <input type="file" id="audioFileInput" name="audio" accept="audio/*" class="form-control">
                            @error('audio')<div class="text-danger validation-error">{{ $message }}</div>@enderror
                            <div id="audioPreview" class="mt-2">
                                @if (isset($lecture) && $lecture->audio)
                                <div class="existing-file">
                                    <audio controls class="w-100">
                                        <source src="{{ $lecture->audio_url }}" type="audio/mpeg">
                                    </audio>
                                </div>
                                @endif
                            </div>
                        </div>


                        <div class="form-group type-field" id="videoField">
                            <label>Video File <span class="text-danger">*</span></label>
                            <div id="videoDropzone" class="dropzone">
                                <div class="dz-message">
                                    <i class="mdi mdi-video mdi-48px text-muted"></i>
                                    <h4>Drop video file here or click to upload</h4>
                                    <p class="text-muted">Supported formats: MP4, AVI, MOV, WMV (Max: 100MB)</p>
                                </div>
                            </div>
                            <!-- Hidden file input -->
                            <input type="file" id="videoFileInput" name="video" accept="video/*"
                                style="display: none;">
                            @error('video')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                            <div id="videoPreview" class="mt-2">
                                @if (isset($lecture) && $lecture->video)
                                <div class="existing-file">
                                    <video controls class="w-100" style="max-height: 200px;">
                                        <source src="{{ $lecture->video_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <small class="text-muted d-block mt-1">Current video file</small>
                                </div>
                                @endif
                            </div>
                        </div>



                        <div class="form-group">
                            <label>Attachment</label>
                            <div id="attachmentDropzone" class="dropzone">
                                <div class="dz-message">
                                    <i class="mdi mdi-file-document mdi-48px text-muted"></i>
                                    <h4>Drop file here or click to upload</h4>
                                    <p class="text-muted">Supported formats: JPG, PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR (Max: 50MB)</p>
                                </div>
                            </div>
                            <!-- Hidden file input -->
                            <input type="file" id="attachmentFileInput" name="attachment"
                                accept=".jpg,.jpeg,.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar" style="display: none;">
                            @error('attachment')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                            <div id="attachmentPreview" class="mt-2">
                                @if (isset($lecture) && $lecture->attachment)
                                <div class="existing-file">
                                    <a href="{{ $lecture->attachment_url }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="mdi mdi-download"></i> Download Current File
                                    </a>
                                    <small
                                        class="text-muted d-block mt-1">{{ basename($lecture->attachment) }}</small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card section -->
                <div class="card bg-white">
                    <!--card header section -->
                    <div class="card-header bg-white border-bottom border-gray-200">
                        <h4 class="card-title">
                            Publish
                        </h4>
                    </div>
                    <!--card body section -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select2">
                                        @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $lecture->status ?? 'draft') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="is_preview" id="is_preview"
                                            class="custom-control-input" value="1"
                                            {{ old('is_preview', $lecture->is_preview ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_preview">
                                            Allow Preview
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="is_highlight" id="is_highlight"
                                            class="custom-control-input" value="1"
                                            {{ old('is_highlight', $lecture->is_highlight ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_highlight">
                                            Highlight
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </form>
    <!-- End Lecture Content -->
</div>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- CKEditor CSS -->
<style>
    .ck-editor__editable {
        min-height: 200px;
    }

    .ck-editor__editable[data-placeholder]:empty::before {
        color: #999;
    }

    /* Custom Dropzone Styles */
    .dropzone {
        border: 2px dashed #ccc !important;
        border-radius: 8px !important;
        background: #fafafa !important;
        padding: 20px !important;
        text-align: center !important;
        transition: all 0.3s ease !important;
        min-height: 120px !important;
        cursor: pointer !important;
        position: relative !important;
    }

    .dropzone:hover {
        border-color: #007bff !important;
        background: #f8f9ff !important;
    }

    .dropzone.dz-drag-hover {
        border-color: #007bff !important;
        background: #e3f2fd !important;
    }

    .dropzone .dz-message {
        margin: 0 !important;
        pointer-events: none !important;
    }

    .dropzone .dz-message h4 {
        font-size: 16px !important;
        margin: 10px 0 5px 0 !important;
        color: #666 !important;
        pointer-events: none !important;
    }

    .dropzone .dz-message p {
        font-size: 12px !important;
        margin: 0 !important;
        pointer-events: none !important;
    }

    .existing-file {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .new-file {
        margin-bottom: 10px;
    }

    .alert {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    function toggleMediaFields() {
        const type = $('#type').val();

        // Hide all media fields first
        $('.type-field').hide();

        if (type === 'audio') {
            $('#audioField').show();
        } else {
            $('#videoField').show();
        }
    }
    
    $(document).ready(function() {

        // Initial load
        toggleMediaFields();

        // On type change
        $('#type').on('change', function() {
            toggleMediaFields();
        });


        // Form validation and submission
        $('#lectureForm').validate({
            ignore: [],
            rules: {
                course_id: {
                    required: true
                },
                section_id: {
                    required: true
                },
                title: {
                    required: true,
                    minlength: 3
                },
                type: {
                    required: true
                },
                status: {
                    required: true
                },
                duration: {
                    required: true,
                    number: true,
                    min: 1
                },
                order: {
                    number: true,
                    min: 0
                },
                video: {
                    required: function() {
                        return $('#type').val() === 'video' && $('#videoPreview .existing-file').length === 0 && $('#videoPreview .new-file').length === 0;
                    },
                    extension: "mp4|avi|mov|wmv"
                },
                audio: {
                    required: function() {
                        return $('#type').val() === 'audio' && $('#audioPreview .existing-file').length === 0 && $('#audioPreview .new-file').length === 0;
                    },
                    extension: "mp3|wav|aac"
                },
                attachment: {
                    accept: "image/jpeg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/zip, application/x-rar-compressed",
                    extension: "jpg|jpeg|pdf|doc|docx|ppt|pptx|xls|xlsx|zip|rar"
                }
            },
            messages: {
                course_id: {
                    required: "Please select a course"
                },
                section_id: {
                    required: "Please select a section"
                },
                title: {
                    required: "Please enter lecture title",
                    minlength: "Lecture title must be at least 3 characters"
                },
                type: {
                    required: "Please select lecture type"
                },
                status: {
                    required: "Please select lecture status"
                },
                duration: {
                    required: "Please enter duration",
                    number: "Duration must be a valid number",
                    min: "Duration must be at least 1 minute"
                },
                order: {
                    number: "Order must be a valid number",
                    min: "Order cannot be negative"
                },
                video: {
                    required: "Please upload a video file",
                    extension: "Allowed formats: mp4, avi, mov, wmv"
                },
                attachment: {
                    extension: "Allowed formats: pdf, doc, docx, ppt, pptx, txt, zip, rar"
                }
            },
            submitHandler: function(form) {
                const $btn = $('#saveBtn');

                if ($btn.text().trim().toLowerCase() === 'update') {
                    $btn.prop('disabled', true).text('Updating...');
                } else {
                    $btn.prop('disabled', true).text('Saving...');
                }

                form.submit();
             
            },
            errorElement: 'div',
            errorClass: 'text-danger custom-error',
            errorPlacement: function(error, element) {
                $('.validation-error').hide(); // hide blade errors
                if (element.hasClass("select2")) {
                    error.insertAfter(element.next('span.select2')); // place error after select2
                } else {
                    error.insertAfter(element);
                }
            },
            focusInvalid: true
        });

        // Initialize Select2 with error handling
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2();
        }

        // initialize CKEditor on the short_description
        try {
            if (document.querySelector('#short_description')) {
                //initialize CKEditor for the short description field
                ClassicEditor
                    .create(document.querySelector('#short_description'))
                    .then(editor => {
                        editor.ui.view.editable.element.style.minHeight = '100px';
                        editor.ui.view.editable.element.style.maxHeight = '100px';
                        editor.ui.view.editable.element.style.overflowY = 'auto'; // optional scroll
                    })
                    .catch(error => {
                        toastr.error('CKEditor short_description initialization failed: ' + error.message);
                    });
            }
        } catch (error) {
            toastr.error('CKEditor general error: ' + error.message);
        }

        // Initialize CKEditor with error handling
        try {
            if (document.querySelector('#description')) {
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .then(editor => {
                        editor.ui.view.editable.element.style.minHeight = '100px';
                        editor.ui.view.editable.element.style.maxHeight = '100px';
                        editor.ui.view.editable.element.style.overflowY = 'auto'; // optional scroll
                    })
                    .catch(error => {
                        toastr.error('CKEditor short_description initialization failed: ' + error.message);
                    });
            }

        } catch (error) {
            toastr.error('CKEditor general error:', error);
        }

        // Fetch sections when course_id changes
        $('#course_id').on('change', function() {
            var courseId = $(this).val();
            var $sectionSelect = $('#section_id');
            $sectionSelect.html('<option value="">Loading...</option>');
            if (courseId) {
                $.ajax({
                    url: "{{ route('admin.courses.sections', ['course' => '__courseId__']) }}".replace('__courseId__', courseId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Select Section</option>';
                        if (data.sections && data.sections.length > 0) {
                            $.each(data.sections, function(i, section) {
                                options += '<option value="' + section.id + '">' +
                                    section.title + '</option>';
                            });
                        } else {
                            options += '<option value="">No sections found</option>';
                        }
                        $sectionSelect.html(options).trigger('change');
                    },
                    error: function() {
                        $sectionSelect.html(
                            '<option value="">Error loading sections</option>');
                    }
                });
            } else {
                $sectionSelect.html('<option value="">Select Section</option>');
            }
        });

        // Simple drag and drop functionality
        function setupDragAndDrop() {
            // Video dropzone
            const videoDropzone = document.getElementById('videoDropzone');
            const videoInput = document.getElementById('videoFileInput');

            if (videoDropzone && videoInput) {

                // Make video dropzone clickable
                videoDropzone.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    videoInput.click();
                });

                // Drag and drop for video
                videoDropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    videoDropzone.classList.add('dz-drag-hover');
                });

                videoDropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    videoDropzone.classList.remove('dz-drag-hover');
                });

                videoDropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    videoDropzone.classList.remove('dz-drag-hover');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        if (file.type.startsWith('video/')) {
                            videoInput.files = files;
                            handleVideoFile(file);
                        } else {
                            alert('Please select a valid video file');
                        }
                    }
                });

                // Video file input change
                videoInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        handleVideoFile(this.files[0]);
                    }
                });
            }

            // Attachment dropzone
            const attachmentDropzone = document.getElementById('attachmentDropzone');
            const attachmentInput = document.getElementById('attachmentFileInput');

            if (attachmentDropzone && attachmentInput) {
                // Make attachment dropzone clickable
                attachmentDropzone.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    attachmentInput.click();
                });

                // Drag and drop for attachment
                attachmentDropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    attachmentDropzone.classList.add('dz-drag-hover');
                });

                attachmentDropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    attachmentDropzone.classList.remove('dz-drag-hover');
                });

                attachmentDropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    attachmentDropzone.classList.remove('dz-drag-hover');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];
                        const allowedTypes = ['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.txt', '.zip',
                            '.rar'
                        ];
                        const fileExt = '.' + file.name.split('.').pop().toLowerCase();

                        if (allowedTypes.includes(fileExt)) {
                            attachmentInput.files = files;
                            handleAttachmentFile(file);
                        } else {
                            alert('Please select a valid document file');
                        }
                    }
                });

                // Attachment file input change
                attachmentInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        handleAttachmentFile(this.files[0]);
                    }
                });
            }
        }

        // Handle video file preview
        function handleVideoFile(file) {
            // Check file size (100MB limit)
            if (file.size > 100 * 1024 * 1024) {
                alert('Video file is too large. Maximum size is 100MB.');
                return;
            }

            // Update dropzone message
            const message = document.querySelector('#videoDropzone .dz-message h4');
            if (message) {
                message.textContent = 'Video file selected: ' + file.name;
            }

            // Hide existing file preview
            const existingFile = document.querySelector('#videoPreview .existing-file');
            if (existingFile) {
                existingFile.style.display = 'none';
            }

            // Remove any previous new file previews
            const previousNewFile = document.querySelector('#videoPreview .new-file');
            if (previousNewFile) {
                previousNewFile.remove();
            }

            // Show video preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.getElementById('videoPreview');
                const newFileDiv = document.createElement('div');
                newFileDiv.className = 'new-file';
                newFileDiv.innerHTML = `
                <video controls class="w-100" style="max-height: 200px;">
                    <source src="${e.target.result}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <small class="text-muted d-block mt-1">New video file: ${file.name}</small>`;
                previewDiv.insertBefore(newFileDiv, previewDiv.firstChild);
            };
            reader.readAsDataURL(file);
        }

        // Handle attachment file preview
        function handleAttachmentFile(file) {
            // Check file size (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                alert('Attachment file is too large. Maximum size is 10MB.');
                return;
            }

            // Update dropzone message
            const message = document.querySelector('#attachmentDropzone .dz-message h4');
            if (message) {
                message.textContent = 'Attachment selected: ' + file.name;
            }

            // Hide existing file preview
            const existingFile = document.querySelector('#attachmentPreview .existing-file');
            if (existingFile) {
                existingFile.style.display = 'none';
            }

            // Remove any previous new file previews
            const previousNewFile = document.querySelector('#attachmentPreview .new-file');
            if (previousNewFile) {
                previousNewFile.remove();
            }

            // Show attachment info
            const previewDiv = document.getElementById('attachmentPreview');
            const newFileDiv = document.createElement('div');
            newFileDiv.className = 'new-file';
            newFileDiv.innerHTML = `<div class="alert alert-info">
                <i class="mdi mdi-file-document"></i> 
                New attachment: ${file.name}
                <br><small>Size: ${(file.size / 1024 / 1024).toFixed(2)} MB</small></div>`;
            previewDiv.insertBefore(newFileDiv, previewDiv.firstChild);
        }

        // Initialize drag and drop
        setupDragAndDrop();


    });
</script>
@endpush