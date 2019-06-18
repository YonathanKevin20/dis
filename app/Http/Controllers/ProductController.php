<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ImportTarget;
use App\Imports\TargetsImport;
use DB;

class ProductController extends Controller
{
    public function getData(Request $req)
    {
        $model = Product::all();

        return response()->json($model); 
    }

    public function getChart(Request $req)
    {
        $label = InvoiceProduct::with(['product'])->select('products_id')->groupBy('products_id')->orderBy('products_id')->get()->pluck('product.name');

        $data1 = InvoiceProduct::orderBy('products_id')->get()->groupBy('products_id')->map(function($row) {
            return $row->sum('qty');
        })->values();

        $data2 = ImportTarget::orderBy('products_id')->get()->groupBy('products_id')->map(function($row) {
            return $row->sum('qty');
        })->values();

        $result = [
            'label' => $label,
            'data1' => $data1,
            'data2' => $data2,
        ];

        return response()->json($result);
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

    public function importForm()
    {
        return view('pages.product.import');
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
