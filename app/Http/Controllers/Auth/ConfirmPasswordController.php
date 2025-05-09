<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    protected function redirectTo() {        
        if (Auth::check() && Auth::user()->role) {
            $roleName = strtolower(Auth::user()->role->name);
    
            return match ($roleName) {
                'admin'     => route('admin.dashboard'),
                'sst'       => route('sst.dashboard'),
                'conductor' => route('conductor.dashboard'),
                default     => route('login'),
            };
        }
        return route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
