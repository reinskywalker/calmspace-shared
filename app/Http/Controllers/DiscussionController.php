<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string|max:1000',
        ]);

        Discussion::create($request->all());

        return back()->with('success', 'Comment added successfully.');
    }
}
