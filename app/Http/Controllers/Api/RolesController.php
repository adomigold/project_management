<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class RolesController extends Controller
{
    public function index()
    {
        return Role::with('permissions')->get();
    }

    public function show(Role $role)
    {
        return $role->load('permissions');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|not_in:Admin',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        return $role;
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|not_in:Admin',
        ]);

        if ($role->name === 'Admin') {
            return response()->json(['message' => 'Admin role cannot be updated'], 400);
        }

        $role->update($request->all());
        return $role;
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'Admin') {
            return response()->json(['message' => 'Admin role cannot be deleted'], 400);
        }

        $role->users()->detach();
        $role->permissions()->detach();
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignPermission(Request $request, Role $role)
    {
        $request->validate([
            'permission_id' => 'required|integer|exists:permissions,id',
        ]);

        $role->givePermissionTo($request->permission_id);
        return $role->load('permissions');
    }

    public function removePermission(Request $request, Role $role)
    {
        $request->validate([
            'permission_id' => 'required|integer|exists:permissions,id',
        ]);

        $role->revokePermissionTo($request->permission_id);
        return $role->load('permissions');
    }
}
