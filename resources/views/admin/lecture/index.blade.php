@extends('admin::admin.layouts.master')

@section('title', 'Lectures Management')

@section('page-title', 'Lecture Manager')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Lecture Manager</li>
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
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.lectures.index') }}" class="btn btn-secondary mt-4">Reset</a>
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
                    @admincan('lectures_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.lectures.create', ['course' => request('course')]) }}" class="btn btn-primary mb-3">
                            Create New Lecture
                        </a>
                    </div>
                    @endadmincan

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S. No.</th>
                                    <th scope="col">@sortablelink('title', 'Title', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">Course/Section</th>
                                    <th scope="col">@sortablelink('type', 'Type', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($lectures) && $lectures->count() > 0)
                                @php
                                $i = ($lectures->currentPage() - 1) * $lectures->perPage() + 1;
                                @endphp
                                @foreach ($lectures as $lecture)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td>
                                        <strong>{{ $lecture->title }}</strong>
                                        @if ($lecture->short_description)
                                        <br><small
                                            class="text-muted">{!! Str::limit($lecture->short_description, 50) !!}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $lecture?->course?->title ?? 'No Course Assigned' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="mdi mdi-folder"></i> {{ $lecture?->section?->title ?? 'No Section Assigned' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($lecture->type) }}</span>
                                    </td>
                                    <td>
                                        @php
                                        $color = config('course.constants.statusBadge.' . $lecture->status, 'badge-secondary');
                                        @endphp
                                        <p><span class="badge {{ $color }}">{{ ucfirst($lecture->status) }}</span></p>
                                    </td>
                                    <td>
                                        {{ $lecture->created_at ? $lecture->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s') : '—' }}
                                    </td>
                                    <td>
                                        @admincan('lectures_manager_view')
                                        <a href="{{ route('admin.lectures.show', $lecture) }}"
                                            data-toggle="tooltip" data-placement="top"
                                            title="View this record" class="btn btn-warning btn-sm mr-1"><i
                                                class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('lectures_manager_edit')
                                        <a href="{{ route('admin.lectures.edit', $lecture) }}"
                                            data-toggle="tooltip" data-placement="top"
                                            title="Edit this record" class="btn btn-success btn-sm mr-1"><i
                                                class="mdi mdi-pencil"></i></a>
                                        @endadmincan

                                        @admincan('lectures_manager_delete')
                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                            data-placement="top" title="Delete this record"
                                            data-url="{{ route('admin.lectures.destroy', $lecture) }}"
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
                                    <td colspan="7" class="text-center">No records found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        @if ($lectures->count() > 0)
                        {{ $lectures->links('admin::pagination.custom-admin-pagination') }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Lecture Content -->
</div>
<!-- End Container fluid  -->
@endsection

@push('scripts')
@endpush