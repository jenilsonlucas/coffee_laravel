<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PageController extends Controller
{

    /**
     * displat the page home
     */

    public function index()
    {
        $blog = Post::orderby('post_at', 'desc')->limit(6)->get();
        return view('page.home', compact('blog'));        
    }

    /**
     * diplay the page about
     */
    public function about()
    {
        return view('page.about');
    }

}
