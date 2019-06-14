<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function getSpv(Request $req)
    {
        $model = User::where('role', 1)->get();

        return response()->json($model);
    }

    public function getSales(Request $req)
    {
        $model = User::where('role', 2)->get();

        return response()->json($model);
    }

    public function store(Request $req)
    {
        //
    }

    public function changePasswordForm()
    {
        return view('pages.user.change_password');
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
