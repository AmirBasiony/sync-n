<?php

namespace App\Http\Controllers;

use App\Http\Requests\Faqs\FaqsCreateRequest;
use App\Http\Requests\Faqs\FaqsUpdateRequest;
use App\Models\Faqs;
use Inertia\Inertia;

class FaqsController extends Controller
{
    public function index()
    {
        $faqs = Faqs::paginate(10)->withQueryString();
        return Inertia::render("faqs/Index", ['faqs' => $faqs]);
    }

    public function create()
    {
        return Inertia::render("faqs/Create");
    }

    public function store(FaqsCreateRequest $request)
    {
        $validated = $request->validated();

        Faqs::create($validated);

        return redirect()->route('faqs.index')->with('success', 'Faqs created successfully.');
    }

    public function edit($id)
    {
        $faqs = Faqs::findOrFail($id);

        return Inertia::render('faqs/Edit', [
            'faqs' => $faqs,
        ]);
    }

    public function update(FaqsUpdateRequest $request, $id)
    {
        $faqs = Faqs::findOrFail($id);
        $validated = $request->validated();

        $faqs->update($validated);

        return redirect()->route('faqs.index')->with('success', 'Faqs updated successfully.');
    }

    public function destroy(Faqs $faqs)
    {
        $faqs->delete();

        return redirect()->route('faqs.index')->with('success', 'Faqs deleted successfully.');
    }

}
