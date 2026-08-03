<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class ManageRequestController extends Controller
{
    public function index()
    {
        if(auth()->user()->can('view:requests')){
            $user = auth()->user();
            $requests = RequestModel::where('T30T10_user_id', $user->id)->get();
            return view('request', compact('requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    public function supportRequests()
    {
        if(auth()->user()->can('support:requests')){
            $user = auth()->user();
            $requests = RequestModel::where('T30_supported_status', 'pending')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('group', $user->group);
                })
                ->get();
            return view('request', compact('requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    public function approveRequests()
    {
        if(auth()->user()->can('approve:requests')){
            $user = auth()->user();
            $requests = RequestModel::where('T30_approved_status', 'pending')
                ->where('T30_supported_status', 'accepted')
                ->get();
            return view('request', compact('requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
}
