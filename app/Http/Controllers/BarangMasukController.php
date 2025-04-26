<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('barangmasuk.index');
    }

    public function create()
    {
        return view('barangmasuk.create');
    }

    public function show($purchaseId)
    {
        return view('barangmasuk.show', compact('purchaseId'));
    }
}
