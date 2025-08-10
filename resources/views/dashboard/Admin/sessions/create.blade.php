@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ $pageTitle }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.sessions.store') }}" method="POST">
            @csrf

            <div class="form-group my-2">
                <label for="learner_id">{{ trans('dashboard/admin.learners') }}</label>
                <select name="learner_id" id="learner_id" class="form-control" required>
                    @foreach($learners as $learner)
                        <option value="{{ $learner->id }}" {{ old('learner_id') == $learner->id ? 'selected' : '' }}>
                            {{ $learner->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group my-2">
                <label for="instructor_id">{{ trans('dashboard/admin.instructors') }}</label>
                <select name="instructor_id" id="instructor_id" class="form-control" required>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group my-2">
                <label for="request_id">{{ trans('dashboard/admin.request') }}</label>
                <input type="number" name="request_id" id="request_id" class="form-control"
                       value="{{ old('request_id') }}">
            </div>

            <div class="form-group my-2">
                <label for="date">{{ trans('dashboard/admin.date') }}</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
            </div>

            <div class="form-group my-2">
                <label for="start_time">{{ trans('dashboard/admin.start_time') }}</label>
                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
            </div>

            <div class="form-group my-2">
                <label for="end_time">{{ trans('dashboard/admin.end_time') }}</label>
                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
            </div>

            <div class="form-group my-2">
                <label for="price">{{ trans('dashboard/admin.price') }}</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control"
                       value="{{ old('price') }}" required>
            </div>

            <div class="form-group my-2">
                <label for="status">{{ trans('dashboard/admin.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.pending') }}
                    </option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.completed') }}
                    </option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.rejected') }}
                    </option>
                    <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>
                        {{ trans('dashboard/admin.canceled') }}
                    </option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="notes">{{ trans('dashboard/admin.notes') }}</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="rejection_reason">{{ trans('dashboard/admin.rejection_reason') }}</label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="2">{{ old('rejection_reason') }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="completed_at">{{ trans('dashboard/admin.completed_at') }}</label>
                <input type="datetime-local" name="completed_at" id="completed_at" class="form-control"
                       value="{{ old('completed_at') }}">
            </div>

            <div class="form-group my-2">
                <label for="rate">{{ trans('dashboard/admin.rate') }}</label>
                <input type="number" step="0.1" min="0" max="5" name="rate" id="rate" class="form-control"
                       value="{{ old('rate') }}">
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
