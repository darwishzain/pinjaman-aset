<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        return view('users.add');
    }
    public function list()
    {
        
    }
}
