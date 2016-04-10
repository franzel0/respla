<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Auth

class AuthController extends Controller
{
    protected $username = 'name';

    protected $redirectPath = '/month';

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate()
    {
        if (Auth::attempt(['name' => $name, 'password' => $password])) {
            // Authentication passed...
            return redirect()->intended('month');
        }
    }
}
