<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;

class AdminController extends Controller
{
    public function masterdata()
    {
        $userCount = User::count();
        $articles = Article::where('status', '!=', 'published')->paginate(10);
        $latestUsers = User::latest()->take(5)->get();
        $totalArticles = Article::count();
        return view('admins.masterdata', compact('latestUsers', 'userCount', 'articles', 'totalArticles'));
    }
}
