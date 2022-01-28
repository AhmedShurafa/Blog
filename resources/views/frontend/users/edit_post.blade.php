@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('frontend/js/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')
    <!-- Start Blog Area -->
    <div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12">
                    <h3>Edit Post ({{ $post->title }})</h3>
                    {!! Form::model($post,['route' => ['users.post.update', $post->id], 'method' => 'put', 'files' => true]) !!}
                    @method('PUT')
                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title',$post->title),['class'=>'form-control']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description', old('description',$post->description),['class'=>'form-control summernote']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            {!! Form::label('category_id', 'Category') !!}
                            {!! Form::select('category_id', ['' => '----'] + $categories->toArray(), old('category_id',$post->category_id),['class'=>'form-control']) !!}
                            @error('category_id')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                        <div class="col-4">
                            {!! Form::label('comment_able', 'Comment Able') !!}
                            {!! Form::select('comment_able', ['0' => 'No' , '1' => 'yes'], old('comment_able',$post->comment_able) ,['class'=>'form-control']) !!}
                            @error('comment_able')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                        <div class="col-4">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status',$post->status) ,['class'=>'form-control']) !!}
                            @error('status')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="file-loading">
                        {!! Form::file('images[]', ['id'=>'post-images' , 'multiple' =>'multiple']) !!}
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::submit('Update Post', ['class'=>'btn btn-success']) !!}
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
                initialPreview:[
                    @if($post->media->count() > 0)
                        @foreach($post->media as $media)
                            "{{ asset('assets/posts/' . $media->file_name) }}",
                        @endforeach
                    @endif
                ],
                initialPreviewAsData:true,
                initialPreviewFileType:'image',

                initialPreviewConfig:[
                    @if($post->media->count() > 0)
                        @foreach($post->media as $media)
                            {caption: "{{ $media->file_name }}", size: {{ $media->file_size }}, width: "120px", url: "{{ route('users.post.media.destroy', [$media->id, '_token' => csrf_token()]) }}", key: "{{ $media->id }}"},
                        @endforeach
                    @endif
                ],
            })
        });
    </script>
@endsection
