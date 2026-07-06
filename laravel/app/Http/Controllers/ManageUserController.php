<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ManageUserController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('users.form',compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'], // Validate Spatie role
        ]);

        // 1. Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. Assign the Spatie role directly to the created user
        $user->assignRole($request->role);

        // 3. Redirect back with success message (Omitting Auth::login)
        return redirect()->route('users.create')
            ->with('status', 'User created successfully with the assigned role.');
        
    }
}