@extends('admin::admin.layouts.master')

@section('title', 'Courses Management')

@section('page-title', isset($course) ? 'Edit Course' : 'Create Course')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Course Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ isset($course) ? 'Edit Course' : 'Create Course' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Course Content -->
        <form action="{{ isset($course) ? route('admin.courses.update', $course) : route('admin.courses.store') }}"
            method="POST" id="courseForm" enctype="multipart/form-data">
            @csrf
            @if (isset($course))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-8">
                    <!-- card section -->
                    <div class="card bg-white">
                        <!--card header section -->
                        <div class="card-header bg-white border-bottom border-gray-200">
                            <h4 class="card-title">
                                Course Information
                            </h4>
                        </div>
                        <!--card body section -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Course Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title', $course->title ?? '') }}"
                                            placeholder="Enter course title" required>
                                        @error('title')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            value="{{ old('slug', $course->slug ?? '') }}"
                                            placeholder="Auto-generated if empty">
                                        @error('slug')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Short Description</label>
                                <textarea name="short_description" id="short_description" class="form-control" rows="2"
                                    placeholder="Enter brief course description">{{ old('short_description', $course->short_description ?? '') }}</textarea>
                                @error('short_description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control description-editor" rows="4"
                                    placeholder="Enter detailed course description">{{ old('description', $course->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Language <span class="text-danger">*</span></label>
                                        <!--select2 drop down for the languages-->
                                        <select name="language" id="language" class="form-control select2" required>
                                            @foreach($languages as $lang)
                                                <option value="{{ $lang }}" {{ old('language', $course->language ?? 'English') == $lang ? 'selected' : '' }}>
                                                    {{ Str::ucfirst($lang) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('language')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Duration (In Weeks) <span class="text-danger">*</span></label>
                                        <input type="text" name="duration" id="duration" class="form-control"
                                            value="{{ old('duration', $course->duration ?? '') }}"
                                            placeholder="e.g. 8 weeks">
                                        @error('duration')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price <span class="text-danger">*</span></label>
                                        <input type="number" name="price" id="price" class="form-control"
                                            step="0.01" value="{{ old('price', $course->price ?? '') }}"
                                            placeholder="Enter price">
                                        @error('price')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Max Students <span class="text-danger">*</span></label>
                                        <input type="number" name="max_students" id="max_students" class="form-control"
                                            value="{{ old('max_students', $course->max_students ?? '') }}"
                                            placeholder="Enter max students">
                                        @error('max_students')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="text" name="start_date" id="start_date" class="form-control"
                                            value="{{ old('start_date', isset($course->start_date) ? $course->start_date->format('Y-m-d') : '') }}"
                                            autocomplete="off" readonly placeholder="Select Start Date">
                                        @error('start_date')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="text" name="end_date" id="end_date" class="form-control"
                                            value="{{ old('end_date', isset($course->end_date) ? $course->end_date->format('Y-m-d') : '') }}"
                                            autocomplete="off" readonly placeholder="Select End Date">
                                        @error('end_date')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Thumbnail Image <span class="text-danger">*</span></label>
                                        <input type="file" name="thumbnail_image" class="form-control"
                                            id="imageInput" accept="image/*">
                                        @error('thumbnail_image')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div id="imagePreview">
                                        @if (isset($course) && $course->thumbnail_image)
                                            <img src="{{ asset('storage/' . $course->thumbnail_image) }}"
                                                alt="Course Image" class="thumbnail w-100">
                                        @else
                                            <img src="{{ asset('images/noimage.png') }}"
                                                alt="Default Course Image" class="w-100 h-50">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Promo Video <span class="text-danger">*</span></label>
                                        <input type="file" name="promo_video" class="form-control" id="videoInput"
                                            accept="video/*">
                                        @error('promo_video')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div id="videoPreview">
                                        @if (isset($course) && $course->promo_video)
                                            <video controls class="w-100">
                                                <source src="{{ asset('storage/' . $course->promo_video) }}"
                                                    type="video/mp4">Your browser does not support the video tag.
                                            </video>
                                        @else                                            
                                            <img src="{{ asset('images/novideo.png') }}"
                                                alt="Default Course Image" class="w-50">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    {{ isset($course) ? 'Update' : 'Save' }}
                                </button>
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                    Back
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
                                Categories and Tags
                            </h4>
                        </div>
                        <!--card body section -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Categories <span class="text-danger">*</span></label>
                                        <select name="categories[]" id="categories" class="form-control select2"
                                            multiple>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ (isset($course) && $course->categories->contains($category->id)) ||
                                                    (is_array(old('categories')) && in_array($category->id, old('categories')))
                                                        ? 'selected'
                                                        : '' }}>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categories')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <select name="course_tags[]" id="course_tags" class="form-control select2"
                                            multiple>
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    {{ (isset($course) && $course->courseTags->contains($tag->id)) ||
                                                    (is_array(old('course_tags')) && in_array($tag->id, old('course_tags')))
                                                        ? 'selected'
                                                        : '' }}>
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_tags')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>

                    <!-- card section -->
                    <div class="card bg-white">
                        <!--card header section -->
                        <div class="card-header bg-white border-bottom border-gray-200">
                            <h4 class="card-title d-inline-block">
                                Course Sections
                            </h4>
                            <button type="button" class="btn btn-sm btn-primary float-right d-inline-block"
                                id="addSectionBtn">
                                <i class="mdi mdi-plus"></i> Add Section
                            </button>
                        </div>
                        <!--card body section -->
                        <div class="card-body">
                            <div id="sectionsContainer">
                                @if (isset($course) && $course->sections->count() > 0)
                                    @foreach ($course->sections as $index => $section)
                                        <div class="section-item" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        {{-- <label>Section Title</label> --}}
                                                        <input type="text" name="sections[{{ $index }}][title]"
                                                            class="form-control section-title"
                                                            value="{{ old('sections.' . $index . '.title', $section->title) }}"
                                                            placeholder="Enter section title">
                                                        <input type="hidden" name="sections[{{ $index }}][id]"
                                                            value="{{ $section->id }}">
                                                        @error("sections.{$index}.title")
                                                            <div class="text-danger validation-error">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        {{-- <label>&nbsp;</label> --}}
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm d-block removeSectionBtn">
                                                            <i class="mdi mdi-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="section-item" data-index="0">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    {{-- <label>Section Title</label> --}}
                                                    <input type="text" name="sections[0][title]"
                                                        class="form-control section-title"
                                                        value="{{ old('sections.0.title') }}"
                                                        placeholder="Enter section title">
                                                    @error('sections.0.title')
                                                        <div class="text-danger validation-error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    {{-- <label>&nbsp;</label> --}}
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm d-block removeSectionBtn">
                                                        <i class="mdi mdi-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="text-muted small">
                                <i class="mdi mdi-information"></i> Add sections to organize your course content. Sections
                                help students navigate through the course material.
                                Example: "Introduction", "New Features", "Basic Foundations", "Advanced Topics", etc.
                            </div>
                        </div>
                        <!--card body section -->
                    </div>
                    <!--card section -->

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
                                        <label>Level <span class="text-danger">*</span></label>
                                        <select name="level" id="level" class="form-control select2" required>
                                            <option value="">Select Level</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level }}"
                                                    {{ old('level', $course->level ?? '') == $level ? 'selected' : '' }}>
                                                    {{ ucfirst($level) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('level')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control select2" required>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}"
                                                    {{ old('status', $course->status ?? 'draft') == $status ? 'selected' : '' }}>
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
                                            <input type="checkbox" name="is_highlight" id="is_highlight"
                                                class="custom-control-input" value="1"
                                                {{ old('is_highlight', $course->is_highlight ?? false) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_highlight">Featured
                                                Course</label>
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
        <!-- End Course Content -->
    </div>
@endsection

@push('styles')
    <!-- ckeditor CSS -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- datepicker CSS jquery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">    
    <style>
        #addSectionBtn {
            float: right;
            margin-top: -5px;
        }
        .card-header h4 {
            margin-bottom: 0;
        }
        .form-control[readonly] {
            background-color: #ffffff;
        }     
    </style>
@endpush

@push('scripts')
    <!-- jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Include the CKEditor script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <!-- jQuery UI for datepicker -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });

            // Auto-generate slug from title
            $('#title').on('input', function() {
                if ($('#slug').val() === '') {
                    var slug = $(this).val().toLowerCase()
                        .replace(/[^a-z0-9 -]/g, '') // remove invalid characters
                        .replace(/\s+/g, '-') // replace spaces with hyphens
                        .replace(/-+/g, '-'); // remove duplicate hyphens
                    $('#slug').val(slug);
                }
            });

            // Remove old image input logic (Dropzone now handles preview)

            // jQuery validation for the form
            $('#courseForm').validate({
                ignore: [],
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    level: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                    language: {
                        required: true                      
                    },
                    duration: {
                        required: true,
                        minlength: 1
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    max_students: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    start_date: {
                        date: true,                        
                    },
                    end_date: {
                        date: true,
                        
                    },
                    // thumbnail_image: {
                    //     required: function() {
                    //         return $('input[name="thumbnail_image"]').val() === '' && $('#imagePreview img.thumbnail').length === 0;
                    //     }
                    // },
                    // promo_video: {
                    //     required: function() {
                    //         return $('input[name="promo_video"]').val() === '' && $('#videoPreview video').length === 0;
                    //     }
                    // },
                    'categories[]': {
                        required: true,
                        minlength: 1
                    },
                },
                messages: {
                    title: {
                        required: "Please enter a course title",
                        minlength: "Title must be at least 3 characters long"
                    },
                    level: {
                        required: "Please select a course level"
                    },
                    status: {
                        required: "Please select a status"
                    },
                    language: {
                        required: "Please enter a language",
                        minlength: "Language must be at least 2 characters long"
                    },
                    duration: {
                        required: "Please enter a duration",
                        minlength: "Duration must be at least 1 week"
                    },
                    price: {
                        required: "Please enter a price",
                        number: "Please enter a valid price",
                        min: "Price cannot be negative"
                    },
                    max_students: {
                        required: "Please enter the maximum number of students",
                        digits: "Max students must be a valid number",
                        min: "Max students must be at least 1"
                    },
                    start_date: {
                        date: "Please enter a valid start date"
                    },
                    end_date: {
                        date: "Please enter a valid end date"
                    },
                    thumbnail_image: {
                        required: "Please upload a thumbnail image"
                    },
                    promo_video: {
                        required: "Please upload a promo video"
                    },
                    'categories[]': {
                        required: "Please select at least one category"
                    }
                },
                submitHandler: function(form) {
                    const $btn = $('#saveBtn');
                    $btn.prop('disabled', true).html(
                        '<i class="mdi mdi-loading mdi-spin"></i> Saving...');
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide(); // hide blade errors
                    if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2')); // place error after select2 container
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

             // Image preview functionality
            $('#imageInput').on('change', function(event) {
                const input = event.target;
                const preview = $('#imagePreview');
                preview.empty(); // Remove old image

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.html('<img src="' + e.target.result +
                            '" style="max-width:200px; max-height:120px;" alt="Course Preview" />');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Date validation - end date should be after start date
            $('#start_date, #end_date').on('change', function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
                    toastr.error('End date must be after start date', {
                        position: 'top-right',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 3000
                    });
                    $('#end_date').val('');
                }
            });

            // Sections functionality
            let sectionIndex =
                {{ isset($course) && $course->sections->count() > 0 ? $course->sections->count() : 1 }};

            // Add new section
            $('#addSectionBtn').on('click', function() {
                const sectionHtml = `
                    <div class="section-item" data-index="${sectionIndex}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    {{-- <label>Section Title</label> --}}                                    
                                    <input type="text" name="sections[${sectionIndex}][title]" 
                                           class="form-control section-title" 
                                           placeholder="Enter section title">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    {{-- <label>&nbsp;</label> --}}
                                    <button type="button" class="btn btn-danger btn-sm d-block removeSectionBtn">
                                        <i class="mdi mdi-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#sectionsContainer').append(sectionHtml);
                updateSectionLabels();
                sectionIndex++;
            });

            // Remove section
            $(document).on('click', '.removeSectionBtn', function() {
                if ($('.section-item').length > 1) {
                    $(this).closest('.section-item').remove();
                    updateSectionLabels();
                } else {
                    //alert('At least one section is required.');
                    toastr.error('At least one section is required.', {
                        position: 'top-right',
                        progressBar: true,
                        closeButton: true,
                        timeOut: 3000
                    });
                }
            });

            // Update section labels after add/remove
            function updateSectionLabels() {
                $('.section-item').each(function(index) {
                    // $(this).find('label').first().text('Section Title');
                    $(this).attr('data-index', index);

                    // Update input names
                    const titleInput = $(this).find('.section-title');
                    const currentName = titleInput.attr('name');
                    const newName = currentName.replace(/sections\[\d+\]/, `sections[${index}]`);
                    titleInput.attr('name', newName);

                    // Update hidden ID input if exists
                    const hiddenInput = $(this).find('input[type="hidden"]');
                    if (hiddenInput.length) {
                        const hiddenName = hiddenInput.attr('name');
                        const newHiddenName = hiddenName.replace(/sections\[\d+\]/, `sections[${index}]`);
                        hiddenInput.attr('name', newHiddenName);
                    }
                });
            }
        });
    </script>
    <!-- datepicker initialization -->
    <script>
        $(function() {
            $("#start_date, #end_date").datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10",
                autoclose: true,
            });

            // end date should be after start date
            $("#start_date").on("change", function() {
                var startDate = $(this).datepicker("getDate");
                if (startDate) {
                    $("#end_date").datepicker("option", "minDate", startDate);
                }
            });

            // when end date is selected, set max date for start date
            $("#end_date").on("change", function() {
                var endDate = $(this).datepicker("getDate");
                if (endDate) {
                    $("#start_date").datepicker("option", "maxDate", endDate);
                }
            });
        });
    </script>
    <!-- Initialize CKEditor -->
    <script>
        //initialize CKEditor for the short description field
        ClassicEditor
            .create(document.querySelector('#short_description'))
            .then(editor => {
                editor.ui.view.editable.element.style.minHeight = '100px';
                editor.ui.view.editable.element.style.maxHeight = '100px';
                editor.ui.view.editable.element.style.overflowY = 'auto'; // optional scroll
            })
            .catch(error => {
                console.error(error);
            });

        // Initialize CKEditor for the description field
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(editor => {
                editor.ui.view.editable.element.style.minHeight = '250px';
                editor.ui.view.editable.element.style.maxHeight = '250px';
                editor.ui.view.editable.element.style.overflowY = 'auto'; // optional scroll
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
