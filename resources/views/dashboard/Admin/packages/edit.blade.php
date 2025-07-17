@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ trans('dashboard/admin.edit_package') }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group my-2">
                <label for="name">{{ trans('dashboard/admin.name') }}</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $package->name) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="description">{{ trans('dashboard/admin.description') }}</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="form-group my-2">
                <label for="days_count">{{ trans('dashboard/admin.days_count') }}</label>
                <input type="number" name="days_count" id="days_count" class="form-control" value="{{ old('days_count', $package->days_count) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="hours_count">{{ trans('dashboard/admin.hours_count') }}</label>
                <input type="number" name="hours_count" id="hours_count" class="form-control" value="{{ old('hours_count', $package->hours_count) }}" required>
            </div>

            <div class="form-group my-2">
                <label for="price">{{ trans('dashboard/admin.price') }}</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $package->price) }}" required>
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
