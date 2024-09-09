<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return Inertia::render('Console/Permission/View', ['permissions' => $permissions]);
    }

    public function listpermissions()
    {
        $permissions = Permission::orderBy('name', 'asc')->get();
        return response()->json(['data' => $permissions], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',

        ]);

        $permissions = Permission::create([
            'name' => $validatedData['name'],
        ]);

        return response()->json(['message' => 'Permissions created successfully', 'permissions' => $permissions]);
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json(['data' => $permission]);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $request->input('name');
        $permission->save();

        return response()->json(['message' => 'Permission updated successfully']);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
