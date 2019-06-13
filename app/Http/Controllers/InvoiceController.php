<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
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

    public function getDatatables(Request $req)
    {
        $model = Invoice::with(['store', 'sales', 'deliveryOrder']);

        // $params = $req->params;
        // if($params && Auth::user()->role == 2) {
        //     $model->where('sales_id', $params['id']);
        // }

        return DataTables::eloquent($model)->toJson();
    }

    public function store(Request $req)
    {
        $invoice = Invoice::create([
            'stores_id' => $req->stores_id,
            'sales_id' => $req->sales_id,
            'delivery_orders_id' => $req->delivery_orders_id,
        ]);

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
