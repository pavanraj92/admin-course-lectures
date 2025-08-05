@extends('admin::admin.layouts.master')

@section('title', 'Courses Management')

@section('page-title', 'Course Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Course Manager</a></li>
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
                                        <td scope="col" colspan="3">{{ $course->title ?? 'N/A' }}</td>
                                    </tr>
                                    @if ($course->short_description)
                                        <tr>
                                            <th scope="row">Short Description</th>
                                            <td scope="col" colspan="3">{{ $course->short_description }}</td>
                                        </tr>
                                    @endif

                                    @if ($course->description)
                                        <tr>
                                            <th scope="row">Description</th>
                                            <td scope="col" colspan="3">{{ $course->description }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th scope="row">Language</th>
                                        <td scope="col">{{ $course->language ?? 'N/A' }}</td>
                                        <th scope="row">Duration (In Weeks)</th>
                                        <td scope="col">{{ $course->duration ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <!-- price -->
                                        <th scope="row">Price</th>
                                        <td scope="col">${{ number_format($course->price ?? 0, 2) }}</td>
                                        <th scope="row">Max Students</th>
                                        <td scope="col">{{ $course->max_students }}</td>
                                    </tr>
                                    <!--start date and end date-->
                                    <tr>
                                        <th scope="row">Start Date</th>
                                        <td scope="col">
                                            {{ $course->start_date ? $course->start_date->format('Y-m-d') : 'N/A' }}</td>
                                        <th scope="row">End Date</th>
                                        <td scope="col">
                                            {{ $course->end_date ? $course->end_date->format('Y-m-d') : 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Level</th>
                                        <td scope="col">
                                            @php
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
                                        </td>
                                        <th scope="row">Status</th>
                                        <td scope="col">
                                            @if ($course->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif ($course->status == 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @elseif ($course->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($course->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Featured</th>
                                        <td scope="col">
                                            <span
                                                class="badge badge-{{ $course->is_highlighted ? 'warning' : 'secondary' }}">
                                                {{ $course->is_highlighted ? 'Yes' : 'No' }}
                                            </span>
                                        </td>

                                        <th scope="row">Categories</th>
                                        <td scope="col">
                                            @foreach ($course->categories as $category)
                                                <span class="badge badge-light mr-1">{{ $category->title }}</span>
                                            @endforeach
                                        </td>
                                    </tr>

                                    <tr>
                                        @if ($course->thumbnail_image)
                                            <th scope="row">Thumbnail URL</th>
                                            <td scope="col">
                                                <a href="{{ asset('storage/' . $course->thumbnail_image) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="mdi mdi-link"></i> View Thumbnail
                                                </a>
                                            </td>
                                        @endif
                                        @if ($course->promo_video)
                                            <th scope="row">Promo Video</th>
                                            <td scope="col">
                                                <a href="{{ asset('storage/' . $course->promo_video) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="mdi mdi-play"></i> Watch Video
                                                </a>
                                            </td>
                                        @endif
                                    </tr>

                                    @if ($course->courseTags->count() > 0)
                                        <tr>
                                            <th scope="row">Tags</th>
                                            <td scope="col" colspan="3">
                                                @foreach ($course->courseTags as $tag)
                                                    <span class="badge badge-dark mr-1">{{ $tag->name }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($course->sections->count() > 0)
                                        <tr>
                                            <th scope="row">Course Sections</th>
                                            <td scope="col" colspan="3">
                                                <ol class="mb-0">
                                                    @foreach ($course->sections as $section)
                                                        <li>{{ $section->title }}</li>
                                                    @endforeach
                                                </ol>
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
