<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use App\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($slug = null){
        if($slug){
            $posts = Post::all()->where('slug' , $slug)->first();
            $posts->user;
            $posts->tags;
            $posts->comments; 
        }
        else{
            $posts = Post::all();
            foreach($posts as $post){
                $post->user;
                $post->tags;
                $post->comments; 
            }
        }
        return response()->json($posts);
    }

    public function indexUsersName($name = null){
        if($name != null){
            $users = User::where('name', 'like', $name.'%')->get();
            foreach($users as $user){
                $user['posts'] = $user->posts;
            }
        }
        return response()->json($users);
    }

    public function indexUser($slug = null){
        if($slug != null){
            $user = User::where('slug', $slug)->first();
            $user->posts;
            foreach($user->posts as $post){
                $post->user;
                $post->tags;
                $post->comments; 
            }
        }
        return response()->json($user);
    }

    public function newComment(Request $request)
    {
        $post = new Comment();
        $post->content = $request['content'];
        $post->user_id = $request['user_id'];
        $post->name = $request['name'];
        $post->post_id = $request['post_id'];
        $post->save(); 
        return response()->json([
            'message' => 'New post created'
        ]);
    }
}
