@extends('admin::admin.layouts.master')

@section('title', 'View Lecture - ' . $lecture->title)

@section('page-title', 'Lecture Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.lectures.index') }}">Lectures</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $lecture->title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">{{ $lecture->title }}</h4>
                        <div>
                            <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.lectures.index') }}" class="btn btn-secondary ml-2">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Lecture Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Title:</label>
                                                <p>{{ $lecture->title }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Slug:</label>
                                                <p>{{ $lecture->slug }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Section:</label>
                                                <p>
                                                    @if($lecture->section)
                                                    <span class="badge badge-info">{{ $lecture->section->title }}</span>
                                                    @else
                                                    <span class="text-muted">No Section</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Type:</label>
                                                <p>
                                                    <span class="badge {{ $lecture->type_badge_class }}">
                                                        {{ ucfirst($lecture->type) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($lecture->short_description)
                                    <div class="form-group">
                                        <label class="font-weight-bold">Short Description:</label>
                                        <p>{{ $lecture->short_description }}</p>
                                    </div>
                                    @endif

                                    @if($lecture->description)
                                    <div class="form-group">
                                        <label class="font-weight-bold">Description:</label>
                                        <p>{!! $lecture->description !!}</p>
                                    </div>
                                    @endif

                                    @if($lecture->content)
                                    <div class="form-group">
                                        <label class="font-weight-bold">Content:</label>
                                        <div class="border p-3 bg-light">
                                            {!! nl2br(e($lecture->content)) !!}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($lecture->hasVideo() || $lecture->hasAttachment())
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Media Files</h5>
                                </div>
                                <div class="card-body">
                                    @if($lecture->hasVideo())
                                    <div class="form-group">
                                        <label class="font-weight-bold">Video:</label>
                                        <div>
                                            <video controls style="width: 100%; max-width: 600px;">
                                                <source src="{{ $lecture->video_url }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <small class="text-muted">
                                            <a href="{{ $lecture->video_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fa fa-external-link"></i> Open in New Tab
                                            </a>
                                        </small>
                                    </div>
                                    @endif

                                    @if($lecture->hasAttachment())
                                    <div class="form-group">
                                        <label class="font-weight-bold">Attachment:</label>
                                        <div>
                                            <a href="{{ $lecture->attachment_url }}" target="_blank" class="btn btn-outline-success">
                                                <i class="fa fa-download"></i> Download Attachment
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Lecture Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Status:</label>
                                        <p>
                                            <span class="badge {{ $lecture->status_badge_class }}">
                                                {{ ucfirst($lecture->status) }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Duration:</label>
                                        <p>{{ $lecture->formatted_duration }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Order:</label>
                                        <p>{{ $lecture->order }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Preview Access:</label>
                                        <p>
                                            <span class="badge {{ $lecture->is_preview ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $lecture->is_preview ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Highlighted:</label>
                                        <p>
                                            <span class="badge {{ $lecture->is_highlight ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $lecture->is_highlight ? 'Yes' : 'No' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Created:</label>
                                        <p>{{ $lecture->created_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Last Updated:</label>
                                        <p>{{ $lecture->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-warning mb-2">
                                            <i class="fa fa-edit"></i> Edit Lecture
                                        </a>

                                        @if($lecture->section)
                                        <a href="#" class="btn btn-info mb-2">
                                            <i class="fa fa-list"></i> View Section
                                        </a>
                                        @endif

                                        <button type="button" class="btn btn-danger delete-btn"
                                            data-url="{{ route('admin.lectures.destroy', $lecture) }}">
                                            <i class="fa fa-trash"></i> Delete Lecture
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Delete functionality
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();

            let url = $(this).data('url');

            if (confirm('Are you sure you want to delete this lecture? This action cannot be undone.')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '{{ route("admin.lectures.index") }}';
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