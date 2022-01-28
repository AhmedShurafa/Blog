@extends('layouts.app')
@section('content')

<section class="my_account_area pt--80 pb--55 bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-md-3">
                <div class="my__account__wrapper">
                    <h3 class="account__title">Reset Password</h3>
                    {!! Form::open(['route' => 'password.email' , 'method' => 'POST']) !!}

                        <div class="account__form">
                            <div class="input__box">
                                {!! Form::label('email', 'E-Mail Address *') !!}
                                {!! Form::email('email', old('email')) !!}
                                @error('email')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form__btn">
                                {!! Form::button('Send Password Reset Link', ['type'=>'submit']) !!}
                                <a class="forget_pass" href="{{ route('frontend.login') }}">Login?</a>

                            </div>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
