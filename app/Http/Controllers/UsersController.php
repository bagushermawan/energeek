<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return Inertia::render('Console/User/View', ['users' => $users]);
    }

    public function listusers()
    {
        $users = User::orderBy('name', 'asc')->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $request->password ? Hash::make($request->password) : Hash::make('123'),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::create($validated);
        return response()->json(['id' => $user->id]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['data' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if ($request->has('password') && !empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}

