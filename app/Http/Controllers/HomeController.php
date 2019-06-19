<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportTarget;
use App\Models\InvoiceProduct;
use App\Models\Store;
use App\Models\Product;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function getRevenue(Request $req)
    {
        $model = InvoiceProduct::sum('total');

        return response()->json($model); 
    }

    public function getItemsSold(Request $req)
    {
        $model = InvoiceProduct::sum('qty');

        return response()->json($model); 
    }

    public function getStore(Request $req)
    {
        $new_store = Store::whereMonth('created_at', date('m'))->count('id');
        $existing_store = Store::whereMonth('created_at', '!=', date('m'))->count('id');

        $result = [
            'new_store' => $new_store,
            'existing_store' => $existing_store,
        ];

        return response()->json($result);
    }

    public function getChart(Request $req)
    {
        $label = InvoiceProduct::with(['product'])->select('products_id')->groupBy('products_id')->orderBy('products_id')->get()->pluck('product.name');

        $data1 = InvoiceProduct::orderBy('products_id')->get()->groupBy('products_id')->map(function($row) {
            return $row->sum('qty');
        })->values();

        $data2 = ImportTarget::orderBy('products_id')->get()->groupBy('products_id')->map(function($row) {
            return $row->sum('qty');
        })->values();

        $result = [
            'label' => $label,
            'data1' => $data1,
            'data2' => $data2,
        ];

        return response()->json($result);
    }
}
