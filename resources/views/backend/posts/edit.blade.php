@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset('backend/vendor/select2/css/select2.min.css') }}">
@endsection

@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Edit post ({{ $post->title }})</h6>
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
            {!! Form::model($post,['route'=>['admin.posts.update',$post->id],'method' => 'patch' ,'files'=>true]) !!}

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title',$post->title),['class'=>'form-control']) !!}
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
                        {!! Form::textarea('description', old('description',$post->description),['class'=>'form-control summernote']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('tags', 'Tags') !!}
                <div class="my-2">
                    <button type="button" class="btn btn-primary btn-xs" id="select_btn_tag">Select All</button>
                    <button type="button" class="btn btn-primary btn-xs" id="deselect_btn_tag">Deselcet All</button>
                </div>
                {!! Form::select('tags[]', $tags->toArray(), old('tags' , $post->tags),['id'=>'select_all_tags', 'class'=>'select form-control' , 'multiple'=>'multiple']) !!}
                @error('tags')
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
                {!! Form::submit('Update post', ['class'=>'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@section('script')
<script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>

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

            $('.select').select2({
                tags:true,
                minimumResultsForSearch:Infinity,
            });

            $("#select_btn_tag").click(function(){
                $("#select_all_tags > option").prop('selected','selected');
                $("#select_all_tags").trigger("change");
            });

            $("#deselect_btn_tag").click(function(){
                $("#select_all_tags > option").prop('selected',"");
                $("#select_all_tags").trigger("change");
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
                            {caption: "{{ $media->file_name }}", size: {{ $media->file_size }}, width: "120px", url: "{{ route('admin.post.media.destroy', [$media->id, '_token' => csrf_token()]) }}", key: "{{ $media->id }}"},
                        @endforeach
                    @endif
                ],
            })
        });
    </script>
@endsection
