<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    //use AuthenticatesAndRegistersUsers, ThrottlesLogins;

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
