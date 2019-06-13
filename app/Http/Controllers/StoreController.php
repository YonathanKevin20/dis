<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function getData(Request $req)
    {
        $model = Store::all();

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
