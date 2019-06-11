<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resetPassword(Request $req)
    {
        $newpass = Str::random(8);

        $user = User::where('email', $req->email);
        $user->update([
            'password' => bcrypt($newpass),
        ]);

        if(count($user->get())) {
            return redirect()
                ->route('password.request')
                ->with([
                    'status' => 'Your new password',
                    'newpass' => $newpass
                ]);
        }
        else {
            return redirect()
                ->route('password.request')
                ->with([
                    'status' => 'E-Mail not found'
                ]);
        }
    }
}
