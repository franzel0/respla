<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\SectionRequest;

use App\Http\Controllers\Controller;
use Session;
use Auth;
use Redirect;
use App\Classes\lists;

class SectionController extends Controller
{
    
    

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SectionRequest $request, $company_id, $department_id)
    {   
        
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
            
        //return ($department_id."/".Lists::firstdepartment_id($company_id));
        $sections = Lists::sections($department_id);
        //\App\Company::find($company_id)->departments()->find($department_id)->sections();

        return view('admin/section', compact('company_list', 'company_id', 'department_list', 'department_id', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(SectionRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        
        $sections = Lists::sections($department_id);
        
        return view('admin/section', compact('company_list', 'company_id', 'department_list', 'department_id', 'sections'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SectionRequest $request, $company_id, $department_id )
    {
        
        $this->validate($request, [
            'shortname' => 'required',
            'fullname' => 'required',
        ]);

        $input = $request->all();

        $section = new \App\Section($input);
        
        $section->save();

        Session::flash('flash_message', 'section hinzugefügt!');

        //get the data to fill lists & dropdowns
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $sections = Lists::sections($department_id);
        
        return view('admin/section', compact('company_list', 'company_id', 'department_list', 'department_id', 'sections'));
        
        //return ($input['department_id']);
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
    public function edit(SectionRequest $request, $company_id, $department_id, $position_id)
    {
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        
        $sections = Lists::sections($department_id);

        $section = \App\section::findOrFail($position_id);

        return view('admin/section', compact('company_list', 'company_id', 'department_list', 'department_id', 'sections', 'section'));        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SectionRequest $request, $company_id, $department_id, $position_id)
    {
        $this->validate($request, [
            'fullname' => 'required',
            'shortname' => 'required',
        ]);
        
        $section = \App\Section::findOrFail($position_id);

        $section->fill($request->all());

        $section->save();
        
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $sections = Lists::sections($department_id);

        Session::flash('flash_message', 'section geändert!');

        return view('admin/section', compact('company_list', 'company_id', 'department_list', 'department_id', 'sections', 'section'));
        
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
    
    public function getdepartmentlist(SectionRequest $request)
    {
        $company_id = $request->input('company_id');

        $departments = Lists::departmenets($company_id);
        
        $html='<option value=0>Abteilung auswählen</option>';
        
        foreach($departments as $department)
        {
            $id = $department->id;
            $name = $department->name;
            $html .= "<option value=$id>$name</option>";
        }

        echo $html;
    }
}
