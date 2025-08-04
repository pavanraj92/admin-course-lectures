@extends('admin::admin.layouts.master')

@section('title', 'Courses Management')

@section('page-title', isset($course) ? 'Edit Course' : 'Create Course')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Manage Courses</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ isset($course) ? 'Edit Course' : 'Create Course' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Course Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <form
                        action="{{ isset($course) ? route('admin.courses.update', $course) : route('admin.courses.store') }}"
                        method="POST" id="courseForm" enctype="multipart/form-data">
                        @csrf
                        @if (isset($course))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Course Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title', $course->title ?? '') }}" placeholder="Enter course title"
                                        required>
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
                            <textarea name="description" id="description" class="form-control" rows="4"
                                placeholder="Enter detailed course description">{{ old('description', $course->description ?? '') }}</textarea>
                            @error('description')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Language <span class="text-danger">*</span></label>
                                    <input type="text" name="language" id="language" class="form-control"
                                        value="{{ old('language', $course->language ?? 'English') }}"
                                        placeholder="Enter language" required>
                                    @error('language')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Duration (In Weeks) <span class="text-danger">*</span></label>
                                    <input type="text" name="duration" id="duration" class="form-control"
                                        value="{{ old('duration', $course->duration ?? '') }}" placeholder="e.g. 8 weeks">
                                    @error('duration')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price <span class="text-danger">*</span></label>
                                    <input type="number" name="price" id="price" class="form-control" step="0.01"
                                        value="{{ old('price', $course->price ?? '') }}" placeholder="Enter price">
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
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ old('start_date', isset($course->start_date) ? $course->start_date->format('Y-m-d') : '') }}">
                                    @error('start_date')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ old('end_date', isset($course->end_date) ? $course->end_date->format('Y-m-d') : '') }}">
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
                                    <input type="file" name="thumbnail_image" class="form-control" id="imageInput"
                                        accept="image/*">
                                    @error('thumbnail_image')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror                                  
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div id="imagePreview">
                                    @if (isset($course) && $course->thumbnail_image)
                                        <img src="{{ asset('storage/' . $course->thumbnail_image) }}"
                                            alt="Course Image" class="w-50">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Thumbnail Video <span class="text-danger">*</span></label>
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
                                        <video controls class="w-50">
                                            <source src="{{ asset('storage/' . $course->promo_video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Categories <span class="text-danger">*</span></label>
                                    <select name="categories[]" id="categories" class="form-control select2" multiple>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tags</label>
                                    <select name="course_tags[]" id="course_tags" class="form-control select2" multiple>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="is_highlight" id="is_highlight"
                                            class="custom-control-input" value="1"
                                            {{ old('is_highlight', $course->is_highlight ?? false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_highlight">Featured Course</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="mdi mdi-content-save"></i>
                                {{ isset($course) ? 'Update Course' : 'Save Course' }}
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Course Content -->
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS for the courses -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">    
@endpush

@push('scripts')
    <!-- jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
                        required: true,
                        minlength: 2
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
                        date: true
                    },
                    end_date: {
                        date: true
                    },
                    thumbnail_image: {   
                        required: function() {
                             return {{ isset($course) ? 'false' : 'true' }};
                        },                     
                        //extension: "jpg,jpeg,png"
                    },
                    promo_video: {
                        required: function() {                           
                            return {{ isset($course) ? 'false' : 'true' }};
                        },
                        //extension: "mp4,mov,avi,wmv"
                    },
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
                        number: "Please enter a valid price",
                        min: "Price cannot be negative"
                    },
                    max_students: {
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
                        required: "Please upload a thumbnail image",
                        extension: "Only jpg, jpeg, and png formats are allowed"
                    },
                    promo_video: {
                        required: "Please upload a promo video",
                        extension: "Only mp4, mov, avi, and wmv formats are allowed"
                    },
                    categories: {
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
                    error.insertAfter(element);
                }
            });

            // Date validation - end date should be after start date
            $('#start_date, #end_date').on('change', function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
                    alert('End date must be after start date');
                    $('#end_date').val('');
                }
            });
        });
    </script>
@endpush
