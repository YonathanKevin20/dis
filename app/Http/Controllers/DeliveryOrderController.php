<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;
use Auth;
use DataTables;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        return view('pages.delivery-order.index');
    }

    public function create()
    {
        $model = DeliveryOrder::latest()->first();
        $no_delivery_order = generateNoDeliveryOrder($model->id);
        return view('pages.delivery-order.create', compact('no_delivery_order'));
    }

    public function view($id)
    {
        return view('pages.delivery-order.view', compact('id'));
    }

    public function getDatatables(Request $req)
    {
        $model = DeliveryOrder::with(['spv', 'sales', 'vehicle'])->select('delivery_orders.*');

        $params = $req->params;
        if($params && Auth::user()->role == 2) {
            $model->where('sales_id', $params['id']);
        }

        return DataTables::eloquent($model)->toJson();
    }

    public function getData(Request $req)
    {
        $model = DeliveryOrder::with(['spv', 'sales', 'vehicle'])
                    ->where('sales_id', Auth::user()->id)
                    ->where('status', '!=', '2')
                    ->get();

        return response()->json($model);
    }

    public function getDataProduct($delivery_orders_id)
    {
        $model = DeliveryOrderProduct::with(['product'])->where('delivery_orders_id', $delivery_orders_id)->get();

        return response()->json($model);
    }

    public function store(Request $req)
    {
        $delivery_order = DeliveryOrder::create([
            'no_delivery_order' => $req->no_delivery_order,
            'spvs_id' => Auth::user()->id,
            'sales_id' => $req->sales_id,
            'vehicles_id' => $req->vehicle_id,
        ]);

        $products = $req->products;
        foreach($products as $product) {
            DeliveryOrderProduct::create([
                'delivery_orders_id' => $delivery_order->id,
                'products_id' => $product['product']['id'],
                'qty' => $product['qty'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $data = DeliveryOrder::with(['spv', 'sales', 'vehicle'])->findOrFail($id);
        $product = DeliveryOrderProduct::with(['product'])->where('delivery_orders_id', $id)->get();

        $result = [
            'data' => $data,
            'product' => $product,
        ];

        return response()->json($result);
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
