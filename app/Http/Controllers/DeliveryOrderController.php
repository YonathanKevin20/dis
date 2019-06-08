<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        return view('pages.delivery-order.index');
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
