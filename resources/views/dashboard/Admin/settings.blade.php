@extends('dashboard.layouts.master')

@push('css')
    <style>
        /* From Uiverse.io by Na3ar-17 */
        .radio-input {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .radio-input * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        .radio-input label {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0px 20px;
            width: 220px;
            cursor: pointer;
            height: 50px;
            position: relative;
        }

        .radio-input label::before {
            position: absolute;
            content: "";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 220px;
            height: 45px;
            z-index: -1;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-radius: 10px;
            border: 2px solid transparent;
        }

        .radio-input label:hover::before {
            transition: all 0.2s ease;
            background-color: #2a2e3c;
        }

        .radio-input .label:has(input:checked)::before {
            background-color: #2d3750;
            border-color: #435dd8;
            height: 50px;
        }

        .radio-input .label .text {
            color: #000000;
        }

        .radio-input .label input[type="radio"] {
            background-color: #8787a1;
            appearance: none;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .radio-input .label input[type="radio"]:checked {
            background-color: #435dd8;
            -webkit-animation: puls 0.7s forwards;
            animation: pulse 0.7s forwards;
        }

        .radio-input .label input[type="radio"]:before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            transition: all 0.1s cubic-bezier(0.165, 0.84, 0.44, 1);
            background-color: #fff;
            transform: scale(0);
        }

        .radio-input .label input[type="radio"]:checked::before {
            transform: scale(1);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(138, 124, 124, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(115, 98, 98, 0);
            }
        }
    </style>
@endpush('css')


@section('pageTitle')
    {{ $pageTitle }}
@endsection

@section('content')
    @include('dashboard.layouts.common._partial.messages')


    <div class="card">
        <form class="form" method="POST" action="{{ route('admin.settings.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group row" class="my-2">
                    <div class="col-lg-6">
                        <label class="mb-2">{{ __('dashboard/forms.email') }}</label>
                        <input name="email" type="text" class="form-control form-control-solid" placeholder="Email" value="{{ old('email', setting('email')) }}"/>
                        <span class="form-text text-muted">Enter the Email appear in header</span>
                    </div>
                    <div class="col-lg-6">

                        <label for="exampleTextarea" class="mb-2">{{ __('dashboard/forms.phone') }}</label>
                        <input name="phone" type="text" class="form-control form-control-solid" placeholder="Phone" value="{{ old('phone', setting('phone')) }}"/>
                        <span class="form-text text-muted">enter the Phone appear in header</span>
                    </div>
                </div>
                <div class="form-group row" class="my-2">
                    <div class="col-lg-6">
                        <label class="mb-2">{{ __('dashboard/forms.location') }}</label>
                        <input name="location" type="text" class="form-control form-control-solid" placeholder="location" value="{{ old('location', setting('location')) }}"/>
                        <span class="form-text text-muted">Enter the location appear in contact</span>
                    </div>
                    <div class="col-lg-6">

                        <label for="exampleTextarea" class="mb-2">{{ __('dashboard/forms.hotline') }}</label>
                        <input name="hotline" type="text" class="form-control form-control-solid" placeholder="hotline" value="{{ old('hotline', setting('hotline')) }}"/>
                        <span class="form-text text-muted">enter the hotline appear in header</span>
                    </div>
                </div>

                <div class="form-group row" class="my-2">
                    <div class="col-lg-6">
                        <label class="mb-2">{{ __('dashboard/forms.Facebook') }}</label>
                        <input name="Facebook" type="text" class="form-control form-control-solid" placeholder="Facebook Link" value="{{ old('Facebook', setting('Facebook')) }}"/>
                        <span class="form-text text-muted">Enter the Facebook link</span>
                    </div>
                    <div class="col-lg-6">

                        <label for="exampleTextarea" class="mb-2">{{ __('dashboard/forms.Instagram') }}</label>
                        <input name="Instagram" type="text" class="form-control form-control-solid" placeholder="Instagram Link" value="{{ old('Instagram', setting('Instagram')) }}"/>
                        <span class="form-text text-muted">Enter the Instagram link</span>
                    </div>
                </div>

                <div class="form-group row" class="my-2">
                    <div class="col-lg-6">
                        <label class="mb-2">{{ __('dashboard/forms.LinkedIn') }}</label>
                        <input name="LinkedIn" type="text" class="form-control form-control-solid" placeholder="LinkedIn Link" value="{{ old('LinkedIn', setting('LinkedIn')) }}"/>
                        <span class="form-text text-muted">Enter the LinkedIn link</span>
                    </div>
                    <div class="col-lg-6">

                        <label for="exampleTextarea" class="mb-2">{{ __('dashboard/forms.Twitter') }}</label>
                        <input name="Twitter" type="text" class="form-control form-control-solid" placeholder="Twitter Link" value="{{ old('Twitter', setting('Twitter')) }}"/>
                        <span class="form-text text-muted">Enter the Twitter link</span>
                    </div>
                </div>

            </div>
            <div class="">
                <div class="row my-4 mx-3">
                    <div class="col-lg-10 text-lg-right">
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endpush
