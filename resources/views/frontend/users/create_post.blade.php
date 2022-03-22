@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('frontend/js/summernote/summernote-bs4.min.css') }}">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />

@endsection
@section('content')
    <!-- Start Blog Area -->
    <div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12">
                    <h3>Create Post</h3>
                    {!! Form::open(['rotue'=>'users.post.store','method' => 'post' ,'files'=>true]) !!}

                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title'),['class'=>'form-control']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description', old('description'),['class'=>'form-control summernote']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            {!! Form::label('category_id', 'Category') !!}
                            {!! Form::select('category_id', ['' => '----'] + $category->toArray(), old('comment_able'),['class'=>'form-control']) !!}
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

                    <div class="file-loading">
                        {!! Form::file('images[]', ['id'=>'post-images' , 'multiple' =>'multiple']) !!}
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
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

@section('script')
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ asset('frontend/js/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


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
                theme:"fa",
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
