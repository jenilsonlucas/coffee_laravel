<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = Post::orderby('post_at', 'desc')->paginate(6);
        return view('page.blog', compact('blog'));
    }

    /**
     * searching a post
     */
    public function postSearch(Request $request)
    {
        $search = $request->validate([
            's' => "nullable"
        ]);

        if(empty($search['s'])) return redirect('/blog');

        $blog = Post::whereFullText('title', $search)
        ->orWhereFullText('subtitle', $search)->paginate(6);
        
        $title = "PESQUISA POR:";
        $search = $search['s'];

        return view('page.blog', compact('blog', 'title', 'search'));
    }

    public function show(Post $post)
    {
        $related = Post::where('category_id', $post->category->id)
            ->where('id', '<>', $post->id)
            ->limit(3)
            ->orderby('post_at', 'desc')
            ->get();

        return view('blog.blog-post', compact('post', 'related'));
    }

}
