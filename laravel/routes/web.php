<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageRoleController;

use Illuminate\Support\Facades\Route;
//* Redirect to login page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
Route::middleware(['auth'])->group(function (){
    //* Dashboard
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    //* Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'role:superadmin|admin'])->group(function () {
    Route::get('/asset', function () {return view('asset');})->name('asset');
    //Route::get('/user', [UserController::class,'create'])->name('user');
    Route::get('/users/list', [UserController::class,'list'])->name('users.list');
    Route::get('/users/create', [ManageUserController::class,'create'])->name('users.create');
    Route::post('/users/store', [ManageUserController::class,'store'])->name('users.store');
});

//  middleware('can:"view-any:requests"')
Route::middleware(['auth','can:"update:user-roles"'])->group(function(){
    Route::get('/users/roles', [ManageRoleController::class,'list'])->name('roles.list');
});
Route::middleware(['auth','can:"create:user"'])->group(function(){

});


require __DIR__.'/auth.php';
