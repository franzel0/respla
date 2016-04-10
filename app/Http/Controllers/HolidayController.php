<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\HolidayRequest;
use App\Http\Controllers\Controller;
use App\Classes\lists;
use Session;
use Auth;   
use Carbon;


class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(HolidayRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $holiday_list = Lists::holidays($department_id);
        return view('admin/holiday', compact('company_list', 'company_id', 'department_list', 'department_id', 'holiday_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(HolidayRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $holiday_list = Lists::holidays($department_id);
        
        return view('admin/holiday', compact('company_list', 'company_id', 'department_list', 'department_id', 'holiday_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(HolidayRequest $request, $company_id, $department_id)
    {
        $this->validate($request, [
            'name' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        $input = $request->all();
        
        $holiday = new \App\Holiday($input);
        
        $holiday->date_from = date('Y-m-d',strtotime($request->date_from));

        $holiday->date_to = date('Y-m-d', strtotime($request->date_to));

        $holiday->save();

        Session::flash('flash_message', 'Ferien hinzugefügt!');

        //get the data to fill lists & dropdowns
        $company_list = Lists::companies();
        $department_list = Lists::departments($company_id);
        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        $holiday_list = Lists::holidays($department_id);
        
        return view('admin/holiday', compact('company_list', 'company_id', 'department_list', 'department_id', 'holiday_list', 'holiday'));
    

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
    public function edit(HolidayRequest $request, $company_id, $department_id, $holiday_id)
    {
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $holiday_list = Lists::holidays($department_id);

        $holiday = \App\Holiday::findOrFail($holiday_id);

        return view('admin/holiday', compact('company_list', 'company_id', 'department_list', 'department_id', 'holiday_list', 'holiday'));        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(HolidayRequest $request, $company_id, $department_id, $holiday_id)
    {
        $this->validate($request, [
            'name' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);   

        $holiday = \App\Holiday::findOrFail($holiday_id);
        
        $holiday->date_from = date('Y-m-d',strtotime($request->date_from));

        $holiday->date_to = date('Y-m-d', strtotime($request->date_to));

        $holiday->name = $request->name;
        
        $holiday->save();
        
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);
        
        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        
        $holiday_list = Lists::holidays($department_id);
        
        return view('admin/holiday', compact('company_list', 'company_id', 'department_list', 'department_id', 'holiday_list', 'holiday'));        
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
