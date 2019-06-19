<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportTarget;
use App\Imports\TargetsImport;
use DataTables;
use DB;

class ImportTargetController extends Controller
{
    public function getDatatables(Request $req)
    {
        $model = ImportTarget::with(['product'])->select('import_targets.*');

        return DataTables::eloquent($model)->toJson();
    }

    public function index()
    {
        return view('pages.import-target.index');
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

    public function import(Request $req)
    {
        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if($req->file) {
            DB::beginTransaction();
            $data = new TargetsImport();
            try {
                switch($ext) {
                    case 'xlsx':
                        $data->import($req->file, null, \Maatwebsite\Excel\Excel::XLSX);
                        break;
                }                
                DB::commit();
                return response()->json(['success' => true]);
            } catch(\Illuminate\Database\QueryException $ex) {
                DB::rollback();
                return response()->json([
                    'message' => 'Terjadi kesalahan pada database',
                    'console' => $ex->getMessage(),
                ]);
            }
        }
        else {
            return back();
        }
    }

    public function downloadTemplateXlsx()
    {
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        $file = 'import_target_xlsx';
        $ext = '.xlsx';
        return response()->download(storage_path('app/template/'.$file.$ext), $file.$ext, $headers);
    }
}
