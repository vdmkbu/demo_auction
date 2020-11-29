<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __invoke()
    {

        return view('news.index', [
            'posts' => Post::orderByDate()->get()
        ]);
    }
}
