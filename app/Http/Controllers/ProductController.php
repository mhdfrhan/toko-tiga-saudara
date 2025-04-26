<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('produk.index');
    }

    public function create()
    {
        return view('produk.create');
    }

    public function categories()
    {
        return view('produk.kategori');
    }
}
