<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use App\Models\Transaction;

class ManageTransactionController extends Controller
{
    public function index()
    {
        $title = "Pergerakan Aset";
        $requests = RequestModel::where('T30_support_status','accepted')
            ->where('T30_approve_status','accepted')
            ->get();
        return view('transaction', compact('title','requests'));
    }
    public function request($id)
    {
        $title = "Pergerakan Aset Permohonan";
        $request = RequestModel::where('T30_id',$id)
            ->first();
        $transactions = Transaction::where('T40T30_request_id',$request->T30_id)
            ->get();
        return view('transaction', compact('title','request','transactions'));
    }
}
