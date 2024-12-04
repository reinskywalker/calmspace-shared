<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function view()
    {
        $articles = Article::paginate(10);

        return view('home', compact('articles'));
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function updateStatus(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->status = $request->input('status');
        $article->save();

        return redirect()->back()->with('success', 'Article status updated successfully.');
    }


    public function show($id)
    {
        $article = Article::with('discussions')->findOrFail($id);
        return view('show', compact('article'));
    }

    public function create()
    {
        return view('articles.create');
    }


    public function index()
    {
        $articles = Article::where('status', '!=', 'published')->paginate(10);

        return view('articles.articles', compact('articles'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'audio_video_url' => 'nullable|url|max:255',
            'thumbnail_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail_image_url' => 'nullable|url|max:255',
            'content' => 'required|string',
            'posted_by' => 'required|string|max:255',
        ]);

        if ($request->hasFile('thumbnail_image_file')) {
            $fileName = time() . '.' . $request->thumbnail_image_file->extension();
            $request->thumbnail_image_file->move(public_path('images'), $fileName);
            $thumbnailImageUrl = 'images/' . $fileName;
        } else {
            $thumbnailImageUrl = $request->thumbnail_image_url;
        }

        $article = new Article([
            'title' => $validated['title'],
            'audio_video_url' => $validated['audio_video_url'],
            'thumbnail_image_url' => $thumbnailImageUrl,
            'content' => $validated['content'],
            'posted_by' => $validated['posted_by'],
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        $article->save();

        return redirect()->route('articles.create')->with('success', 'Article created successfully!');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => 'Article deleted successfully.']);
    }


    public function mypost()
    {
        $articles = Article::where('user_id', Auth::id())->paginate(10);
        return view('articles.mypost', compact('articles'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.edit', compact('article'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,published,revised',
        ]);

        $article = Article::findOrFail($id);
        $article->status = $request->input('status');
        $article->save();

        return redirect()->route('articles')->with('success', 'Article status updated successfully!');
    }
}
