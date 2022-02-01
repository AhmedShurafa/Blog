@extends('layouts.admin')
@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Comments</h6>
        </div>
        @include('backend.post_comments.filter.filter')

        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Author</th>
                        <th width="40%">Comment</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comments as $comment)
                        <tr>
                            <td><img src="{{ get_gravatar($comment->email, 50) }}" class="img-circle"></td>

                            <td>
                                <a href="{!! $comment->url != '' ? $comment->url : 'javascript:void(0);' !!}" target="_blank">
                                   {{ $comment->name }}
                                </a>
                            </td>

                            <td>
                                <div class="text-muted">
                                   <a href="{{ route('admin.posts.show', $comment->post_id) }}">{{ $comment->post->title  }}</a>
                                </div>
                                {!! $comment->comment !!}
                            </td>
                            <td>{{ $comment->status() }}</td>

                            <td>{{ $comment->created_at->format('d-m-Y h:i a') }}</td>
                            <td>
                                <a href="{{ route('admin.post_comments.edit', $comment->id) }}"
                                    class="btn btn-success"><i class="fa fa-edit"></i></a>

                                <a href="javascript:vaid(0)"
                                data-url="{{ route('admin.post_comments.destroy', $comment->id) }}"
                                class="btn btn-danger delete2"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Not Comments Found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <div class="float-right">
                            <th colspan="6">{!! $comments->appends(request()->input())->links() !!}</th>

                        </div>
                    </tr>
                </tfoot>
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
                            success: function(res) {
                                if (res.status) {
                                    swal("Comment Deleted Successfully!", {
                                        icon: "success",
                                    });
                                    window.reload();
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
