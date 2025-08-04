@extends('admin::admin.layouts.master')

@section('title', 'Courses Management')

@section('page-title', 'Course Manager')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Course Manager</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Course Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.courses.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="keyword">Course Title</label>
                                    <input type="text" name="keyword" id="keyword" class="form-control"
                                        value="{{ app('request')->query('keyword') }}" placeholder="Enter course title">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">All</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}"
                                                {{ app('request')->query('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="level">Level</label>
                                    <select name="level" id="level" class="form-control select2">
                                        <option value="">All</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level }}"
                                                {{ app('request')->query('level') == $level ? 'selected' : '' }}>
                                                {{ ucfirst($level) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="language">Language</label>
                                    <select name="language" id="language" class="form-control select2">
                                        <option value="">All</option>
                                        @foreach ($languages as $language)
                                            <option value="{{ $language }}"
                                                {{ app('request')->query('language') == $language ? 'selected' : '' }}>
                                                {{ $language }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto mt-1 text-right">
                                <div class="form-group">                                    
                                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary mt-4">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @admincan('courses_manager_create')
                            <div class="text-right">
                                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mb-3">
                                    <i class="mdi mdi-plus"></i> Create New Course
                                </a>
                            </div>
                        @endadmincan

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">@sortablelink('title', 'Title', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">Categories</th>
                                        <th scope="col">@sortablelink('level', 'Level', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">@sortablelink('language', 'Language', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">@sortablelink('is_highlight', 'Highlighted', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($courses) && $courses->count() > 0)
                                        @php
                                            $i = ($courses->currentPage() - 1) * $courses->perPage() + 1;
                                        @endphp
                                        @foreach ($courses as $course)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>
                                                    <strong>{{ $course->title }}</strong>
                                                    @if ($course->short_description)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($course->short_description, 50) }}</small>
                                                    @endif
                                                    @if ($course->thumbnail_url)
                                                        <br><small class="text-success"><i class="mdi mdi-image"></i> Has
                                                            thumbnail</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($course->categories->count() > 0)
                                                        @foreach ($course->categories as $category)
                                                            <span class="badge badge-light">{{ $category->title }}</span>
                                                        @endforeach
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
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
                                                <td>{{ $course->language ?? '—' }}</td>
                                                <td>
                                                    @if ($course->is_highlight == 1)
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to remove highlight"
                                                            data-url="{{ route('admin.courses.updateHighlight') }}"
                                                            data-method="POST" data-status="0"
                                                            data-id="{{ $course->id }}"
                                                            class="btn btn-success btn-sm update-status">Yes</a>
                                                    @else
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to highlight"
                                                            data-url="{{ route('admin.courses.updateHighlight') }}"
                                                            data-method="POST" data-status="1"
                                                            data-id="{{ $course->id }}"
                                                            class="btn btn-warning btn-sm update-status">No</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($course->status == 'approved')
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to change status"
                                                            data-url="{{ route('admin.courses.updateStatus') }}"
                                                            data-method="POST" data-status="rejected"
                                                            data-id="{{ $course->id }}"
                                                            class="btn btn-success btn-sm update-status">Approved</a>
                                                    @elseif ($course->status == 'rejected')
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to publish"
                                                            data-url="{{ route('admin.courses.updateStatus') }}"
                                                            data-method="POST" data-status="approved"
                                                            data-id="{{ $course->id }}"
                                                            class="btn btn-danger btn-sm update-status">Rejected</a>
                                                    @else
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to publish"
                                                            data-url="{{ route('admin.courses.updateStatus') }}"
                                                            data-method="POST" data-status="approved"
                                                            data-id="{{ $course->id }}"
                                                            class="btn btn-warning btn-sm update-status">{{ ucfirst($course->status) }}</a>
                                                        {{-- <span class="badge badge-danger">{{ ucfirst($course->status) }}</span> --}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $course->created_at ? $course->created_at->format(config('GET.admin_date_time_format') ?? 'M d, Y') : '—' }}
                                                </td>
                                                <td>
                                                    @admincan('courses_manager_edit')
                                                        <a href="{{ route('admin.courses.edit', $course) }}"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Edit this record" class="btn btn-success btn-sm"><i
                                                                class="mdi mdi-pencil"></i></a>
                                                    @endadmincan
                                                    @admincan('courses_manager_view')
                                                        <a href="{{ route('admin.courses.show', $course) }}"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="View this record" class="btn btn-warning btn-sm"><i
                                                                class="mdi mdi-eye"></i></a>
                                                    @endadmincan
                                                    @admincan('courses_manager_delete')
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Delete this record"
                                                            data-url="{{ route('admin.courses.destroy', $course) }}"
                                                            data-text="Are you sure you want to delete this record?"
                                                            data-method="DELETE"
                                                            class="btn btn-danger btn-sm delete-record"><i
                                                                class="mdi mdi-delete"></i></a>
                                                    @endadmincan
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center">No courses found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move to the right side-->
                            @if ($courses->count() > 0)
                                {{ $courses->links('admin::pagination.custom-admin-pagination') }}
                            @endif

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
