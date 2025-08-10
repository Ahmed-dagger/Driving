@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ trans('dashboard/admin.edit_request') }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.requests.update', $requestItem->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group my-2">
                <label for="learner_id">{{ trans('dashboard/admin.learner') }}</label>
                <input type="number" name="learner_id" id="learner_id" class="form-control" value="{{ old('learner_id', $requestItem->learner_id) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="instructor_id">{{ trans('dashboard/admin.instructor') }}</label>
                <input type="number" name="instructor_id" id="instructor_id" class="form-control" value="{{ old('instructor_id', $requestItem->instructor_id) }}">
            </div>

            <div class="form-group my-2">
                <label for="package_id">{{ trans('dashboard/admin.package') }}</label>
                <input type="number" name="package_id" id="package_id" class="form-control" value="{{ old('package_id', $requestItem->package_id) }}">
            </div>

            <div class="form-group my-2">
                <label for="start_date">{{ trans('dashboard/admin.start_date') }}</label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', optional($requestItem->start_date)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="form-group my-2">
                <label for="location_city">{{ trans('dashboard/admin.city') }}</label>
                <input type="text" name="location_city" id="location_city" class="form-control" value="{{ old('location_city', $requestItem->location_city) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="location_area">{{ trans('dashboard/admin.area') }}</label>
                <input type="text" name="location_area" id="location_area" class="form-control" value="{{ old('location_area', $requestItem->location_area) }}" required>
            </div>

            <div class="form-check my-2">
                <input type="checkbox" class="form-check-input" id="has_learner_car" name="has_learner_car" value="1" {{ old('has_learner_car', $requestItem->has_learner_car) ? 'checked' : '' }}>
                <label class="form-check-label" for="has_learner_car">{{ trans('dashboard/admin.has_learner_car') }}</label>
            </div>

            <div class="form-check my-2">
                <input type="checkbox" class="form-check-input" id="requires_transport" name="requires_transport" value="1" {{ old('requires_transport', $requestItem->requires_transport) ? 'checked' : '' }}>
                <label class="form-check-label" for="requires_transport">{{ trans('dashboard/admin.requires_transport') }}</label>
            </div>

            <div class="form-group my-2">
                <label for="total_price">{{ trans('dashboard/admin.total_price') }}</label>
                <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" value="{{ old('total_price', $requestItem->total_price) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="type">{{ trans('dashboard/admin.type') }}</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="general" {{ old('type', $requestItem->type) === 'general' ? 'selected' : '' }}>{{ trans('dashboard/admin.general') }}</option>
                    <option value="private" {{ old('type', $requestItem->type) === 'private' ? 'selected' : '' }}>{{ trans('dashboard/admin.private') }}</option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="status">{{ trans('dashboard/admin.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ old('status', $requestItem->status) === 'pending' ? 'selected' : '' }}>{{ trans('dashboard/admin.pending') }}</option>
                    <option value="accepted" {{ old('status', $requestItem->status) === 'accepted' ? 'selected' : '' }}>{{ trans('dashboard/admin.accepted') }}</option>
                    <option value="rejected" {{ old('status', $requestItem->status) === 'rejected' ? 'selected' : '' }}>{{ trans('dashboard/admin.rejected') }}</option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="notes">{{ trans('dashboard/admin.notes') }}</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $requestItem->notes) }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="rejection_reason">{{ trans('dashboard/admin.rejection_reason') }}</label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="2">{{ old('rejection_reason', $requestItem->rejection_reason) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary my-4">{{ trans('dashboard/general.update') }}</button>
        </form>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
    </div>
@endsection

@push('js')
@endpush
