<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Post;
use App\User;
use App\Tag;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected function create_slug($value , $id){
        $slug = Str::slug($value);
        $count = 1;
        while(Post::whereSlug($slug)->where('id' , '!=' , $id)->first()){
            $slug = Str::slug($value)."-".$count;
            $count++;
        }
        //Str::of($data["title"])->slug("-")
        return $slug;
    }

    protected function control_tag($tag_to_control){
        $tag_control = explode('#' , $tag_to_control);
        $tag_to_add = [];
        $tag_to_pass = [];

        foreach($tag_control as $control){
            if($control != null)
                $tag_to_add[] = $control;
        }

        foreach($tag_to_add as $add_tag){
            $temp = Tag::where('name' , $add_tag)->first();
            if($temp == null){
                Tag::create([
                    'name' => $add_tag,
                    'slug' => $add_tag,
                ]);
                $temp = Tag::where('name' , $add_tag)->first();
            }
            $tag_to_pass[] = $temp->id;
        }

        return $tag_to_pass;
    }

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
            $new_str = preg_replace("/\s+/", "*", $name);
            $name = $new_str;
            $users = User::whereRaw("REPLACE(`name`, ' ' ,'') LIKE ?", $name.'%')->get();
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
            'message' => 'New Comment Added'
        ]);
    }

    protected $validation = [
        "title" => "required",
        "published" => "required",
    ];

    public function newPost(Request $request)
    {
        $post = new Post();
        
        if(isset($request['image']) && $request['image'] != null){
            $path = Storage::put('uploads' , $request['image']);
            $post->image = $path;
        }

        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->published = 1;
        $post->slug = $this->create_slug($request["title"], null);
        $post->user_id = $request['user_id'];
        $tag_to_pass = $this->control_tag($request['tag']);
        
        $post->save(); 

        $post->tags()->sync($tag_to_pass);
        return response()->json([
            'message' => 'New Post Created'
        ]);
    }
}
