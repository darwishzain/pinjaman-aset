<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user();
        $dashboardData = [];
        if( $user->hasRole('superadmin')) {
            $dashboardData['users'] = User::all();
            $dashboardData['roles'] = Role::all();
            $dashboardData['permissions'] = Permission::all();
        } elseif ($user->hasRole('admin')) {

        } elseif ($user->hasRole('manager')) {

        } elseif ($user->hasRole('staff')) {

        } else {

        }
        $dashboardData['user'] = $user;
        return view('dashboard', $dashboardData);
    }
}
// Assign multiple roles to a user
//$user->assignRole('Admin', 'Manager');
// Check if user has any of the roles
//$user->hasAnyRole(['Admin', 'Manager']);
// Check if user has all roles
//$user->hasAllRoles(['Admin', 'Manager']);   