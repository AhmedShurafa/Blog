@extends('layouts.admin')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Create user</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Users</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'admin.users.store','method' => 'POST' ,'files'=>true]) !!}

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', old('name'),['class'=>'form-control']) !!}
                        @error('name')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('username', 'Username') !!}
                        {!! Form::text('username', old('username'),['class'=>'form-control']) !!}
                        @error('username')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', old('email'),['class'=>'form-control']) !!}
                        @error('email')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('mobile', 'mobile') !!}
                        {!! Form::text('mobile', old('mobile'),['class'=>'form-control']) !!}
                        @error('mobile')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('password', 'Password') !!}
                        {!! Form::password('password',['class'=>'form-control']) !!}
                        @error('password')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status') ,['class'=>'form-control']) !!}
                    @error('status')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>

                <div class="col-6">
                    {!! Form::label('receive_email', 'Receive Email') !!}
                    {!! Form::select('receive_email', ['0' => 'Inactive' , '1' => 'Active'], old('receive_email') ,['class'=>'form-control']) !!}
                    @error('receive_email')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('bio', 'Bio') !!}
                        {!! Form::textarea('bio', old('bio'),['class'=>'form-control summernote']) !!}
                        @error('bio')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {!! Form::label('user_image', 'User Image') !!}
                    <br>
                    <div class="file-loading">
                        {!! Form::file('user_image', ['id'=>'user_image' , 'class' =>'file-input-overview']) !!}
                        <span>Image width should br 300px * 300px</span>
                        @error('user_image')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>



            <div class="form-group mt-3">
                {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@section('script')

    <script>
        $(function(
            $("#user_image").fileinput({
                theme:"fas",
                maxFileCount:5,
                allowedFileTypes: ['image'],    // allow only images
                showCancel:true,
                showRemove:true,
                showUpload:false,
                overwriteInitial:false,
            })
        });
    </script>
@endsection
