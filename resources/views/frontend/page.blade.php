@extends('layouts.app')
@section('content')
<div class="page-blog-details section-padding--lg bg--white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="blog-details content">
                    <article class="blog-page-details">
                        @if($page->media->count() > 0)

                        <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">

                                @foreach ($page->media as $media)
                                    <li data-target="#carouselIndicators" data-slide-to="{{ $loop->index }}"
                                         class="{{ $loop->index == 0 ? 'active' : '' }}"></li>
                                @endforeach

                            </ol>
                            <div class="carousel-inner">
                                @foreach ($page->media as $media)
                                    <div class="carousel-item {{ $loop->index==0 ? 'active' : '' }}">
                                    <img class="d-block w-100" src="{{ asset('assets/pages/'.$media->file_name) }}" alt="{{ $page->title }}">
                                    </div>
                                @endforeach
                            </div>
                            @if($page->media->count() > 1)
                                <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                                </a>
                            @endif
                          </div>
                        @endif

                        <div class="page_wrapper">
                            <div class="page_header">
                                <h2>{{ $page->title }}</h2>
                                <div class="blog-date-categori">
                                    <ul>
                                        <li>{{ $page->created_at->format('M d, Y') }}</li>
                                        <li><a href="#" title="pages by boighor" rel="author">{{ $page->user->name }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="page_content">
                                <p>{!! $page->description !!}</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
