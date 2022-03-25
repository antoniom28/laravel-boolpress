<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use App\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($id = null){
        if($id){
            $posts = Post::all()->where('id' , $id)->first();
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

    public function indexUsers($name = null){
        if($name != null){
            $users = User::where('name', 'like', $name.'%')->get();
            foreach($users as $user){
                $user['posts'] = $user->posts;
            }
        }

        return response()->json($users);
    }

    public function newComment(Request $request)
    {
        $post = new Comment();
        $post->content = $request['content'];
        $post->user_id = $request['user_id'];
        $post->post_id = $request['post_id'];
        $post->save(); 
        return response()->json([
            'message' => 'New post created'
        ]);
    }
}
