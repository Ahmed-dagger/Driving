@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ trans('dashboard/admin.edit_session') }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.sessions.update', $sessionItem->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group my-2">
                <label for="learner_id">{{ trans('dashboard/admin.learner') }}</label>
                <input type="number" name="learner_id" id="learner_id" class="form-control"
                    value="{{ old('learner_id', $sessionItem->learner_id) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="instructor_id">{{ trans('dashboard/admin.instructor') }}</label>
                <input type="number" name="instructor_id" id="instructor_id" class="form-control"
                    value="{{ old('instructor_id', $sessionItem->instructor_id) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="request_id">{{ trans('dashboard/admin.request') }}</label>
                <input type="number" name="request_id" id="request_id" class="form-control"
                    value="{{ old('request_id', $sessionItem->request_id) }}">
            </div>

            <div class="form-group my-2">
                <label for="date">{{ trans('dashboard/admin.date') }}</label>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ old('date', optional($sessionItem->date)->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="start_time">{{ trans('dashboard/admin.start_time') }}</label>
                <input type="time" name="start_time" id="start_time" class="form-control"
                    value="{{ old('start_time', optional($sessionItem->start_time)->format('H:i')) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="end_time">{{ trans('dashboard/admin.end_time') }}</label>
                <input type="time" name="end_time" id="end_time" class="form-control"
                    value="{{ old('end_time', optional($sessionItem->end_time)->format('H:i')) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="price">{{ trans('dashboard/admin.price') }}</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control"
                    value="{{ old('price', $sessionItem->price) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="status">{{ trans('dashboard/admin.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ old('status', $sessionItem->status) === 'pending' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.pending') }}
                    </option>
                    <option value="completed" {{ old('status', $sessionItem->status) === 'completed' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.completed') }}
                    </option>
                    <option value="rejected" {{ old('status', $sessionItem->status) === 'rejected' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.rejected') }}
                    </option>
                    <option value="canceled" {{ old('status', $sessionItem->status) === 'canceled' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.canceled') }}
                    </option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="notes">{{ trans('dashboard/admin.notes') }}</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $sessionItem->notes) }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="rejection_reason">{{ trans('dashboard/admin.rejection_reason') }}</label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="2">{{ old('rejection_reason', $sessionItem->rejection_reason) }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="completed_at">{{ trans('dashboard/admin.completed_at') }}</label>
                <input type="datetime-local" name="completed_at" id="completed_at" class="form-control"
                    value="{{ old('completed_at', optional($sessionItem->completed_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="form-group my-2">
                <label for="rate">{{ trans('dashboard/admin.rate') }}</label>
                <input type="number" step="0.1" name="rate" id="rate" min="0" max="5" class="form-control"
                    value="{{ old('rate', $sessionItem->rate) }}">
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
