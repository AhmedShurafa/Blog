@extends('layouts.app')
@section('content')
    <!-- Start Blog Area -->
    <div class="page-blog bg--white section-padding--lg blog-sidebar right-sidebar">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Post</td>
                                    <td>Status</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($comments as $comment)
                                    <tr>
                                        <td><a href="#"> {{ $comment->name }}</a></td>
                                        {{-- <td><a href="{{ route('post.show', $post->slug) }}"> {{ $comment->name }}</a></td> --}}
                                        <td>{{ $comment->post->title }}</td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="status" @if($comment->status == 1){ {{ "checked" }} } @endif
                                                 class="custom-control-input SwitchButton" value="{{ $comment->status }}" id="Switch{{ $comment->id }}">
                                                <label class="custom-control-label" for="Switch{{ $comment->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-white">
                                            {{-- <a href="{{ route('users.comment.edit', $comment->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a> --}}


                                            <a href="javascript:void(0);" onclick="if(confirm('Are You sure to delete this comment ?'))
                                                     {document.getElementById('comment-delete-{{ $comment->id }}').submit(); } else{return false} "
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                            <form action="{{ route('users.comment.destroy',$comment->id) }}" method="POST" id="comment-delete-{{ $comment->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Post Not Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">{{ $comments->links() }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
                <div class="col-lg-3 col-12 md-mt-40 sm-mt-40">
                    @include('partial.users.sidebar')
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog Area -->
@endsection

@section('script')
    <script>
        $(function(){
            $(".SwitchButton").click(function(){
                var num = $(this).val();
                if(num == 0){
                    num = 1;
                }else{
                    num = 0;
                }

                console.log($(this).val());
                $.ajax({
                        type:'PUT',
                        url:"{{ route('users.comment.update',$comment->id) }}",
                        data:'id= ' + {{ $comment->id }}+"_token={{ csrf_token() }}",
                    });
            });
        })
    </script>
@endsection
