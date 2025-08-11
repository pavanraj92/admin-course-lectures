@extends('admin::admin.layouts.master')

@section('title', 'Lecture Management')

@section('page-title', 'Lecture Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.lectures.index') }}">Lecture Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Lecture Details</li>
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
                                <a href="{{ route('admin.lectures.index') }}" class="btn btn-secondary ml-2">Back</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Lecture Information</h5>
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
                                                    <label class="font-weight-bold">Section:</label>
                                                    <p>
                                                        @if ($lecture->section)
                                                            <span
                                                                class="badge badge-info">{{ $lecture->section->title }}</span>
                                                        @else
                                                            <span class="text-muted">No Section</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            @if ($lecture->short_description)
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Short Description:</label>
                                                        <p>{!! $lecture->short_description !!}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($lecture->description)
                                                <div class="col-md-12">
                                                    <div class="form-group inline">
                                                        <label class="font-weight-bold">Description:</label>
                                                        <p>{!! $lecture->description !!}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($lecture->hasVideo() || $lecture->hasAttachment())
                                    <div class="card mt-3">
                                        <div class="card-header bg-primary">
                                            <h5 class="mb-0 text-white">Media Files</h5>
                                        </div>
                                        <div class="card-body">
                                            @if ($lecture->hasVideo())
                                                <div>
                                                    <strong>Video:</strong>
                                                    <a href="{{ $lecture->video_url }}" target="_blank"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="mdi mdi-play"></i> Watch Video
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($lecture->hasAttachment())
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Attachment:</label>
                                                    <div>
                                                        <a href="{{ $lecture->attachment_url }}" target="_blank"
                                                            class="btn btn-outline-success">
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
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Lecture Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li class="mt-2"><strong>Type:</strong> <span
                                                    class="badge {{ $lecture->type_badge_class }}">
                                                    {{ ucfirst($lecture->type) }}
                                                </span></li>
                                            <li class="mt-2"><strong>Duration:</strong>
                                                {{ $lecture->formatted_duration }}</li>
                                            <li class="mt-2"><strong>Order:</strong> {{ $lecture->order }}</li>
                                            <li class="mt-2"><strong>Preview Access:</strong>
                                                <span
                                                    class="badge {{ $lecture->is_preview ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $lecture->is_preview ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </li>
                                            <li class="mt-2"><strong>Highlighted:</strong>
                                                <span
                                                    class="badge {{ $lecture->is_highlight ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $lecture->is_highlight ? 'Yes' : 'No' }}
                                                </span>
                                            </li>
                                            <li class="mt-2"><strong>Status:</strong>
                                                <span class="badge {{ $lecture->status_badge_class }}">
                                                    {{ ucfirst($lecture->status) }}
                                                </span>
                                            </li>
                                            <li class="mt-2"><strong>Created:</strong>
                                                {{ $lecture->created_at->format('d M, Y') }}</li>
                                            <li class="mt-2"><strong>Updated:</strong>
                                                {{ $lecture->updated_at->format('d M, Y') }}</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.lectures.edit', $lecture) }}"
                                                class="btn btn-warning mb-2">
                                                <i class="fa fa-edit"></i> Edit Lecture
                                            </a>

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
    <script>
        $(document).ready(function() {
            // Delete functionality
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                let url = $(this).data('url');

                if (confirm(
                        'Are you sure you want to delete this lecture? This action cannot be undone.')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.href = '{{ route('admin.lectures.index') }}';
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
