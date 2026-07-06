<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
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
