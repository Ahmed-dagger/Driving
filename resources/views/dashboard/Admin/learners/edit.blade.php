@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ trans('dashboard/admin.edit_learner') }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.learners.update', $learner->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group my-2">
                <label for="name">{{ trans('dashboard/admin.name') }}</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $learner->name) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="email">{{ trans('dashboard/admin.email') }}</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $learner->email) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="password">{{ trans('dashboard/admin.password') }}</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ trans('dashboard/admin.leave_blank_to_keep') }}">
            </div>

            <div class="form-group my-2">
                <label for="phone">{{ trans('dashboard/admin.phone') }}</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $learner->phone) }}">
            </div>

            <div class="form-group my-2">
                <label for="status">{{ trans('dashboard/general.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" {{ old('status', $learner->status) === 'active' ? 'selected' : '' }}>
                        {{ trans('dashboard/general.active') }}
                    </option>
                    <option value="inactive" {{ old('status', $learner->status) === 'inactive' ? 'selected' : '' }}>
                        {{ trans('dashboard/general.inactive') }}
                    </option>
                </select>
            </div>

            <div class="form-group my-2">
                <label for="bio">{{ trans('dashboard/admin.bio') }}</label>
                <textarea name="bio" id="bio" class="form-control" rows="3">{{ old('bio', $learner->bio) }}</textarea>
            </div>

            <input type="hidden" name="user_type" value="learner">

            <button type="submit" class="btn btn-primary my-4">{{ trans('dashboard/general.update') }}</button>
        </form>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
    </div>
@endsection

@push('js')
@endpush
