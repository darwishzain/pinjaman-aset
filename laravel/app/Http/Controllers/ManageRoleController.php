<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class ManageRoleController extends Controller
{
    public function list()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('roles.list', compact('roles', 'permissions'));
    }
}
