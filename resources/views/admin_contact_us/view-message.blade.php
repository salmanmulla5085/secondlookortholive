@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'View Message'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>View Message</h6>
                </div>
                <div class="card-body px-5 pt-0 pb-2">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <label for='title'>{{ __('First Name') }}</label>
                    <input type='text' class='form-control' id='first_name' name='first_name' value="{{ $messageData->first_name }}" disabled>

                    <label for='title'>{{ __('Last Name') }}</label>
                    <input type='text' class='form-control' id='last_name' name='last_name' value="{{ $messageData->last_name }}" disabled>

                    <label for='title'>{{ __('Email') }}</label>
                    <input type='text' class='form-control' id='email' name='email' value="{{ $messageData->email }}" disabled>

                    <label for='title'>{{ __('Phone') }}</label>
                    <input type='text' class='form-control' id='phone' name='phone' value="{{ $messageData->phone }}" disabled>

                    <label for="about">{{__('Message')}}</label>
                    <textarea class='form-control' id='message' name='message' placeholder="Message" disabled>{{ $messageData->message }}</textarea>

                    <br>
                    <a href="{{ url('admin/contact_us') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
