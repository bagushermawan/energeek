<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return Inertia::render('Console/Role/View', ['roles' => $roles]);
    }

    public function listroles()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        return response()->json(['data' => $roles], 200);
    }

    public function permissions()
    {
        $permissions = Permission::all();
        $categoryMappings = [
            'user' => 'User Permission',
            'admin' => 'Admin Permission',
            'role' => 'Role Permission',
            'permission' => 'Permission',
        ];

        $permissionsByCategory = [];

        foreach ($permissions as $value) {
            $prefix = explode(' ', $value->name)[1]; // Ambil awalan sebagai prefix
            $category = isset($categoryMappings[$prefix]) ? $categoryMappings[$prefix] : $prefix;

            if (!isset($permissionsByCategory[$category])) {
                $permissionsByCategory[$category] = [];
            }

            $permissionsByCategory[$category][] = $value;
        }

        return response()->json(['data' => $permissionsByCategory]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role added successfully']);
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all()->groupBy(function ($item, $key) {
            return explode(' ', $item->name)[1]; // Mengelompokkan berdasarkan prefix
        });

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array', // Pastikan permissions adalah array
            'permissions.*' => 'exists:permissions,name', // Validasi bahwa setiap permission ada di tabel permissions
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::findOrFail($id);

        try {
            // Update nama role
            $role->update([
                'name' => $request->input('name')
            ]);

            // Sinkronisasi permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->input('permissions'));
            }

            return response()->json(['message' => 'Role updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update role', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        try {
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete role', 'error' => $e->getMessage()], 500);
        }
    }
}
