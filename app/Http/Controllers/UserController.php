<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Classes\lists;
use Session;
use Auth;   

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(UserRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $position_list = Lists::positions($department_id)->sortBy('name')->lists('name', 'id');
        
        $section_list = Lists::sections($department_id)->lists('fullname', 'id');
        
        $users = Lists::users($department_id);

        $roles = Lists::roles();

        $userrole = null;
//return("hi");
        return view('admin/user', compact('company_list', 'company_id', 'department_list', 'department_id', 'position_list', 'section_list', 'roles', 'userrole', 'users' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(UserRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $position_list = Lists::positions($department_id)->sortBy('name')->lists('name', 'id');
        
        $section_list = Lists::sections($department_id)->lists('fullname', 'id');
        
        $users = Lists::users($department_id);

        $roles = Lists::roles();
        
        $userrole = null;
        
        return view('admin/user', compact('company_list', 'company_id', 'department_list', 'department_id', 'position_list', 'section_list', 'roles', 'userrole', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(UserRequest $request, $company_id, $department_id)
    {
        $this->validate($request, [
            'lastname' => 'required',
            'firstname' => 'required',
            'position_id' =>'required|integer',
            'email' => 'required|email|unique:users',
            'name' => 'required|unique:users',
            'section_id' =>'required|integer',
            'role_id' => 'required',
        ]);

        $input = $request->all();

        $user = new \App\User($input);

        if ($request->has('active')) {
            $user->active = true;
        }
        else{
            $user->active = false;   
        }
        $user->password = bcrypt($user->password);
        $user->confirmed = 1;

        $user->save();

        $ownrole = new \App\Role_user();
        $ownrole->role_id = $request->input('role_id');

        $user->ownrole()->save($ownrole);

        Session::flash('flash_message', 'Mitarbeiter hinzugefügt!');

        //get the data to fill lists & dropdowns
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $position_list = Lists::positions($department_id)->sortBy('name')->lists('name', 'id');

        $section_list = Lists::sections($department_id)->lists('fullname', 'id');

        $roles = Lists::roles();
        
        $users = Lists::users($department_id);
        
        $userrole = null;

        return view('admin/user', compact('company_list', 'company_id', 'department_list', 'department_id', 'position_list', 'section_list', 'roles', 'userrole', 'users'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(UserRequest $request, $company_id, $department_id, $user_id)
    {   
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $position_list = Lists::positions($department_id)->sortBy('name')->lists('name', 'id');

        $section_list = Lists::sections($department_id)->lists('fullname', 'id');

        $roles = Lists::roles();
        
        $users = Lists::users($department_id);

        $user = \App\User::findOrFail($user_id);

        $userrole = $user->ownrole->role_id;
        
        return view('admin/user', compact('company_list', 'company_id', 'department_list', 'department_id', 'position_list', 'section_list', 'roles', 'users', 'userrole', 'user')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UserRequest $request, $company_id, $department_id, $user_id)
    { 
        $this->validate($request, [
            'lastname' => 'required',
            'firstname' =>'required',
            'position_id' =>'required|integer',
            'email' => 'required',
            'name' => 'required',
            'position_id' =>'required|integer',
            'role_id' => 'required|integer',
        ]);
        
        $user = \App\user::findOrFail($user_id);

        $user->fill($request->all());

        // set old role_id if not provided
        if (Auth::user()->can('changeroles') || Auth::user()->can('changecompanyroles') || Auth::user()->can('changedepartmentroles'))
        {
            $user->ownrole->role_id = $request->input('role_id'); 
        }
        else
        {
            $user->ownrole->role_id = Auth::user()->ownrole->role_id;
        }

        if ($request->has('active')) {
            $user->active = true;
        }
        else{
            $user->active = false;   
        }   

        $user->push();


        //Lists
        
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $position_list = Lists::positions($department_id)->sortBy('name')->lists('name', 'id');

        $section_list = Lists::sections($department_id)->lists('fullname', 'id');

        $roles = Lists::roles();
        
        $users = Lists::users($department_id);

        $userrole = $user->ownrole->role_id;

        Session::flash('flash_message', 'Mitarbeiter geändert!');

        //return ($request->position_id);
        
        return view('admin/user', compact('company_list', 'company_id', 'department_list', 'department_id', 'position_list', 'section_list', 'roles', 'users', 'userrole', 'user'));
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
