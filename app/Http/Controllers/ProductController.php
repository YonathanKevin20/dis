<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getData(Request $req)
    {
        $model = Product::all();

        return response()->json($model); 
    }

    public function index()
    {
        //
    }

    public function store(Request $req)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $req, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
