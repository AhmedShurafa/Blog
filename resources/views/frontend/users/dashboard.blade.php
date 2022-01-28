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
                                    <td width="50%">Title</td>
                                    <td>Comments</td>
                                    <td>Status</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                    <tr>
                                        <td><a href="{{ route('post.show', $post->slug) }}"> {{ $post->title }}</a></td>
                                        <td><a href="{{ route('users.comments',['post' => $post->id]) }}">{{ $post->comments_count }}</a> </td>
                                        <td>{{ $post->status }}</td>
                                        <td class="text-white">
                                            <a href="{{ route('users.post.edit',$post->slug) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>

                                            <a href="javascript:void(0);" onclick="if(confirm('Are You sure to delete this post ?'))
                                                     {document.getElementById('post-delete-{{ $post->id }}').submit(); } else{return false} "
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                            <form action="{{ route('users.post.destroy',$post->slug) }}" method="POST" id="post-delete-{{ $post->id }}">
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
                                    <td colspan="4">{{ $posts->links() }}</td>
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
