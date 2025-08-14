<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\BlogCreateRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $createdAt = $request->input('created_at');

        $blogs = Blog::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('subtitle', 'like', "%$search%")
                        ->orWhere('content', 'like', "%$search%");
                });
            })
            ->when($createdAt, function ($query, $createdAt) {
                try {
                    $formatted = Carbon::createFromFormat('d-m-Y', $createdAt)->format('Y-m-d');
                    $query->whereDate('created_at', $formatted);
                } catch (\Exception $e) {
                    Log::warning('Invalid date format for blog filter: ' . $e->getMessage());
                }
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('blog/Index', [
            'blogs' => $blogs,
        ]);
    }

    public function create()
    {
        return Inertia::render('blog/Create');
    }

    public function store(BlogCreateRequest $request)
    {
        $validated = $request->validated();

        $blog = Blog::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('image')) {
            $blog->addMedia($request->file('image'), 'blogs');
        }

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);

        return Inertia::render('blog/Edit', [
            'blog' => $blog,
        ]);
    }

    public function update(BlogUpdateRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $validated = $request->validated();

        $blog->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('image')) {
            $media = $blog->media()->first();

            if ($media) {
                $blog->deleteMedia($media->id);
            }

            $blog->addMedia($request->file('image'), 'blogs');
        }

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
