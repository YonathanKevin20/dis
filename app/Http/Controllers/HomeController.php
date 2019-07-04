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
        $model = InvoiceProduct::query();

        if($req->month != 'all') {
            $month = $req->month+1;
            $model->whereMonth('created_at', $month);
        }

        $model = $model->sum('total');

        return response()->json($model); 
    }

    public function getRevenueProduct(Request $req)
    {
        $label = Product::orderBy('id')->pluck('name');

        $month = $req->month;

        $model = Product::selectRaw('products.id, IFNULL(SUM(total), 0) as total')
            ->orderBy('id')
            ->leftJoin('invoice_products', function($join) use($month) {
                $join->on('products.id', '=', 'invoice_products.products_id')
                ->whereNULL('deleted_at');
                if($month != 'all') {
                    $join->whereMonth('created_at', $month+1);
                }
            })
            ->groupBy('products.id')
            ->pluck('total');

        $result = [
            'label' => $label,
            'data' => $model,
        ];

        return response()->json($result);
    }

    public function getItemsSold(Request $req)
    {
        $model = InvoiceProduct::query();

        if($req->month != 'all') {
            $month = $req->month+1;
            $model->whereMonth('created_at', $month);
        }

        $model = $model->sum('qty');

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

    public function getStoreLocation(Request $req)
    {
        $model = Store::selectRaw('location, COUNT(location) as count')->groupBy('location')->get();

        return response()->json($model);
    }

    public function getChart(Request $req)
    {
        $label = Product::orderBy('id')->pluck('name');

        $month = $req->month;

        $dataRealitation = Product::selectRaw('products.id, IFNULL(SUM(qty), 0) as qty')
            ->orderBy('id')
            ->leftJoin('invoice_products', function($join) use($month) {
                $join->on('products.id', '=', 'invoice_products.products_id')
                ->whereNULL('deleted_at');
                if($month != 'all') {
                    $join->whereMonth('created_at', $month+1);
                }
            })
            ->groupBy('products.id')
            ->pluck('qty');

        $dataTarget = ImportTarget::orderBy('products_id');

        if($month != 'all') {
            $dataTarget = $dataTarget->where('month', 'LIKE', '%'.$month.'%');
        }
            
        $dataTarget = $dataTarget->get()
            ->groupBy('products_id')
            ->map(function($row) {
                return $row->sum('qty');
            })->values();

        $result = [
            'label' => $label,
            'dataRealitation' => $dataRealitation,
            'dataTarget' => $dataTarget,
        ];

        return response()->json($result);
    }
}
