<div class="wn__sidebar">
    <!-- Start Single Widget -->
    <aside class="widget search_widget">
        <h3 class="widget-title">Search</h3>

        {!! Form::open(['route'=>'frontend.search', 'method'=>'get']) !!}
            <div class="form-input">
                {!! Form::text('keyword', old('keyword',request()->keyword), ['placeholder'=>'Search...']) !!}
                {!! Form::button('<i class="fa fa-search"></i>' , ['type'=>'submit']) !!}
            </div>
        {!! Form::close() !!}
        </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget recent_widget">
        <h3 class="widget-title">Recent</h3>
        <div class="recent-posts">
            <ul>
                @foreach ($recent_posts as $recent_post)
                    <li>
                        <div class="post-wrapper d-flex">

                            <div class="thumb">
                                <a href="{{ route('post.show', $recent_post->slug) }}">
                                    @if($recent_post->media->count() > 0 )
                                        <img src="{{ asset('assets/posts/'.$recent_post->media->first()->file_name) }}" alt="{{ $recent_post->title }}">
                                    @else
                                        <img src="{{ asset('assets/posts/1.jpg') }}" alt="default_image">
                                    @endif
                                </a>
                            </div>

                            <div class="content">
                                <h4>
                                    <a href="{{ route('post.show', $recent_post->slug) }}">
                                        {{ Str::limit($recent_post->title, 20, '...') }}
                                    </a>
                                </h4>
                                <p>{{ $recent_post->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget comment_widget">
        <h3 class="widget-title">Recent Comments</h3>
        <ul>
            @foreach ($recent_comments as $recent_comment)
                <li>
                    <div class="post-wrapper">
                        <div class="thumb">
                            <img src="{{ get_gravatar($recent_comment->email,47) }}"
                            alt="{{ $recent_comment->name }}">
                        </div>
                        <div class="content">
                            <p>{{ $recent_comment->name }} says:</p>
                            <a href="{{ $recent_comment->url !='' ? $recent_comment->url : '#' }}">{{ Str::limit($recent_comment->comment, 20, '...') }}</a>
                        </div>
                    </div>
                </li>
            @endforeach

        </ul>
    </aside>
    <!-- End Single Widget -->
    <!-- Start Single Widget -->
    <aside class="widget category_widget">
        <h3 class="widget-title">Categories</h3>
        <ul>
            @foreach ($global_categories as $global_category)
                <li><a href="{{ route('frontend.category.posts', $global_category->slug) }}">{{ $global_category->name }}</a></li>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->

    <!-- Start Single Widget -->
    <aside class="widget category_widget">
        <h3 class="widget-title">Tags</h3>
        <ul>
            @foreach ($global_tags as $global_tag)
                <li>
                    <a href="{{ route('frontend.tag.posts', $tag->slug) }}">{{ $global_tag->name }}</a>
                    <span class="bg-info float-right p-1 text-white rounded">{{ $global_tag->posts_count }}</span>
                </li>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->


    <!-- Start Single Widget -->
    <aside class="widget archives_widget">
        <h3 class="widget-title">Archives</h3>
        <ul>
            @foreach ($global_archives as $key => $value)
                <li><a href="{{ route('frontend.archive.posts',$key."-".$value) }}">{{ date("F", mktime(0,0,0,$key,1)) .' '. $value }}</a></li>
            @endforeach
        </ul>
    </aside>
    <!-- End Single Widget -->
</div>
