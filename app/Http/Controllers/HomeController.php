<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportTarget;
use App\Models\InvoiceProduct;
use App\User;
use App\Models\Store;
use App\Models\Product;
use DateTime;

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
            ->orderBy('products.id')
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

    public function getItemsSoldStore(Request $req)
    {
        $label = Store::orderBy('id')
            ->groupBy('location')
            ->pluck('location');

        $model = Store::selectRaw('stores_id, IFNULL(SUM(qty), 0) as qty')
            ->orderBy('stores.id')
            ->leftJoin('invoices', function($join) {
                $join->on('invoices.stores_id', '=', 'stores.id')
                ->whereNULL('invoices.deleted_at')
                ->leftJoin('invoice_products', function($join) {
                    $join->on('invoice_products.invoices_id', '=', 'invoices.id')
                    ->whereNULL('invoice_products.deleted_at');
                });
            })
            ->whereNULL('stores.deleted_at')
            ->groupBy('stores_id')
            ->pluck('qty');

        $result = [
            'label' => $label,
            'data' => $model,
        ];

        return response()->json($result);
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
        $label = Store::orderBy('id')
            ->groupBy('location')
            ->pluck('location');

        $model = Store::selectRaw('COUNT(location) as count')
            ->orderBy('id')
            ->groupBy('location')
            ->pluck('count');

        $result = [
            'label' => $label,
            'data' => $model,
        ];

        return response()->json($result);
    }

    public function getAvgTimeSales(Request $req)
    {
        $label = User::whereRole(2)
            ->orderBy('id')
            ->pluck('name');

        $model = User::selectRaw("
            users.id,
            users.name,
            delivery_orders.id as do_id,
            no_delivery_order,
            DATEDIFF(invoice_products.updated_at, delivery_orders.created_at) as 'diff'")
            ->leftJoin('delivery_orders', function($join) {
                $join->on('delivery_orders.sales_id', '=', 'users.id')
                ->whereNULL('delivery_orders.deleted_at')
                ->whereStatus('2')
                ->leftJoin('invoices', function($join) {
                    $join->on('invoices.delivery_orders_id', '=', 'delivery_orders.id')
                    ->whereNULL('invoices.deleted_at')
                    ->latest('invoices.updated_at')
                    ->leftJoin('invoice_products', function($join) {
                        $join->on('invoice_products.invoices_id', '=', 'invoices.id')
                        ->whereNULL('invoice_products.deleted_at')
                        ->latest('invoice_products.updated_at');
                    });
                });
            })
            ->whereRole(2);

        if($req->sales_id) {
            $sales_id = $req->sales_id;
            $model->where('users.id', $sales_id);
        }

        $model = $model->groupBy([
            'users.id',
            'users.name',
            'do_id',
            'no_delivery_order',
            'diff',
        ])->get();

        $data = array();
        $data_id = array();
        foreach($model as $value) {
            $id = $value->id ?? 0;
            if(!array_key_exists($id, $data)) {
                $data[$id] = $value->diff ?? 0;
                $data_id[$id] = $value->id;
            }
            else {
                $data[$id] = $data[$id] + $value->diff ?? 0;
            }
        }
        $data = array_values($data);
        $data_id = array_values($data_id);

        $result = [
            'label' => $label,
            'data' => $data,
            'id' => $data_id,
            'model' => $model,
        ];

        return response()->json($result);
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
