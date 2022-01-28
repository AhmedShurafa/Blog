@extends('layouts.app')
@section('content')
	<!-- Start My Account Area -->
    <section class="my_account_area pt--80 pb--55 bg--white">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-md-3">
                    <div class="my__account__wrapper">
                        <h3 class="account__title">Register</h3>
                        {!! Form::open(['route' => 'frontend.register' , 'method' => 'POST' , 'files'=>true]) !!}

                            <div class="account__form">
                                <div class="input__box">
                                    {!! Form::label('name', 'Name *') !!}
                                    {!! Form::text('name', old('name')) !!}
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input__box">
                                    {!! Form::label('username', 'Username *') !!}
                                    {!! Form::text('username', old('username')) !!}
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input__box">
                                    {!! Form::label('email', 'Email *') !!}
                                    {!! Form::email('email', old('email')) !!}
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input__box">
                                    {!! Form::label('mobile', 'Mobile *') !!}
                                    {!! Form::text('mobile', old('mobile')) !!}
                                    @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="input__box">
                                    {!! Form::label('password', 'Password *') !!}
                                    {!! Form::password('password') !!}
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="input__box">
                                    {!! Form::label('password_confirmation', 'Re-Password *') !!}
                                    {!! Form::password('password_confirmation') !!}
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="input__box">
                                    {!! Form::label('user_image', 'User image') !!}
                                    {!! Form::file('user_image',['class'=>'custom-file']) !!}
                                    @error('user_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="form__btn">
                                    {!! Form::button('Create Account', ['type'=>'submit']) !!}
                                </div>
                                <a class="forget_pass" href="{{ route('frontend.login') }}">Login?</a>
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End My Account Area -->
@endsection

