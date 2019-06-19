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
        $label = Product::orderBy('id')->pluck('name');

        $data1 = Product::selectRaw('products.id, IFNULL(SUM(qty), 0) as qty')
            ->orderBy('id')
            ->leftJoin('invoice_products', 'products.id', '=', 'invoice_products.products_id')
            ->whereNULL('deleted_at')
            ->groupBy('products.id')
            ->pluck('qty');

        $data2 = ImportTarget::orderBy('products_id')
            ->get()
            ->groupBy('products_id')
            ->map(function($row) {
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
