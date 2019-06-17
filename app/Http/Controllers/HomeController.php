<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\Store;

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
}
