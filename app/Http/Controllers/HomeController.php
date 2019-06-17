<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\Store;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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
        $model = InvoiceProduct::select('products_id')->groupBy('products_id')->get();

        return response()->json($model); 
    }

    public function getStore(Request $req)
    {
        $model = Store::count('id');

        return response()->json($model); 
    }
}
