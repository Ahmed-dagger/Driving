@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ $pageTitle }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.ratings.store') }}" method="POST">
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
                <label for="rating">{{ trans('dashboard/admin.rating') }}</label>
                <input type="number" step="0.1" min="0" max="5" name="rating" id="rating" class="form-control"
                       value="{{ old('rating') }}" required>
            </div>

            <div class="form-group my-2">
                <label for="comment">{{ trans('dashboard/admin.comment') }}</label>
                <textarea name="comment" id="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
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
