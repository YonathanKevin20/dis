<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Models\DeliveryOrder;
use Auth;

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

    public function changePassword(Request $req)
    {
        $req->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail(Auth::user()->id);

        if(Hash::check($req->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($req->new_password),
            ]);
            return redirect()
                ->back()
                ->with([
                    'status' => 'Your password is changed',
                ]);
        }
        else {
            return redirect()
                ->back()
                ->with([
                    'status' => 'Your old password is different',
                ]);
        }
    }

    public function avgTimeSales($sales_id)
    {
        return view('pages.user.view_avg_time_sales', compact('sales_id'));
    }

    public function showAvgTimeSales(Request $req)
    {
        $deliver_orders_id = $req->deliver_orders_id;

        $model = DeliveryOrder::with(['sales', 'invoice.invoiceProduct.product', 'invoice.store'])
            ->whereIn('id', $deliver_orders_id)
            ->get();

        return response()->json($model);
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
