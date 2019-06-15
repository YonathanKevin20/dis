<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Auth;
use DataTables;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('pages.invoice.index');
    }

    public function create()
    {
        return view('pages.invoice.create');
    }

    public function view($id)
    {
        return view('pages.invoice.view', compact('id'));
    }

    public function getDatatables(Request $req)
    {
        $model = Invoice::with(['store', 'sales', 'deliveryOrder'])->select('invoices.*');

        $params = $req->params;
        if($params && Auth::user()->role == 2) {
            $model->where('invoices.sales_id', $params['id']);
        }

        return DataTables::eloquent($model)->toJson();
    }

    public function store(Request $req)
    {
        $invoice = Invoice::create([
            'stores_id' => $req->stores_id,
            'sales_id' => $req->sales_id,
            'delivery_orders_id' => $req->delivery_orders_id,
        ]);

        $this->sumQty($req->products, $req->delivery_orders_id);
        $this->changeStatus($req->delivery_orders_id);

        $products = $req->products;

        foreach($products as $product) {
            InvoiceProduct::create([
                'invoices_id' => $invoice->id,
                'products_id' => $product['product']['id'],
                'qty' => $product['qty'],
                'price' => $product['product']['price'],
                'total' => $product['total'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $data = Invoice::with(['store', 'sales', 'deliveryOrder'])->findOrFail($id);
        $product = InvoiceProduct::with(['product'])->where('invoices_id', $id)->get();

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

    protected function sumQty($products, $delivery_orders_id)
    {
        $model = DeliveryOrderProduct::where('delivery_orders_id', $delivery_orders_id)->get();
        foreach($model as $key => $value) {
            $total = $value->qty - $products[$key]['qty'];
            $value->update([
                'qty' => $total,
            ]);
        }
    }

    protected function changeStatus($delivery_orders_id)
    {
        $qty = DeliveryOrderProduct::where('delivery_orders_id', $delivery_orders_id)->sum('qty');
        if($qty == 0) {
            $model = DeliveryOrder::findOrFail($delivery_orders_id);
            $model->update([
                'status' => '2',
            ]);
        }
        else {
            $model = DeliveryOrder::findOrFail($delivery_orders_id);
            $model->update([
                'status' => '1',
            ]);
        }
    }
}
