<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with('roles')->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', $createdAt);
        }

        $permissions = $query->paginate(10)->withQueryString();

        return Inertia::render('permissions/Index', compact('permissions')); 
    }

    public function create()
    {
        return Inertia::render('permissions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string', 'max:255', 'unique:permissions,name'],
        ]);

        Permission::create($validated);
        
        return redirect()->route('permissions.index')->with('message', 'Permission created successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('message', 'Permission deleted successfully');
    }
}
