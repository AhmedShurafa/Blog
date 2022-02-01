@extends('layouts.admin')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Create post</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Posts</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'admin.posts.store','method' => 'POST' ,'files'=>true]) !!}

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title'),['class'=>'form-control']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description', old('description'),['class'=>'form-control summernote']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    {!! Form::label('category_id', 'Category') !!}
                    {!! Form::select('category_id', ['' => '----'] + $categories->toArray(), old('category_id'),['class'=>'form-control']) !!}
                    @error('category_id')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
                <div class="col-4">
                    {!! Form::label('comment_able', 'Comment Able') !!}
                    {!! Form::select('comment_able', ['0' => 'No' , '1' => 'yes'], old('comment_able') ,['class'=>'form-control']) !!}
                    @error('comment_able')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
                <div class="col-4">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status') ,['class'=>'form-control']) !!}
                    @error('status')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {!! Form::label('Sliders', 'Images') !!}
                    <br>
                    <div class="file-loading">
                        {!! Form::file('images[]', ['id'=>'post-images' , 'multiple' =>'multiple']) !!}
                        @error('images')
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
        $(function(){
            $('.summernote').summernote({
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            $("#post-images").fileinput({
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
