<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\Product;

class ProductController extends Controller
{
    public function getData(Request $req)
    {
        $model = Product::all();

        return response()->json($model); 
    }

    public function getChart(Request $req)
    {
        $label = InvoiceProduct::with(['product'])->select('products_id')->groupBy('products_id')->orderBy('products_id')->get()->pluck('product.name');
        $data = InvoiceProduct::orderBy('products_id')->get()->groupBy('products_id')->map(function($row) {
            return $row->sum('qty');
        })->values();

        $result = [
            'label' => $label,
            'data' => $data,
        ];

        return response()->json($result);
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
