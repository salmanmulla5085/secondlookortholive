@extends('layouts.app')

@section('head')
@endsection

@section('content')
    @include('layouts.navbars.topnav', ['title' => 'Edit Plan'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Plan</h6>
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

                    <form action="{{ route('admin.updatePlan', ['id' => $planData->id]) }}" method="POST">
                        @csrf
                        @method('POST')

                       


                    <label for='title'>{{ __('Plan type') }}</label>
                    <input type='text' class='form-control' id='plan_type' name='plan_type' value="{{ $planData->plan_type }}" required>

                    <label for='title'>{{ __('Plan duration') }}</label>
                    <input type='text' class='form-control' id='plan_duration' name='plan_duration' value="{{ $planData->plan_duration }}" >
 
                    <label for='title'>{{ __('Plan amount') }}</label>
                    <input type='text' class='form-control' id='plan_amount' name='plan_amount' value="{{ $planData->plan_amount }}" required>

                    <label for='title'>{{ __('Plan details') }}</label>
                    <input type='text' class='form-control' id='plan_detail' name='plan_detail' value="{{ $planData->plan_detail }}" required>

                        <br>
                        <input type="submit" value="Update" class="btn btn-success" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
