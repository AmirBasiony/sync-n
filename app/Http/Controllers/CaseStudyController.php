<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseStudyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $createdAt = $request->input('created_at');

        $caseStudies = CaseStudy::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('author', 'like', "%$search%");
                });
            })
            ->when($createdAt, function ($query, $createdAt) {
                try {
                    $formatted = Carbon::createFromFormat('d-m-Y', $createdAt)->format('Y-m-d');
                    $query->whereDate('created_at', $formatted);
                } catch (\Exception $e) {
                    Log::warning('Invalid date format for case study filter: ' . $e->getMessage());
                }
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('caseStudies/Index', compact('caseStudies'));
    }

    public function create()
    {
        $categories = CaseStudyCategory::all();
        return Inertia::render('caseStudies/Create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $caseStudy = CaseStudy::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'author' => $validated['author'],
            'category_id' => $validated['category_id'],
        ]);

        if ($request->hasFile('image')) {
            $caseStudy->addMedia($request->file('image'), 'case_study');
        }

        return redirect()->route('case-studies.index')->with('success', 'Case study created.');
    }

    public function edit($id)
    {
        $caseStudy = CaseStudy::findOrFail($id);
        $categories = CaseStudyCategory::all();
        return Inertia::render('caseStudies/Edit', compact('caseStudy', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $caseStudy = CaseStudy::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $caseStudy->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'author' => $validated['author'],
            'category_id' => $validated['category_id'],
        ]);

        if ($request->hasFile('image')) {
            $media = $caseStudy->media()->first();

            if ($media) {
                $caseStudy->deleteMedia($media->id);
            }

            $caseStudy->addMedia($request->file('image'), 'case_study');
        }

        return redirect()->route('case-studies.index')->with('success', 'Case study updated.');
    }

    public function destroy(CaseStudy $caseStudy)
    {
        $caseStudy->delete();

        return redirect()->route('case-studies.index')->with('success', 'Case study deleted.');
    }
}
