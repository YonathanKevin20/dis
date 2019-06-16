<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use Auth;

class StatusController extends Controller
{
    public function getData(Request $req)
    {
        $model = DeliveryOrder::where('status', '0');

        if($req->sales_id && Auth::user()->role == 2) {
            $model->where('sales_id', $req->sales_id);
        }

        $model = $model->count('status');

        return response()->json($model); 
    }

    public function index()
    {
        //
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
