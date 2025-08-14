<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleCreateRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::with('permissions')->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', $createdAt);
        }

        $roles = $query->paginate(10)->withQueryString();

        return Inertia::render('roles/Index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::get();

        return Inertia::render('roles/Create', compact('permissions'));
    }

    public function store(RoleCreateRequest $request)
    {

        $role = Role::create([
            'name' => $request->validated('name'),
            'display_name' => $request->validated('name'),
        ]);

        $role->permissions()->sync($request->validated('permissions'));

        return redirect()->route('roles.index')->with('message', 'Role Created successfully');
    }

    public function edit(Role $role)
    {
        return Inertia::render('roles/Edit',  [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('id'),
            ],
            'permissions' => Permission::select('id', 'name')->get(),
        ]);
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->validated('name'),
            'display_name' => $request->validated('name'),
        ]);

        $role->permissions()->sync($request->validated('permissions'));

        return redirect()->route('roles.index')->with('message', 'Role Updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('message', 'Role deleted successfully');
    }
}
