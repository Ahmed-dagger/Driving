@extends('dashboard.layouts.master')

@section('css')
@endsection

@section('pageTitle')
    {{ $pageTitle }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')


    <div class="card">
    <form action="{{ route('admin.codes.store') }}" method="POST">
        @csrf
        <div>
            <label for="number_of_codes">Number of Codes to Generate:</label>
            <input type="number" name="number_of_codes" id="number_of_codes" min="1" required>
        </div>
        <button type="submit" class=" btn btn-primary my-5">Generate Codes</button>
    </form>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    </div>

   
@endsection

@push('js')
    




@endpush
