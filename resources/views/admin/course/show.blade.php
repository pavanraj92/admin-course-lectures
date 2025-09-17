@extends('admin::admin.layouts.master')

@section('title', 'Courses Management')

@section('page-title', 'Course Details')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Course Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Course Details</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $course->title }}</h4>
                            <div>
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>
                        <!-- Start Course Information section -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Course Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Title:</label>
                                                    <p>{{ $course->title }}</p>
                                                </div>
                                            </div>
                                            @if ($course->short_description)
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Short Description:</label>
                                                        <p>{!! $course->short_description !!}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($course->description)
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Description:</label>
                                                        <p>{!! $course->description !!}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Course Information section -->

                            <!-- Start Course Details section -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Course Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mt-2"><strong>Level:</strong> @php
                                                $levelBadgeClasses = [
                                                    'beginner' => 'dark',
                                                    'intermediate' => 'warning',
                                                    'advanced' => 'info',
                                                    'expert' => 'success',
                                                ];
                                                $badgeClass = $levelBadgeClasses[$course->level] ?? 'secondary';
                                            @endphp
                                                <span class="badge badge-{{ $badgeClass }}">
                                                    {{ ucfirst($course->level) }}
                                                </span>
                                            </li>
                                            <li class="mt-2"><strong>Language:</strong> {{ $course->language ?? 'N/A' }}
                                            </li>
                                            <li class="mt-2"><strong>Duration:</strong> {{ $course->duration ?? 'N/A' }}
                                                weeks</li>
                                            <li class="mt-2"><strong>Price:</strong>
                                                ${{ number_format($course->price ?? 0, 2) }}</li>
                                            <li class="mt-2"><strong>Max Students:</strong> {{ $course->max_students }}
                                            </li>
                                            <li class="mt-2"><strong>Status:</strong>
                                                @if ($course->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif ($course->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @elseif ($course->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span
                                                        class="badge badge-secondary">{{ ucfirst($course->status) }}</span>
                                                @endif
                                            </li>
                                            <li class="mt-2"><strong>Featured:</strong>
                                                {{ $course->is_highlighted ? 'Yes' : 'No' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Course Details section -->

                            <!-- start Categories and tags -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Categories</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($course->categories)
                                            <ul class="list-group">
                                                @foreach ($course->categories as $category)
                                                    <li class="mt-1">{{ $category->title }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">No categories assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End  Categories and tags -->

                            <!-- start tags -->
                            @if (admin\courses\Models\Course::isModuleInstalled('tags'))
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header bg-primary">
                                            <h5 class="mb-0 text-white">Tags</h5>
                                        </div>
                                        <div class="card-body">
                                            @if ($course->courseTags->count() > 0)
                                                <ul class="list-group">
                                                    @foreach ($course->courseTags as $tag)
                                                        <li class="mt-1">{{ $tag->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No tags assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!-- end tags -->

                            <!-- start cours section -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Sections</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($course->sections->count() > 0)
                                            <ul class="list-group ">
                                                @foreach ($course->sections as $section)
                                                    <li class="mt-1">{{ $section->title }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">No sections assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- end course section -->


                            <!-- start Course media files -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Media Files</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($course->thumbnail_image)
                                            <div class="mb-3">
                                                <strong>Thumbnail Image:</strong>
                                                <a href="{{ asset('storage/' . $course->thumbnail_image) }}"
                                                    target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="mdi mdi-image"></i> View Thumbnail
                                                </a>
                                            </div>
                                        @endif
                                        @if ($course->promo_video)
                                            <div>
                                                <strong>Promo Video:</strong>
                                                <a href="{{ asset('storage/' . $course->promo_video) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="mdi mdi-play"></i> Watch Video
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- start Course media files -->


                            <!-- start Course start date and end date -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Course Dates</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mt-1"><strong>Start Date:</strong>
                                                {{ $course->start_date
                                                    ? $course->start_date->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                    : 'N/A' }}
                                            </li>
                                            <li class="mt-1"><strong>End Date:</strong>
                                                {{ $course->end_date ? $course->end_date->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : 'N/A' }}
                                            </li>
                                            <li class="mt-1"><strong>Created At:</strong>
                                                {{ $course->created_at
                                                    ? $course->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                    : 'N/A' }}
                                            </li>
                                            <li class="mt-1"><strong>Updated At:</strong>
                                                {{ $course->updated_at
                                                    ? $course->updated_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                    : 'N/A' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- end Course start date and end date -->
                            <!-- start Course Quick links -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.courses.edit', $course) }}"
                                                class="btn btn-warning mb-2">
                                                <i class="fa fa-edit"></i> Edit Course
                                            </a>

                                            <button type="button" class="btn btn-danger delete-btn"
                                                data-url="{{ route('admin.courses.destroy', $course) }}">
                                                <i class="fa fa-trash"></i> Delete Course
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end Course Quick links -->
                        </div>
                    </div>
                    <!-- End card body -->
                </div>
                <!-- End card -->
            </div>
            <!-- End col -->
        </div>
        <!-- End row -->
    </div>
    <!-- End Container fluid  -->
@endsection

@push('scripts')
@endpush
