<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Place the eager-loading query here
        $users = User::with('roles')->paginate(15);

        return view('users.index', compact('users'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('users.form',compact('roles'));
    }
    public function list()
    {
        $users = User::with('roles')->paginate(15);
        return view('users.list', compact('users'));
    }
}
