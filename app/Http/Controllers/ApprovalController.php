<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ApprovalController extends Controller
{
    public function viewapproval()
    {
        $articles = Article::paginate(10);
        return view(
            'articles.approval',
            compact('articles'),
        );
    }

    public function index()
    {
        $articles = Article::where('status', '!=', 'published')->paginate(10);

        return view('articles.approval', compact('articles'));
    }
}
