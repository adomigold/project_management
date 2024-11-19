<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermissionsController extends Controller
{
    public function index()
    {
        return Permission::all();
    }

    public function show(Permission $permission)
    {
        return $permission;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        return $permission;
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($request->all());
        return $permission;
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->users()->detach();
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
