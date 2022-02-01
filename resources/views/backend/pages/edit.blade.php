@extends('layouts.admin')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Edit page ({{ $page->title }})</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Pages</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::model($page,['route'=>['admin.pages.update',$page->id],'method' => 'patch' ,'files'=>true]) !!}

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('title', 'Title') !!}
                        {!! Form::text('title', old('title',$page->title),['class'=>'form-control']) !!}
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
                        {!! Form::textarea('description', old('description',$page->description),['class'=>'form-control summernote']) !!}
                        @error('title')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    {!! Form::label('category_id', 'Category') !!}
                    {!! Form::select('category_id', ['' => '----'] + $categories->toArray(), old('category_id',$page->category_id),['class'=>'form-control']) !!}
                    @error('category_id')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
                <div class="col-6">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status',$page->status) ,['class'=>'form-control']) !!}
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
                        {!! Form::file('images[]', ['id'=>'page-images' , 'multiple' =>'multiple']) !!}
                        @error('images')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>



            <div class="form-group mt-3">
                {!! Form::submit('Update page', ['class'=>'btn btn-success']) !!}
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

            $("#page-images").fileinput({
                theme:"fa",
                maxFileCount:5,
                allowedFileTypes: ['image'],    // allow only images
                showCancel:true,
                showRemove:true,
                showUpload:false,
                overwriteInitial:false,
                initialPreview:[
                    @if($page->media->count() > 0)
                        @foreach($page->media as $media)
                            "{{ asset('assets/pages/' . $media->file_name) }}",
                        @endforeach
                    @endif
                ],
                initialPreviewAsData:true,
                initialPreviewFileType:'image',

                initialPreviewConfig:[
                    @if($page->media->count() > 0)
                        @foreach($page->media as $media)
                            {caption: "{{ $media->file_name }}", size: {{ $media->file_size }}, width: "120px", url: "{{ route('admin.pages.media.destroy', [$media->id, '_token' => csrf_token()]) }}", key: "{{ $media->id }}"},
                        @endforeach
                    @endif
                ],
            })
        });
    </script>
@endsection
