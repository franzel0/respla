<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AdminRequest;

use App\Http\Controllers\Controller;

use Session;

class PermissionController extends Controller
{
    
    /*
    * Inject AdminRequest to authorize user
    *
    private $request;

    public function __construct(AdminRequest $request)
    {
        $this->request = $request;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(AdminRequest $request)
    {
        return view('admin/permission');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(AdminRequest $request)
    {
        return view('admin/permission')->withCreate('1');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AdminRequest $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        /*$input = $request->all();

        \App\Permission::create($input);*/

        $permission = new \App\Permission();
        $permission->name = $request->input('name');
        $permission->display_name = $request->input('display_name');
        $permission->description = $request->input('description');
        $permission->save();

        Session::flash('flash_message', 'Recht hinzugefügt!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(AdminRequest $request, $id)
    {
        $formpermission = \App\Permission::findOrFail($id);

        return view('admin/permission')->withFormpermission($formpermission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AdminRequest $request, $id)
    {
        $permission = \App\Permission::findOrFail($id);

        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $input = $request->all();
    
        $permission->fill($input)->save();

        Session::flash('flash_message', 'Recht geändert!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
