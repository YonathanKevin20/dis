<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use DataTables;

class StoreController extends Controller
{
    public function getData(Request $req)
    {
        $model = Store::orderBy('name')->get();

        return response()->json($model); 
    }

    public function getDatatables(Request $req)
    {
        $model = Store::query();

        return DataTables::eloquent($model)->toJson();
    }

    public function index()
    {
        return view('pages.store.index');
    }

    public function store(Request $req)
    {
        Store::create([
            'name' => $req->name,
            'location' => $req->location,
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $req, $id)
    {
        $model = Store::findOrFail($id);
        $model->update([
            'name' => $req->name,
            'location' => $req->location,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $model = Store::findOrFail($id);
        $model->delete();
    
        return response()->json(['success' => true]);
    }
}
