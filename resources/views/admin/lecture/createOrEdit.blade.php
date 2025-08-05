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
        @if(isset($lecture))
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
                                        placeholder="Enter lecture title">
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

                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="content" id="content" class="form-control ckeditor" rows="6"
                                placeholder="Enter lecture content/notes">{{ old('content', $lecture->content ?? '') }}</textarea>
                            @error('content')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Duration (minutes)</label>
                                    <input type="number" name="duration" id="duration" class="form-control"
                                        value="{{ old('duration', $lecture->duration ?? '') }}"
                                        placeholder="Enter duration in minutes" min="1">
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
                                <i class="mdi mdi-content-save"></i>
                                {{ isset($lecture) ? 'Update Lecture' : 'Save Lecture' }}
                            </button>
                            <a href="{{ route('admin.lectures.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Section <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-control select2">
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ (old('section_id', $lecture->section_id ?? '') == $section->id) ? 'selected' : '' }}>
                                            {{ $section->title }}
                                        </option>
                                        @endforeach
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
                                    <select name="type" id="type" class="form-control select2">
                                        @foreach($types as $type)
                                        <option value="{{ $type }}"
                                            {{ (old('type', $lecture->type ?? 'video') == $type) ? 'selected' : '' }}>
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
                        <div class="form-group">
                            <label>Video File</label>
                            <div id="videoDropzone" class="dropzone">
                                <div class="dz-message">
                                    <i class="mdi mdi-video mdi-48px text-muted"></i>
                                    <h4>Drop video file here or click to upload</h4>
                                    <p class="text-muted">Supported formats: MP4, AVI, MOV, WMV (Max: 100MB)</p>
                                </div>
                            </div>
                            <!-- Hidden file input -->
                            <input type="file" id="videoFileInput" name="video" accept="video/*" style="display: none;">
                            @error('video')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                            <div id="videoPreview" class="mt-2">
                                @if(isset($lecture) && $lecture->video)
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
                                    <p class="text-muted">Supported formats: PDF, DOC, DOCX, PPT, PPTX (Max: 10MB)</p>
                                </div>
                            </div>
                            <!-- Hidden file input -->
                            <input type="file" id="attachmentFileInput" name="attachment" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip,.rar" style="display: none;">
                            @error('attachment')
                            <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                            <div id="attachmentPreview" class="mt-2">
                                @if(isset($lecture) && $lecture->attachment)
                                <div class="existing-file">
                                    <a href="{{ $lecture->attachment_url }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="mdi mdi-download"></i> Download Current File
                                    </a>
                                    <small class="text-muted d-block mt-1">{{ basename($lecture->attachment) }}</small>
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
                                        @foreach($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ (old('status', $lecture->status ?? 'draft') == $status) ? 'selected' : '' }}>
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
<!-- jQuery (if not already loaded) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr for notifications -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Configure toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "3000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(document).ready(function() {
        // Initialize CKEditor with error handling
        try {
            if (document.querySelector('#description')) {
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        placeholder: 'Enter lecture description...',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    })
                    .catch(error => {
                        console.log('CKEditor description init error:', error);
                    });
            }

            if (document.querySelector('#content')) {
                ClassicEditor
                    .create(document.querySelector('#content'), {
                        placeholder: 'Enter detailed lecture content/notes...',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'blockQuote', '|',
                            'undo', 'redo'
                        ]
                    })
                    .catch(error => {
                        console.log('CKEditor content init error:', error);
                    });
            }
        } catch (error) {
            console.log('CKEditor general error:', error);
        }

        // Initialize Select2 with error handling
        try {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
            }
        } catch (error) {
            console.log('Select2 error:', error);
        }

        // Simple drag and drop functionality
        function setupDragAndDrop() {
            console.log('Setting up drag and drop...');

            // Video dropzone
            const videoDropzone = document.getElementById('videoDropzone');
            const videoInput = document.getElementById('videoFileInput');

            if (videoDropzone && videoInput) {
                console.log('Video dropzone elements found');

                // Make video dropzone clickable
                videoDropzone.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Video dropzone clicked');
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
                    console.log('Video file input changed');
                    if (this.files.length > 0) {
                        handleVideoFile(this.files[0]);
                    }
                });
            }

            // Attachment dropzone
            const attachmentDropzone = document.getElementById('attachmentDropzone');
            const attachmentInput = document.getElementById('attachmentFileInput');

            if (attachmentDropzone && attachmentInput) {
                console.log('Attachment dropzone elements found');

                // Make attachment dropzone clickable
                attachmentDropzone.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Attachment dropzone clicked');
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
                        const allowedTypes = ['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.txt', '.zip', '.rar'];
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
                    console.log('Attachment file input changed');
                    if (this.files.length > 0) {
                        handleAttachmentFile(this.files[0]);
                    }
                });
            }
        }

        // Handle video file preview
        function handleVideoFile(file) {
            console.log('Video file selected:', file.name);

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
                <small class="text-muted d-block mt-1">New video file: ${file.name}</small>
            `;
                previewDiv.insertBefore(newFileDiv, previewDiv.firstChild);
            };
            reader.readAsDataURL(file);
        }

        // Handle attachment file preview
        function handleAttachmentFile(file) {
            console.log('Attachment file selected:', file.name);

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
            newFileDiv.innerHTML = `
            <div class="alert alert-info">
                <i class="mdi mdi-file-document"></i> 
                New attachment: ${file.name}
                <br><small>Size: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
            </div>
        `;
            previewDiv.insertBefore(newFileDiv, previewDiv.firstChild);
        }

        // Initialize drag and drop
        setupDragAndDrop();

        // Form validation and submission
        $('#lectureForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            let isValid = true;

            // Check required fields
            if (!$('#section_id').val()) {
                isValid = false;
                toastr.error('Please select a section');
            }

            if (!$('#title').val().trim()) {
                isValid = false;
                toastr.error('Please enter lecture title');
            }

            if (!$('#type').val()) {
                isValid = false;
                toastr.error('Please select lecture type');
            }

            if (!$('#status').val()) {
                isValid = false;
                toastr.error('Please select lecture status');
            }

            if (isValid) {
                // Create FormData object from the form
                let formData = new FormData(document.getElementById('lectureForm'));

                // Files are already included in FormData from the file inputs

                // Show loading state
                $('#saveBtn').prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message || 'Lecture saved successfully!');
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.href = "{{ route('admin.lectures.index') }}";
                            }
                        } else {
                            toastr.error(response.message || 'Something went wrong!');
                            $('#saveBtn').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> {{ isset($lecture) ? "Update Lecture" : "Save Lecture" }}');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(', ');
                        }
                        toastr.error(errorMessage);
                        $('#saveBtn').prop('disabled', false).html('<i class="mdi mdi-content-save"></i> {{ isset($lecture) ? "Update Lecture" : "Save Lecture" }}');
                    }
                });
            }
        });

        // Remove validation errors on input
        $('.form-control').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
    });
</script>
@endpush