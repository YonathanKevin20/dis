<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportTarget;
use DataTables;

class ImportTargetController extends Controller
{
    public function getDatatables(Request $req)
    {
        $model = ImportTarget::with(['product'])->select('import_targets.*');

        return DataTables::eloquent($model)->toJson();
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
