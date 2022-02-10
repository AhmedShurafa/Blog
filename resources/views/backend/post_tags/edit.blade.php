@extends('layouts.admin')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Edit Tag ({{ $tag->name }})</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.post_tags.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Tags</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::model($tag,['route'=>['admin.post_tags.update',$tag->id],'method' => 'patch']) !!}

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', old('name',$tag->name),['class'=>'form-control']) !!}
                        @error('name')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                {!! Form::submit('Update tag', ['class'=>'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
