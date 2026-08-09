<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;

class ManageAssetController extends Controller
{
    public function index()
    {
        if(auth()->user()->can('view-any:assets'))
        {
            $title = "Senarai Aset";
            $assets = Asset::all();
            return view('asset',compact('title','assets'));
        }
        else
        {
            abort(403,'Anda tidak mempunyai kebenaran untuk mengakses halaman ini');
        }
    }
    public function getByStatus($status)
    {
        if(auth()->user()->can('view-any:status'))
        {
            
        }
    }
}
