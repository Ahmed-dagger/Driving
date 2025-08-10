@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ $pageTitle }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.requests.store') }}" method="POST">
            @csrf

            <div class="form-group my-2">
                <label for="learner_id">{{ trans('dashboard/admin.learners') }}</label>
                <select name="learner_id" id="learner_id" class="form-control" required>
                    @foreach($learners as $learner)
                        <option value="{{ $learner->id }}">{{ $learner->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group my-2">
                <label for="instructor_id">{{ trans('dashboard/admin.instructors') }}</label>
                <select name="instructor_id" id="instructor_id" class="form-control">
                    <option value="">{{ trans('dashboard/admin.general_request') }}</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group my-2">
                <label for="package_id">{{ trans('dashboard/admin.packages') }}</label>
                <select name="package_id" id="package_id" class="form-control" required>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group my-2">
                <label for="start_date">{{ trans('dashboard/admin.start_date') }}</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>

            <div class="form-group my-2">
                <label for="location_city">{{ trans('dashboard/admin.city') }}</label>
                <input type="text" name="location_city" id="location_city" class="form-control">
            </div>

            <div class="form-group my-2">
                <label for="location_area">{{ trans('dashboard/admin.area') }}</label>
                <input type="text" name="location_area" id="location_area" class="form-control">
            </div>

            <div class="form-group my-2">
                <label for="has_learner_car">{{ trans('dashboard/admin.has_learner_car') }}</label>
                <select name="has_learner_car" id="has_learner_car" class="form-control" required>
                    <option value="1">{{ trans('dashboard/admin.yes') }}</option>
                    <option value="0">{{ trans('dashboard/admin.no') }}</option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="requires_transport">{{ trans('dashboard/admin.requires_transport') }}</label>
                <select name="requires_transport" id="requires_transport" class="form-control" required>
                    <option value="1">{{ trans('dashboard/admin.yes') }}</option>
                    <option value="0">{{ trans('dashboard/admin.no') }}</option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="total_price">{{ trans('dashboard/admin.total_price') }}</label>
                <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" required>
            </div>

            <div class="form-group my-2">
                <label for="type">{{ trans('dashboard/admin.type') }}</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="general">{{ trans('dashboard/admin.general_request') }}</option>
                    <option value="private">{{ trans('dashboard/admin.private_request') }}</option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="status">{{ trans('dashboard/admin.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending">{{ trans('dashboard/admin.requests.pending') }}</option>
                    <option value="accepted">{{ trans('dashboard/admin.requests.accepted') }}</option>
                    <option value="rejected">{{ trans('dashboard/admin.requests.rejected') }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary my-4">{{ trans('dashboard/general.save') }}</button>
        </form>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
    </div>
@endsection

@push('js')
@endpush
