<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;
use Auth;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        return view('pages.delivery-order.index');
    }

    public function store(Request $req)
    {
        $delivery_order = DeliveryOrder::create([
            'no_delivery_order' => $req->no_delivery_order,
            'spvs_id' => Auth::user()->id,
            'sales_id' => $req->sales_id,
            'no_polisi' => $req->no_polisi,
            'driver' => $req->driver,
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
