<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return $users;
    }

    public function show(User $user)
    {
        return $user->load('roles');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $role = Role::where(['name' => $request->role, 'guard_name' => 'web'])->first();
        $user->assignRole($role);

        event(new Registered($user));

        return $user->load('roles');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $user->update($request->all());
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where(['name' => $request->role, 'guard_name' => 'web'])->first();

        // Remove existing role
        $user->syncRoles([]);

        $user->assignRole($role);

        return response()->json(['message' => 'Role assigned successfully', 'user' => $user->load('roles')]);
    }
}
