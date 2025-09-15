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
        $title = "Home - Bem-vindo ao nosso espaço online";
        return view('page.home', compact('blog', 'title'));        
    }

    /**
     * diplay the page about
     */
    public function about()
    {
        $title = "Sobre - Conheça nossa missão e valores";
        return view('page.about', compact('title'));
    }

}
