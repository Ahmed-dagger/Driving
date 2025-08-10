@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ trans('dashboard/admin.edit_rating') }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.ratings.update', $rating->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group my-2">
                <label for="learner_id">{{ trans('dashboard/admin.learner') }}</label>
                <input type="number" name="learner_id" id="learner_id" class="form-control"
                    value="{{ old('learner_id', $rating->learner_id) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="instructor_id">{{ trans('dashboard/admin.instructor') }}</label>
                <input type="number" name="instructor_id" id="instructor_id" class="form-control"
                    value="{{ old('instructor_id', $rating->instructor_id) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="rate">{{ trans('dashboard/admin.rate') }}</label>
                <input type="number" step="0.1" name="rate" id="rate" min="0" max="5" class="form-control"
                    value="{{ old('rate', $rating->rate) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="comment">{{ trans('dashboard/admin.comment') }}</label>
                <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment', $rating->comment) }}</textarea>
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
