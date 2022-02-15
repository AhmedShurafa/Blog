<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function get_posts()
    {
        $posts = Post::whereHas('category', function ($query){
                $query->whereStatus(1);
            })->whereHas('user', function ($query){
                $query->whereStatus(1);
            })->post()->active()->orderBy('id','desc')->paginate(5);

        if($posts->count() > 0){

            $data['status'] = true;
            $data['message'] = 'show all posts';
            $data['data'] = PostResource::collection($posts);

            // return PostResource::collection($posts);
            return response()->json([
                'status' => true,
                'message' => 'All posts',
                'posts' => PostResource::collection($posts),
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no post found',
            ],201);
        }

    }

    public function search(Request $request)
    {
        $keyword =isset($request->keyword) && $request->keyword != '' ? $request->keyword : null;

        $posts = Post::with(['user','media','tags'])
            ->whereHas('category',function ($query){
                $query->whereStatus(1);
            })->whereHas('user',function ($query){
                $query->whereStatus(1);
            });

        if($keyword != null){
            $posts = $posts->search($keyword,null,true);
        }
        $posts = $posts->post()->active()->orderBy('id','desc')->paginate(10);

        if($posts->count() > 0){
            return response()->json([
                'status' => true,
                'message' => 'All posts',
                'posts' => PostResource::collection($posts),
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no post found',
            ],201);
        }
    }

    public function category($slug)
    {

        $category = Category::whereStatus(1)->whereSlug($slug)->first();

        if($category){

            $posts = Post::with(['user','media','tags'])
                ->whereCategoryId($category)
                ->active(1)
                ->post('post')
                ->orderBy('id','desc')
                ->paginate(5);

                if($posts->count() > 0){
                    return response()->json([
                        'status' => true,
                        'message' => 'All posts',
                        'posts' => PostResource::collection($posts),
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'no post found',
                    ],201);
                }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no category found',
            ],201);
        }
    }

    public function tag($slug)
    {
        $tag = Tag::whereSlug($slug)->first();

        if($tag){

            $posts = Post::with(['user','media','tags'])
                ->whereHas('tags',function($query) use ($slug)
                {
                    $query->where('slug',$slug);
                })
                ->active(1)
                ->post('post')
                ->orderBy('id','desc')
                ->get();

                if($posts->count() > 0){
                    return response()->json([
                        'status' => true,
                        'message' => 'All posts',
                        'posts' => PostResource::collection($posts),
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'no post found',
                    ],201);
                }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no tag found',
            ],201);
        }
    }

    public function archive($date)
    {
        $exploded = explode("-",$date);
        $month = $exploded[0];
        $year = $exploded[1];

        $posts = Post::with(['user','media','tags'])
            ->whereMonth('created_at',$month)
            ->whereYear('created_at',$year)
            ->active()
            ->post()
            ->orderBy('id','desc')
            ->paginate(5);


        if($posts->count() > 0){
            return response()->json([
                'status' => true,
                'message' => 'All posts',
                'posts' => PostResource::collection($posts),
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no post found',
            ],201);
        }
    }

    public function author($username)
    {
        $user = User::whereUsername($username)->whereStatus(1)->first();

        if($user){
            $posts = Post::with(['user','media','tags'])
            ->whereUserId($user->id)
            ->active()
            ->post()
            ->orderBy('id','desc')
            ->paginate(5);

            if($posts->count() > 0){
                return response()->json([
                    'status' => true,
                    'message' => 'All posts',
                    'posts' => PostResource::collection($posts),
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'no post found',
                ],201);
            }
        }
        return response()->json([
            'status' => false,
            'message' => 'Something was wrong !',
        ],201);
    }
}
