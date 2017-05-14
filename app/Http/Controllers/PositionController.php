<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PositionRequest;

use App\Http\Controllers\Controller;
use Session;
use Auth;
use Redirect;
use App\Classes\lists;

class PositionController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PositionRequest $request, $company_id, $department_id)
    {   
        
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $positions = Lists::positions($department_id);

        return view('admin/position', compact('company_list', 'company_id', 'department_list', 'department_id', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(PositionRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        
        $positions = Lists::positions($department_id);

        
        return view('admin/position', compact('company_list', 'company_id', 'department_list', 'department_id', 'positions'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PositionRequest $request, $company_id, $department_id )
    {
        
        $this->validate($request, [
            'name' => 'required',
        ]);

        $input = $request->all();

        $position = new \App\Position($input);
        
        if ($request->has('active')) {
            $position->active = true;
        }
        else{
            $position->active = false;   
        }

        //set last priority
        $priority = \App\Department::find($position->department_id)->positions->count() + 1;

        $position->priority = $priority;
        
        $position->save();

        Session::flash('flash_message', 'Position hinzugefügt!');

        //get the data to fill lists & dropdowns
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $positions = Lists::positions($department_id);
        
        return view('admin/position', compact('company_list', 'company_id', 'department_list', 'department_id', 'positions'));
        
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
    public function edit(PositionRequest $request, $company_id, $department_id, $position_id)
    {
        //$positions = "in:".\App\Department::find(Auth::user()->department_id)->positions()->get()->implode('id', ',');    
        
        /*$this->validate($request, [
            'position' => $positions,
        ]);*/
        
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $positions = Lists::positions($department_id);

        if (!$position = \App\Department::find($department_id)->positions()->find($position_id))
        {
            return('errors.forbidden');
        };

        return view('admin/position', compact('company_list', 'company_id', 'department_list', 'department_id', 'positions', 'position'));        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PositionRequest $request, $company_id, $department_id, $position_id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        
        $position = \App\Position::findOrFail($position_id);

        $position->fill($request->all());

        if ($request->has('active')) {
            $position->active = true;
        }
        else{
            $position->active = false;   
        }

        $position->save();
        
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $positions = Lists::positions($department_id);

        Session::flash('flash_message', 'Position geändert!');

        return view('admin/position', compact('company_list', 'company_id', 'department_list', 'department_id', 'positions', 'position'));
        
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
    public function changeOrder(PositionRequest $request)
    {
        /*$this->validate($request, [
            'company_id' => 'numeric|required',
            'department_id' => 'numeric|required'
        ]);*/

        if($request->ajax()) {
            $positions = $request->positions;
            $department_id = $request->department_id;
            $i = 1;
            foreach($positions as $position_id)
            {
                $position = \App\Position::find($position_id);
                $position->priority = $i;
                $position->save();
                $i ++;
            }
        }
    }

    public function getdepartmentlist(PositionRequest $request)
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
