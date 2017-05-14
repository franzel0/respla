<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CompanyAdminRequest;
use App\Http\Controllers\Controller;
use App\Classes\lists;
use Session;

class DepartmentController extends Controller
{
      

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(CompanyAdminRequest $request, $company_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name');

        //if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        
        return view('admin/department', compact('company_list', 'company_id', 'department_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(CompanyAdminRequest $request, $company_id)
    {
        $company_list = Lists::companies();

        $department_list = \App\Company::find($company_id)->departments->sortBy('name');

        $create=1;

        return view('admin/department', compact('company_id', 'company_list', 'department_list', 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CompanyAdminRequest $request, $company_id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $input = $request->all();

        $company = \App\Company::find($request->company_id);

        $newdepartment = new \App\Department($input);
        
        if ($request->has('active')) {
            $newdepartment->active = true;
        }
        else{
            $newdepartment->active = false;   
        }

        $newdepartment->company_id = $company_id;

        $newdepartment->save();      
        

        Session::flash('flash_message', 'Abteilung hinzugefügt!');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(CompanyAdminRequest $request, $company_id, $department_id)
    {
        $department = \App\Company::find($company_id)->Departments()->find($department_id);

        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name');

        return view('admin/department', compact('department', 'company_id', 'department_id', 'company_list', 'department_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CompanyAdminRequest $request, $company_id, $department_id)
    {
        $department = \App\Department::findOrFail($department_id);

        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $input = $request->all();
    
        $department->fill($input);

        if ($request->has('active')) {
            $department->active = true;
        }
        else{
            $department->active = false;   
        }

        $department->save();

        Session::flash('flash_message', 'Abteilung geändert!');

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
