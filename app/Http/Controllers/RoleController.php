<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use Session;
use DB;

class roleController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(AdminRequest $request)
    {
        return view('admin/role');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(AdminRequest $request)
    {
        return view('admin/role')->withCreate('1');
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

        // Create the role first

        $input = $request->only('name', 'display_name', 'description');

        $role = \App\Role::create($input);

        // Create the permissions

        $input = $request->only('permission');
        $i = $input['permission'];

        if ($i)
        {
            $permissions = \App\Permission::all()->lists('id')->toArray();
            foreach($permissions as $permission)
            {
                //As there is no good function to check if a role has a certain permission this is the workaround
                $in_db =DB::table('permission_role')->where('permission_id', '=', $permission)
                                                    ->where('role_id', '=', $role->id)
                                                    ->count();

                if (in_array($permission, $i) && !$in_db) {
                        $role->attachPermission($permission);
                }
                elseif (!in_array($permission, $i) && $in_db){
                        $role->detachPermission($permission);
                }
            }
        }
        
        Session::flash('flash_message', 'Rolle hinzugefügt!');

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
        $formrole = \App\Role::findOrFail($id);

        return view('admin/role')->withFormrole($formrole);
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
        
        //Save the role details
        $role = \App\Role::findOrFail($id);

        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $input = $request->only('name', 'display_name', 'description');
    
        $role->fill($input)->save();

        // Save the permissions

        $input = $request->only('permission');
        $i = $input['permission'];

        if ($i)
        {
            $permissions = \App\Permission::all()->lists('id')->toArray();
            foreach($permissions as $permission)
            {
                //As there is no good function to check if a role has a certain permission this is the workaround
                $in_db =DB::table('permission_role')->where('permission_id', '=', $permission)
                                                    ->where('role_id', '=', $role->id)
                                                    ->count();
    
                if (in_array($permission, $i) && !$in_db) {
                        $role->attachPermission($permission);
                }
                elseif (!in_array($permission, $i) && $in_db){
                        $role->detachPermission($permission);
                }
            }
        }
        
        Session::flash('flash_message', 'Rolle bearbeitet!');

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
