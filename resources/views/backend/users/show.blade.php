@extends('layouts.admin')
@section('content')


    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">User ({{ $user->title }})</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">User</span>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">

                <tbody>
                    <tr>
                        <th colspan="4">Image</th>
                        @if($user->user_image != '')
                            <img src="{{ asset('assets/users/'.$user->user_image) }}" width="200px" class="img-fluid" alt="{{ $user->name }}">
                        @else
                            <img src="{{ asset('assets/users/user.png') }}" width="200px" class="img-fluid" alt="{{ $user->name }}">
                        @endif
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}  - {{ $user->username }}</td>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td>{{ $user->mobile }}</td>
                        <th>Status</th>
                        <td>{{ $user->status() }}</td>

                    </tr>
                    <tr>
                        <th>Created date</th>
                        <td>{{ $user->created_at->format('d-m-Y h:i a') }}</td>
                        <th>Posts Count</th>
                        <th>{{ $user->posts_count }}</th>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

@endsection
