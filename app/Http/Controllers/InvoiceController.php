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
        $model = Invoice::with(['store', 'sales']);

        // $params = $req->params;
        // if($params && Auth::user()->role == 2) {
        //     $model->where('sales_id', $params['id']);
        // }

        return DataTables::eloquent($model)->toJson();
    }

    public function store(Request $req)
    {
        //
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
