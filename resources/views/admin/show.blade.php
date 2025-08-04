@extends('admin::admin.layouts.master')

@section('title', 'Courses Management')

@section('page-title', 'Course Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Manage Courses</a></li>
<li class="breadcrumb-item active" aria-current="page">Course Details</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Course Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <div class="card-body">
                        <table class="table table-striped">
                            <tbody>  
                                <tr>
                                    <th scope="row">Title</th>
                                    <td scope="col">{{ $course->title ?? 'N/A' }}</td>
                                </tr>

                                @if($course->short_description)
                                <tr>
                                    <th scope="row">Short Description</th>
                                    <td scope="col">{{ $course->short_description }}</td>
                                </tr>
                                @endif

                                @if($course->description)
                                <tr>
                                    <th scope="row">Description</th>
                                    <td scope="col">{{ $course->description }}</td>
                                </tr>
                                @endif                               

                                <tr>
                                    <th scope="row">Language</th>
                                    <td scope="col">{{ $course->language ?? 'N/A' }}</td>
                                </tr>

                                <!-- Duration (In Weeks) -->
                                <tr>
                                    <th scope="row">Duration</th>
                                    <td scope="col">{{ $course->duration ?? 'N/A' }}</td>
                                </tr>

                                <!-- price -->
                                <tr>
                                    <th scope="row">Price</th>
                                    <td scope="col">${{ number_format($course->price ?? 0, 2) }}</td>
                                </tr>

                                <tr>
                                    <th scope="row">Level</th>
                                    <td scope="col">
                                        <span class="badge badge-{{ $course->level == 'beginner' ? 'info' : ($course->level == 'intermediate' ? 'primary' : 'dark') }}">
                                            {{ ucfirst($course->level ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Status</th>
                                    <td scope="col">
                                        @if ($course->status == 'published')
                                            <span class="badge badge-success">Published</span>
                                        @elseif ($course->status == 'draft')
                                            <span class="badge badge-warning">Draft</span>
                                        @elseif ($course->status == 'pending')
                                            <span class="badge badge-info">Pending</span>
                                        @else
                                            <span class="badge badge-danger">{{ ucfirst($course->status) }}</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Featured</th>
                                    <td scope="col">
                                        <span class="badge badge-{{ $course->is_highlighted ? 'warning' : 'secondary' }}">
                                            {{ $course->is_highlighted ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>

                                @if($course->duration)
                                <tr>
                                    <th scope="row">Duration</th>
                                    <td scope="col">{{ $course->duration }}</td>
                                </tr>
                                @endif

                                @if($course->price)
                                <tr>
                                    <th scope="row">Price</th>
                                    <td scope="col">${{ number_format($course->price, 2) }}</td>
                                </tr>
                                @endif

                                @if($course->max_students)
                                <tr>
                                    <th scope="row">Max Students</th>
                                    <td scope="col">{{ $course->max_students }}</td>
                                </tr>
                                @endif

                                @if($course->instructor)
                                <tr>
                                    <th scope="row">Instructor</th>
                                    <td scope="col">{{ $course->instructor }}</td>
                                </tr>
                                @endif

                                @if($course->start_date)
                                <tr>
                                    <th scope="row">Start Date</th>
                                    <td scope="col">{{ $course->start_date->format('F d, Y') }}</td>
                                </tr>
                                @endif

                                @if($course->end_date)
                                <tr>
                                    <th scope="row">End Date</th>
                                    <td scope="col">{{ $course->end_date->format('F d, Y') }}</td>
                                </tr>
                                @endif

                                @if($course->categories->count() > 0)
                                <tr>
                                    <th scope="row">Categories</th>
                                    <td scope="col">
                                        @foreach($course->categories as $category)
                                            <span class="badge badge-light mr-1">{{ $category->title }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif

                                @if($course->courseTags->count() > 0)
                                <tr>
                                    <th scope="row">Tags</th>
                                    <td scope="col">
                                        @foreach($course->courseTags as $tag)
                                            <span class="badge badge-info mr-1">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif

                                @if($course->sections->count() > 0)
                                <tr>
                                    <th scope="row">Course Sections</th>
                                    <td scope="col">
                                        <ol class="mb-0">
                                            @foreach($course->sections as $section)
                                                <li>{{ $section->title }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                </tr>
                                @endif

                                @if($course->thumbnail_image)
                                <tr>
                                    <th scope="row">Thumbnail URL</th>
                                    <td scope="col">
                                        <a href="{{ $course->thumbnail_image }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="mdi mdi-link"></i> View Thumbnail
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                @if($course->promo_video)
                                <tr>
                                    <th scope="row">Promo Video</th>
                                    <td scope="col">
                                        <a href="{{ $course->promo_video }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="mdi mdi-play"></i> Watch Video
                                        </a>
                                    </td>
                                </tr>
                                @endif

                                <tr>
                                    <th scope="row">Created At</th>
                                    <td scope="col">
                                        {{ $course->created_at
                                            ? $course->created_at->format(config('GET.admin_date_time_format') ?? 'F d, Y \a\t g:i A')
                                            : '—' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Updated At</th>
                                    <td scope="col">
                                        {{ $course->updated_at
                                            ? $course->updated_at->format(config('GET.admin_date_time_format') ?? 'F d, Y \a\t g:i A')
                                            : '—' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Course Content -->
</div>
<!-- End Container fluid  -->
@endsection

@push('scripts')
@endpush
