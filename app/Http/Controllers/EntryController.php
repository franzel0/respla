<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\EntryRequest;
use App\Http\Controllers\Controller;
use App\Classes\lists;
use Session;
use Auth;   


class EntryController extends Controller
{
    
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(EntryRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $entry_list = Lists::entries($department_id);
        
        return view('admin/entry', compact('company_list', 'company_id', 'department_list', 'department_id', 'entry_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(EntryRequest $request, $company_id, $department_id)
    {
        $company_list = Lists::companies();
        
        $department_list = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $entry_list = Lists::entries($department_id);
        
        return view('admin/entry', compact('company_list', 'company_id', 'department_list', 'department_id', 'entry_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(EntryRequest $request, $company_id, $department_id)
    {
        $this->validate($request, [
            'name' => 'required',
            'shorttext' => 'required',
            'bgcolor' => 'required',
            'textcolor' =>'required',
        ]);

        $input = $request->all();

        //return $request->input('textcolor');

        $entry = new \App\Entry($input);

        $entry->wish = ($request->has('wish')) ? true : false;

        $entry->present = ($request->has('present')) ? true : false;
        
        $entry->right = ($request->has('right')) ? true : false;

        $entry->onweekend = ($request->has('onweekend')) ? true : false;

        $entry->save();

        Session::flash('flash_message', 'Eintrag hinzugefügt!');

        //get the data to fill lists & dropdowns
        $company_list = Lists::companies();
        $department_list = Lists::departments($company_id);
        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        $entry_list = Lists::entries($department_id);
        
        return view('admin/entry', compact('company_list', 'company_id', 'department_list', 'department_id', 'entry_list', 'entry'));
    

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
    public function edit(EntryRequest $request, $company_id, $department_id, $entry_id)
    {
        $company_list = Lists::companies();

        $department_list = Lists::departments($company_id);

        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);

        $entry_list = Lists::entries($department_id);

        $entry = \App\Entry::findOrFail($entry_id); 

        return view('admin/entry', compact('company_list', 'company_id', 'department_list', 'department_id', 'entry_list', 'entry'));        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(EntryRequest $request, $company_id, $department_id, $entry_id)
    {
        $this->validate($request, [
            'name' => 'required',
            'shorttext' => 'required',
            'bgcolor' => 'required',
            'textcolor' =>'required',
        ]);

        $entry = \App\Entry::findOrFail($entry_id);

        $entry->fill($request->all());

        $entry->wish = ($request->has('wish')) ? true : false;

        $entry->present = ($request->has('present')) ? true : false;
        
        $entry->right = ($request->has('right')) ? true : false;

        $entry->onweekend = ($request->has('onweekend')) ? true : false;

        $entry->save();
        
        $company_list = Lists::companies();
        
        $department_list = Lists::departments($company_id);
        
        if($department_id == 0) $department_id = Lists::firstdepartment_id($company_id);
        
        $entry_list = Lists::entries($department_id);
        
        return view('admin/entry', compact('company_list', 'company_id', 'department_list', 'department_id', 'entry_list', 'entry'));        
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
