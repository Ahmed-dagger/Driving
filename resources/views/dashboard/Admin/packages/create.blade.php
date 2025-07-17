@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ $pageTitle }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')

    <div class="card">
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf

            <div class="form-group my-2">
                <label for="name">{{ trans('dashboard/admin.name') }}</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group my-2">
                <label for="description">{{ trans('dashboard/admin.description') }}</label>
                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group my-2">
                <label for="days_count">{{ trans('dashboard/admin.days_count') }}</label>
                <input type="number" name="days_count" id="days_count" class="form-control" required>
            </div>

            <div class="form-group my-2">
                <label for="hours_count">{{ trans('dashboard/admin.hours_count') }}</label>
                <input type="number" name="hours_count" id="hours_count" class="form-control" required>
            </div>

            <div class="form-group my-2">
                <label for="price">{{ trans('dashboard/admin.price') }}</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
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
