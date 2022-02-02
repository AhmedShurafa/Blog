@extends('layouts.admin')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Edit user ({{ $user->username }})</h6>
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
            {!! Form::model($user,['route'=>['admin.users.update',$user->id],'method' => 'patch' ,'files'=>true]) !!}

            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', old('name',$user->name),['class'=>'form-control']) !!}
                        @error('name')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('username', 'Username') !!}
                        {!! Form::text('username', old('username',$user->username),['class'=>'form-control']) !!}
                        @error('username')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', old('email',$user->email),['class'=>'form-control']) !!}
                        @error('email')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        {!! Form::label('mobile', 'mobile') !!}
                        {!! Form::text('mobile', old('mobile',$user->mobile),['class'=>'form-control']) !!}
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
                    {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status',$user->status) ,['class'=>'form-control']) !!}
                    @error('status')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>

                <div class="col-6">
                    {!! Form::label('receive_email', 'Receive Email') !!}
                    {!! Form::select('receive_email', ['0' => 'Inactive' , '1' => 'Active'], old('receive_email',$user->receive_email) ,['class'=>'form-control']) !!}
                    @error('receive_email')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('bio', 'Bio') !!}
                        {!! Form::textarea('bio', old('bio',$user->bio),['class'=>'form-control']) !!}
                        @error('bio')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                @if($user->user_image !='')
                    <div class="col-12 text-center">
                        <div class="imgArea">
                            <img src="{{ asset('assets/users/'.$user->user_image) }}" alt="{{ $user->username }}">
                            <button class="btn btn-danger removeImage">Remove Image</button>
                        </div>
                    </div>
                @endif


                <div class="col-12">
                    {!! Form::label('User Image') !!}
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
                {!! Form::submit('Update post', ['class'=>'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function(){
            $('#user_image').fileinput({
                theme: "fas",
                maxFileCount: 1,
                allowedFileTypes: ['image'],
                showCancel: true,
                showRemove: false,
                showUpload: false,
                overwriteInitial: false,
            });

            $('.removeImage').click(function () {
                $.post('{{ route('admin.users.remove_image') }}'
                    , { user_id: '{{ $user->id }}'
                    , _token: '{{ csrf_token() }}' }
                    , function (data) {
                    if (data == 'true') {
                        window.location.href = window.location;
                    }
                })
            });

        });
    </script>
@endsection
