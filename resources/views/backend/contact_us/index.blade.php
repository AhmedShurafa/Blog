@extends('layouts.admin')
@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">Contact Us</h6>
        </div>
        @include('backend.contact_us.filter.filter')

        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th class="text-center"5>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td>
                                <a href="{{ route('admin.contact_us.show', $message->id) }}">
                                    {{ $message->name }}
                                </a>
                            </td>
                            <td>{{ $message->title }}</td>
                            <td>{{ $message->status() }}</td>
                            <td>{{ $message->created_at->format('d-m-Y h:i a') }}</td>
                            <td>
                                <a href="{{ route('admin.contact_us.show', $message->id) }}"
                                    class="btn btn-success"><i class="fa fa-eye"></i></a>

                                <a href="javascript:vaid(0)"
                                data-url="{{ route('admin.contact_us.destroy', $message->id) }}"
                                class="btn btn-danger delete2"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Not Messages Found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <div class="float-right">
                            <th colspan="5">{!! $messages->appends(request()->input())->links() !!}</th>

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
                    title: "Are you sure to delete this message ?",
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
