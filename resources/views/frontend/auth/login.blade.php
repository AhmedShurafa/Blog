@extends('layouts.app')

@section('content')

	<!-- Start My Account Area -->
    <section class="my_account_area pt--80 pb--55 bg--white">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-md-3">
                    <div class="my__account__wrapper">
                        <h3 class="account__title">Login</h3>
                        {!! Form::open(['route' => 'frontend.login' , 'method' => 'POST']) !!}

                            <div class="account__form">
                                <div class="input__box">
                                    {!! Form::label('username', 'Username *') !!}
                                    {!! Form::text('username', old('username')) !!}
                                    @error('username')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input__box">
                                    {!! Form::label('password', 'Password *') !!}
                                    {!! Form::password('password') !!}
                                    @error('password')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                                <div class="form__btn">
                                    {!! Form::button('Login', ['type'=>'submit']) !!}

                                    <label class="label-for-checkbox">
                                        <input class="input-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span>Remember me</span>
                                    </label>
                                </div>
                                <a class="forget_pass" href="{{ route('password.request') }}">Lost your password?</a>
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End My Account Area -->
@endsection
