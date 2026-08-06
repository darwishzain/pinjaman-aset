<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;

class ManageRequestController extends Controller
{
    public function index()
    {
        if(auth()->user()){
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
            $title = 'Sokong Permohonan';
            $requests = RequestModel::where('T30_support_status', 'pending')
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('group', $user->group);
                })
                ->get();
            return view('request', compact('title', 'requests'));
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
            $title = 'Luluskan Permohonan';
            $requests = RequestModel::where('T30_approve_status', 'pending')
                ->where('T30_support_status', 'accepted')
                ->whereNot('T30_approve_status', 'accepted')
                ->get();
            return view('request', compact('title', 'requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    public function requestByStatus($status)
    {
        if(auth()->user()->can('view-any:requests')){
            $user = auth()->user();
            $title = 'Permohonan '.$status;
            $requests = RequestModel::where('T30_status', $status)
                ->where('T30T10_user_id', $user->id)
                ->get();
            return view('request', compact('title', 'requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    /*public function requestByGroup($group)
    {
        if(auth()->user()->can('view-any:requests')){
            $user = auth()->user();
            $title = 'Permohonan '.$group;
            $requests = RequestModel::where('T30_group', $user()->group)
                ->where('T30T10_user_id', $user->id)
                ->get();
            return view('request', compact('title', 'requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }*/
    public function transactions()
    {
        if(auth()->user()->can('create:transactions')){
            $user = auth()->user();
            $title = 'Pergerakan Aset';
            $requests = RequestModel::where('T30_status', ['pickup','active'])    
                ->where('T30_support_status', 'accepted')
                ->where('T30_approve_status', 'accepted')
                ->get();
            return view('request', compact('title', 'requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    public function pickupRequests()
    {
        if(auth()->user()->can('create:transactions')){
            $user = auth()->user();
            $title = 'Permohonan Untuk Diambil';
            $requests = RequestModel::where('T30_status', 'pickup')
                ->where('T30_support_status', 'accepted')
                ->where('T30_approve_status', 'accepted')
                ->get();
            return view('request', compact('title', 'requests'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
    public function getRequest(Reqest $request, $id)
    {
        if(auth()->user()->canany(['create:transactions','view:transactions','view-any:transactions'])){
            $request = RequestModel::findOrFail($id);
            return view('transaction', compact('request'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini.');
        }
    }
}
