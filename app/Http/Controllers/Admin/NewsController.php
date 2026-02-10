<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // 1. List All News
    public function index()
    {
        $allNews = News::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('allNews'));
    }

    // 2. Show Create Form
    public function create()
    {
        return view('admin.news.create');
    }

    // 3. Store New News (With Image Upload)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'details' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $news = new News();
        $news->title = $request->title;
        $news->slug = Str::slug($request->title);
        $news->details = $request->details;
        $news->status = 1;

        // Image Upload Logic
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $news->image = $path;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'News posted successfully!');
    }

    // 4. Show Edit Form
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    // 5. Update News
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'details' => 'required',
        ]);

        $news = News::findOrFail($id);
        $news->title = $request->title;
        $news->slug = Str::slug($request->title);
        $news->details = $request->details;
        $news->status = $request->status;

        // Update Image if a new one is uploaded
        if ($request->hasFile('image')) {
            // Optional: Delete old image here if you want to save space
            $path = $request->file('image')->store('news', 'public');
            $news->image = $path;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully!');
    }

    // 6. Delete News
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully!');
    }
}