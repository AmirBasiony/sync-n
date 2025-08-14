<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminCreateRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::with('roles')->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', $createdAt);
        }

        $admins = $query->paginate(10)->withQueryString();

        return Inertia::render('admins/Index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::get();

        return Inertia::render('admins/Create', compact('roles'));
    }

    public function edit($id)
    {
        $roles = Role::get();
        $admin = Admin::with('roles')->firstWhere('id', $id);

        return Inertia::render('admins/Edit', compact('admin', 'roles'));
    }

    public function store(AdminCreateRequest $request)
    {
        $validated = $request->validated();

        // Extract role_id
        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        // Create admin
        $admin = Admin::create($validated);

        // Sync role
        if ($roleId) {
            $admin->syncRoles([$roleId]);
        }

        return redirect()->route('admins.index')->with('message', 'Admin Created successfully');
    }

    public function update(AdminUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        // Extract role_id
        $roleId = $validated['role_id'] ?? null;
        unset($validated['role_id']);

        // Find and update admin
        $admin = Admin::findOrFail($id);
        $admin->update($validated);

        // Sync role
        if ($roleId) {
            $admin->syncRoles([$roleId]);
        }

        return redirect()->route('admins.index')->with('message', 'Admin Updated successfully');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admins.index')->with('message', 'Admin deleted successfully');
    }

    private function handleAdminRoles(Admin $admin, array &$validated)
{
    $roleId = $validated['role_id'] ?? null;
    unset($validated['role_id']);

    if ($roleId) {
        $admin->syncRoles([$roleId]);
    }
}
}
