@extends('layouts.admin')
@section('content')


    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Posts ({{ $post->title }})</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-home"></i>
                    </span>
                    <span class="text">Posts</span>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">

                <tbody>
                    <tr>
                        <th>Title</th>
                        <td colspan="4"><a href="{{ route('admin.posts.show', $post->id) }}">{{ $post->title }}</a></td>
                    </tr>
                    <tr>
                        <th>Commets</th>
                        <td>{{ $post->comment_able == 1 ? $post->comments->count() : 'Disallow' }}</td>
                        <th>Status</th>
                        <td>{{ $post->status() }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $post->category->name }}</td>
                        <th>Author</th>
                        <td>{{ $post->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Created date</th>
                        <td>{{ $post->created_at->format('d-m-Y h:i a') }}</td>
                        <th></th>
                        <th></th>
                    </tr>

                    <tr>

                        <td colspan="4">
                            <div class="row">
                                @if ($post->media->count() > 0)
                                    @foreach ($post->media as $media)
                                        <div class="col-2">
                                            <img src="{{ asset('assets/posts/' . $media->file_name) }}"
                                                class="img-fluid">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

    {{-- Start Comments --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Comments</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Author</th>
                        <th width="40%">comment</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($post->comments as $comment)
                        <tr>
                            <td><img src="{{ get_gravatar($comment->email, 50) }}" class="img-circle"></td>
                            <td>{{ $comment->name }}</td>
                            <td>{!! $comment->comment !!}</td>
                            <td>{{ $comment->status() }}</td>

                            <td>{{ $post->created_at->format('d-m-Y h:i a') }}</td>
                            <td>
                                {{-- <a href="javascript:valid(0)" data-url="{{ route('users.comment.destroy',$comment->id) }}"
                                class="delete2 btn btn-success"><i class="fa fa-edit"></i></a> --}}
                                <a href="javascript:valid(0)"
                                    data-url="{{ route('users.comment.destroy', $comment->id) }}"
                                    class="delete2 btn btn-danger"><i class="fa fa-trash"></i></a>

                                {{-- <a href="javascript:valid(0)"
                            onclick="if(confirm('Are You sure to delete this comment ?'))
                            {document.getElementById('comment-delete-{{ $comment->id }}').submit(); } else{return false} "

                            class="btn btn-danger"><i class="fa fa-trash"></i></a>

                            <form action="{{ route('users.comment.destroy',$comment->id) }}"
                                 method="POST" id="comment-delete-{{ $comment->id }}">
                                @csrf
                                @method('DELETE')
                            </form> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Not Comments Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $('.delete2').click(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var delete_url = $(this).data('url');
                console.log(delete_url);
                swal({
                    title: "Are you sure to delete this comment ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: 'delete',
                            url: delete_url,
                            // data:'_token = <?php echo csrf_token(); ?>', "_token": "{{ csrf_token() }}",
                            success: function(res) {
                                if (res.status) {
                                    swal("Comment Deleted Successfully!", {
                                        icon: "success",
                                    });
                                }
                            },
                            error: function(x, y, z) {
                                console.log(x);
                                console.log(y);
                                console.log(z);
                            }
                        });
                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });
            });

        });
    </script>

@endsection
