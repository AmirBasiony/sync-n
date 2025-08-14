<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::paginate(10);

        return Inertia::render('types/Index', [
            'types' => $types,
        ]);
    }

    public function create()
    {
        return Inertia::render('types/Create');
    }

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        

        Type::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
        ]);
        

        return redirect()->route('types.index')->with('success', 'Type created successfully');
    }

    public function edit($id)
    {
        $type = Type::findOrFail($id);

        return Inertia::render('types/Edit', [
            'type' => $type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $type = Type::findOrFail($id);

        $type->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? '',
        ]);
        
        return redirect()->route('types.index')->with('success', 'Type updated.');
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return redirect()->route('types.index')->with('success', 'Type deleted.');
    }
}
