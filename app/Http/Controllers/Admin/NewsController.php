<?php

namespace App\Http\Controllers\Admin;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // 1. List all News
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    // 2. Show the "Create News" Form
    public function create()
    {
        return view('admin.news.create');
    }

 // 3. Store the News in Database
public function store(Request $request)
{
    // 1. Validation (We can remove the 'max:2048' since we are resizing now)
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // max:2048 removed
    ]);

    $imagePath = null;

    // 2. Handle Image Upload & Resizing
    if ($request->hasFile('image')) {
        // Get the file
        $image = $request->file('image');
        
        // Create a unique filename
        $filename = time() . '.' . $image->getClientOriginalExtension();
        
        // Define the saving path (inside storage/app/public/news)
        $location = storage_path('app/public/news/' . $filename);

        // Make sure the directory exists
        if (!file_exists(storage_path('app/public/news'))) {
            mkdir(storage_path('app/public/news'), 0777, true);
        }

        // --- THE MAGIC HAPPENS HERE ---
        // Use Intervention Image to open, resize, and save the file.
        Image::make($image)
            ->resize(800, null, function ($constraint) {
                // This keeps the aspect ratio so the image doesn't get squished
                $constraint->aspectRatio();
                // This prevents smaller images from being upscaled (getting blurry)
                $constraint->upsize();
            })
            ->save($location, 80); // Save with 80% quality (good balance of size/quality)

        // Set the path to save in the database
        $imagePath = 'news/' . $filename;
    }

    // 3. Create the News Item
    News::create([
        'title' => $request->title,
        'slug' => Str::slug($request->title),
        'content' => $request->content,
        'image' => $imagePath,
        'author' => auth()->user()->name ?? 'Admin',
        'published_at' => now(),
    ]);

    return redirect()->route('admin.news.index')->with('success', 'News posted successfully!');
}

    // 4. Delete News
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Delete image if exists
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();
        return back()->with('success', 'News deleted successfully!');
    }
}