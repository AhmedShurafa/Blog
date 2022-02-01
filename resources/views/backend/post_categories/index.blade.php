@extends('layouts.admin')
@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">

            <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
            <div class="ml-auto">
                <a href="{{ route('admin.post_categories.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">Add new Category</span>
                </a>
            </div>
        </div>
        @include('backend.post_categories.filter.filter')

        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Posts Count</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>

                            <td><a href="{{ route('admin.posts.index', ['category_id'=>$category->id]) }}">{{ $category->posts_count }}</a></td>

                            <td>{{ $category->status() }}</td>
                            <td>{{ $category->created_at->format('d-m-Y h:i a') }}</td>
                            <td>
                                <a href="{{ route('admin.post_categories.edit', $category->id) }}"
                                    class="btn btn-success"><i class="fa fa-edit"></i></a>

                                <a href="javascript:vaid(0)"
                                data-url="{{ route('admin.post_categories.destroy', $category->id) }}"
                                class="btn btn-danger delete2"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Not Found Categories Found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <div class="float-right">
                            <th colspan="5">{!! $categories->appends(request()->input())->links() !!}</th>

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
