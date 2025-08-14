<?php

namespace App\Http\Controllers;

use App\Models\CaseStudyCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseStudyCategoryController extends Controller
{
    public function index()
    {
        $categories = CaseStudyCategory::paginate(10)->withQueryString();
        return Inertia::render('caseStudyCategory/Index', compact('categories'));
    }

    public function create()
    {
        return Inertia::render('caseStudyCategory/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CaseStudyCategory::create($request->only(['title', 'description']));

        return redirect()->route('category.index')->with('success', 'Category created.');
    }

    public function edit($id)
    {
        $category = CaseStudyCategory::findOrFail($id);
        return Inertia::render('caseStudyCategory/Edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = CaseStudyCategory::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only(['title', 'description']));

        return redirect()->route('category.index')->with('success', 'Category updated.');
    }

    public function destroy(CaseStudyCategory $category)
    {
        $category->delete();

        return redirect()->route('case-study-categories.index')->with('success', 'Category deleted.');
    }
}
