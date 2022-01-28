@extends('layouts.app')

@section('content')
    <!-- Start Blog Area -->
    <div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12">
                    <h3>Edit comment on: ({{ $comment->post->title }})</h3>
                    {!! Form::model($comment,['route' => ['users.comment.update', $comment->id], 'method' => 'put']) !!}
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('name', 'Name') !!}
                                {!! Form::text('name', old('name',$comment->name),['class'=>'form-control']) !!}
                                @error('name')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('email', 'Email') !!}
                                {!! Form::email('email', old('email',$comment->email),['class'=>'form-control summernote']) !!}
                                @error('email')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                {!! Form::label('url', 'Website') !!}
                                {!! Form::email('url', old('url',$comment->url),['class'=>'form-control summernote']) !!}
                                @error('url')
                                    <span class="text-danger">{{ $mesaage }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-3">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status',$comment->status) ,['class'=>'form-control']) !!}
                            @error('status')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {!! Form::label('comment', 'comment') !!}
                            {!! Form::textarea('comment', old('comment',$comment->comment) ,['class'=>'form-control']) !!}
                            @error('comment')
                                <span class="text-danger">{{ $mesaage }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        {!! Form::submit('Update comment', ['class'=>'btn btn-success']) !!}
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
