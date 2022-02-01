@extends('layouts.admin')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Edit Category ({{ $category->name }})</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.post_categories.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Categories</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::model($category,['route'=>['admin.post_categories.update',$category->id],'method' => 'patch']) !!}

            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', old('name',$category->name),['class'=>'form-control']) !!}
                        @error('name')
                            <span class="text-danger">{{ $mesaage }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-4">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['0' => 'Inactive' , '1' => 'Active'], old('status',$category->status) ,['class'=>'form-control']) !!}
                    @error('status')
                        <span class="text-danger">{{ $mesaage }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group mt-3">
                {!! Form::submit('Update category', ['class'=>'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
