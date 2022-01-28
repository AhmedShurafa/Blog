@extends('layouts.app')

@section('content')
    <!-- Start Blog Area -->
    <div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12">
                    <h3>Edit Information : </h3>
                    {!! Form::open(['route' =>'users.update_info','id'=>'user_info','name'=>'user_info', 'method' => 'post','files'=>true]) !!}

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('name', 'Name') !!}
                                {!! Form::text('name', old('name',auth()->user()->name),['class'=>'form-control']) !!}
                                @error('name')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('email', 'Email') !!}
                                {!! Form::email('email', old('email',auth()->user()->email),['class'=>'form-control']) !!}
                                {{-- <label>Email</label>
                                <input type="email" value="{{ old('email',auth()->user()->email) }}" class="form-control"> --}}

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('mobile', 'mobile') !!}
                                {!! Form::text('mobile', old('mobile',auth()->user()->mobile),['class'=>'form-control']) !!}
                                @error('mobile')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            {!! Form::label('receive_email', 'Receive Email') !!}
                            {!! Form::select('receive_email', ['0' => 'No' , '1' => 'Yes'], old('receive_email',auth()->user()->receive_email) ,['class'=>'form-control']) !!}
                            @error('receive_email')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('bio', 'bio') !!}
                            {!! Form::textarea('bio', old('bio',auth()->user()->bio) ,['class'=>'form-control']) !!}
                            @error('bio')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row my-3">
                        {!! Form::label('user_image', 'User image') !!}
                        @if(auth()->user()->user_image !='')
                            <div class="col-12">
                                <img src="{{ asset('assets/users/'. auth()->user()->user_image) }}"
                                     width="150" class="img-fluid" alt="{{ auth()->user()->name }}">
                            </div>
                        @endif
                        <div class="col-12">
                            {!! Form::file('user_image', ['class'=>'custom-file']) !!}
                            @error('user_image')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::submit('Update Information', ['name'=>'update_information','class'=>'btn btn-success']) !!}
                    </div>

                    {!! Form::close() !!}



                    <hr class="my-3">

                    <h3>Update Password : </h3>

                    {!! Form::open(['route' =>'users.update_password','id'=>'update_password', 'name'=>'update_password', 'method' => 'post']) !!}

                    <div class="row my-3">
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('current_password', 'Current Password') !!}
                                {!! Form::password('current_password', ['class'=>'form-control']) !!}


                                @error('current_password')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('password', 'New Password') !!}
                                {!! Form::password('password', ['class'=>'form-control']) !!}
                                @error('password')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {!! Form::label('password_confirmation', 'Password confirmation') !!}
                                {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <div class="form-group mt-3">
                        {!! Form::submit('Update Password', ['name'=>'update_password','class'=>'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                    @include('partial.users.sidebar')
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog Area -->
@endsection
