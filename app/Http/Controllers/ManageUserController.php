<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admins.users.index');
    }

    public function article()
    {
        $articles = Article::where('status', '!=', 'published')->paginate(10);

        return view('articles.articles', compact('articles'));
    }
}
