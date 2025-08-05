@extends('admin::admin.layouts.master')

@section('title', 'Lectures Management')

@section('page-title', 'Manage Lectures')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Manage Lectures</li>
@endsection

@section('content')
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Lecture Content -->
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.lectures.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="keyword">Lecture Title</label>
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                    value="{{ app('request')->query('keyword') }}" placeholder="Enter lecture title">
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
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control select2">
                                    <option value="">All</option>
                                    @foreach ($types as $type)
                                    <option value="{{ $type }}"
                                        {{ app('request')->query('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-info mr-2">Filter</button>
                                    <a href="{{ isset($course) ? route('admin.lectures.index', ['course' => $course->id]) : route('admin.lectures.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
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
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="card-title mb-0">
                                @if(isset($course))
                                Lectures for "{{ $course->title }}"
                                @else
                                All Lectures
                                @endif
                            </h4>
                            @if(isset($course))
                            <small class="text-muted">
                                <a href="{{ route('admin.courses.index') }}">← Back to Courses</a>
                            </small>
                            @endif
                        </div>
                        <a href="{{ route('admin.lectures.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Lecture
                        </a>
                    </div>

                    @if ($lectures->count() > 0)
                    @php
                    // Group lectures by section
                    $lecturesBySection = $lectures->groupBy(function($lecture) {
                    return $lecture->section ? $lecture->section->title : 'No Section';
                    });
                    @endphp

                    @foreach($lecturesBySection as $sectionTitle => $sectionLectures)
                    <div class="mb-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fa fa-folder-open"></i>
                            {{ $sectionTitle }}
                            <span class="badge badge-secondary ml-2">{{ $sectionLectures->count() }} lectures</span>
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="30%">@sortablelink('title', 'Title', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                        <th width="10%">@sortablelink('type', 'Type', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                        <th width="10%">Duration</th>
                                        <th width="8%">Order</th>
                                        <th width="8%">Highlight</th>
                                        <th width="10%">@sortablelink('status', 'Status', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                        <th width="10%">@sortablelink('created_at', 'Created At', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                        <th width="9%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sectionLectures->sortBy('order') as $index => $lecture)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $lecture->title }}</h6>
                                                    @if($lecture->short_description)
                                                    <small class="text-muted">{{ Str::limit($lecture->short_description, 50) }}</small>
                                                    @endif
                                                    <div class="mt-1">
                                                        @if($lecture->hasVideo())
                                                        <span class="badge badge-success badge-sm mr-1">
                                                            <i class="fa fa-video"></i> Video
                                                        </span>
                                                        @endif
                                                        @if($lecture->hasAttachment())
                                                        <span class="badge badge-info badge-sm">
                                                            <i class="fa fa-paperclip"></i> Attachment
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $lecture->type_badge_class }}">
                                                {{ ucfirst($lecture->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $lecture->formatted_duration }}</td>
                                        <td>
                                            <span class="badge badge-light">{{ $lecture->order }}</span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="{{ $lecture->is_highlight ? 'Click to remove highlight' : 'Click to highlight' }}"
                                                data-url="{{ route('admin.lectures.updateHighlight') }}"
                                                data-method="POST"
                                                data-status="{{ $lecture->is_highlight ? '0' : '1' }}"
                                                data-id="{{ $lecture->id }}"
                                                class="btn {{ $lecture->is_highlight ? 'btn-success' : 'btn-outline-warning' }} btn-sm update-status">
                                                {{ $lecture->is_highlight ? 'Yes' : 'No' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Click to change status"
                                                data-url="{{ route('admin.lectures.updateStatus') }}"
                                                data-method="POST"
                                                data-status="{{ $lecture->status == 'archived' ? 'published' : ($lecture->status == 'draft' ? 'published' : 'archived') }}"
                                                data-id="{{ $lecture->id }}"
                                                class="btn {{ $lecture->status_badge_class }} btn-sm update-status">
                                                {{ ucfirst($lecture->status) }}
                                            </a>
                                        </td>
                                        <td>{{ $lecture->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @admincan('lectures_manager_view')
                                                <a href="{{ route('admin.lectures.show', $lecture) }}"
                                                    class="btn btn-info btn-sm mr-1" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endadmincan
                                                @admincan('lectures_manager_edit')
                                                <a href="{{ route('admin.lectures.edit', $lecture) }}"
                                                    class="btn btn-warning btn-sm mr-1" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endadmincan
                                                @admincan('lectures_manager_delete')
                                                <button type="button"
                                                    class="btn btn-danger btn-sm delete-btn"
                                                    data-url="{{ route('admin.lectures.destroy', $lecture) }}"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endadmincan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                        <!-- Pagination -->
                        @if ($lectures->count() > 0)
                        {{ $lectures->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    @else
                    <div class="text-center py-5">
                        <i class="fa fa-video fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No lectures found</h5>
                        <p class="text-muted">Start by creating your first lecture.</p>
                        <a href="{{ route('admin.lectures.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Lecture
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Update status functionality
        $('.update-status').on('click', function(e) {
            e.preventDefault();

            let url = $(this).data('url');
            let status = $(this).data('status');
            let id = $(this).data('id');
            let button = $(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        button.replaceWith(response.strHtml);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        });

        // Delete functionality
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            if (confirm('Are you sure you want to delete this lecture?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                    }
                });
            }
        });
    });
</script>
@endpush