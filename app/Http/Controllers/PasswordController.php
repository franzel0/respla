<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        return view('admin/password')->withUser('user');
    }

    /*
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $message = [
            'same' => 'Passwörter sind nicht identisch.',
        ];

        $this->validate($request, [
            'password' => 'required',
            'password2' => 'required|same:password',
        ], $message);
        
        $user = \App\user::findOrFail($id);

        $user->password = bcrypt($request->input('password'));

        $user->save();

        return back()->with('status', 'Passswort erfolgreich geändert! :)');
    }

}
