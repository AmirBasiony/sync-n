<?php

namespace App\Http\Controllers;

use App\Models\ContentHub;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ContentHub\ContentHubCreateRequest;
use App\Http\Requests\ContentHub\ContentHubUpdateRequest;
use Carbon\Carbon;

class ContentHubController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $createdAt = $request->input('created_at');

        $contentHubs = ContentHub::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('subtitle', 'like', "%$search%")
                        ->orWhere('category_name', 'like', "%$search%");
                });
            })
            ->when($createdAt, function ($query, $createdAt) {
                try {
                    $formatted = Carbon::createFromFormat('d-m-Y', $createdAt)->format('Y-m-d');
                    $query->whereDate('created_at', $formatted);
                } catch (\Exception $e) {
                    \Log::warning('Invalid date format for content hub filter: ' . $e->getMessage());
                }
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('contentHub/Index', [
            'contentHubs' => $contentHubs,
        ]);
    }

    public function create()
    {
        return Inertia::render('contentHub/Create');
    }

    public function store(ContentHubCreateRequest $request)
    {
        $validated = $request->validated();

        $blog = ContentHub::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
            'category_name' => $validated['category_name'],
        ]);

        if ($request->hasFile('image')) {
            $blog->addMedia($request->file('image'), 'content_hubs');
        }

        return redirect()->route('content-hubs.index')->with('success', 'Content Hub created successfully.');
    }

    public function edit($id)
    {
        $contentHub = ContentHub::findOrFail($id);

        return Inertia::render('contentHub/Edit', [
            'contentHub' => $contentHub,
        ]);
    }

    public function update(ContentHubUpdateRequest $request, $id)
    {
        $contentHub = ContentHub::findOrFail($id);
        $validated = $request->validated();

        $contentHub->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
            'category_name' => $validated['category_name'],
        ]);

        if ($request->hasFile('image')) {
            $media = $contentHub->media()->first();

            if ($media) {
                $contentHub->deleteMedia($media->id);
            }

            $contentHub->addMedia($request->file('image'), 'content_hubs');
        }

        return redirect()->route('content-hubs.index')->with('success', 'Content Hub updated successfully.');
    }

    public function destroy(ContentHub $contentHub)
    {
        $contentHub->delete();

        return redirect()->route('content-hubs.index')->with('success', 'Content Hub deleted successfully.');
    }
}
