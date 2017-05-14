<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Carbon;

class OnCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id)
    {
        return view('admin/oncall', compact('company_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company_id)
    {
        return view('admin/oncall', compact('company_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company_id)
    {
        $oncall =  new \App\Entry($request->only(['name', 'phone', 'right'. 'present', 'onweekend', 'isvisible']));
        \App\Company::find($company_id)->entries()->save($oncall);

        Session::flash('flash_message', 'Dienstgruppe hinzugefügt!');

        return view('admin/oncall', compact('oncall', 'company_id'));
    }

    /**
     * Display the oncalls for reordering.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($company_id)
    {
        $departments = \App\Company::find(Auth::user()->company->id)->departments()->lists('id');
        $oncallsvisible =\App\Entry::where('isvisible', '=', 1)
                                     ->where('isactive', '=', 1)
                                     ->where(function($q) use($departments, $company_id) {
                                        $q->whereIn('department_id', $departments)
                                          ->orWhere('entries.company_id', '=', $company_id);
                                      })
                                      ->leftJoin('companies', 'company_id', '=', 'companies.id')
                                      ->leftJoin('departments', 'department_id', '=', 'departments.id')
                                      ->select('entries.*', 'companies.name as companyname', 'departments.name as departmentname')
                                      ->orderBy('sort')
                                      ->get(); //dd($departments."/".$oncallsvisible);//return $oncallsvisible;
        $oncallsall = \App\Entry::where('isvisible', '<>', 1)
                                      ->where('isactive', '=', 1)
                                      ->where(function($q) use($departments, $company_id) {
                                          $q->whereIn('department_id', $departments)
                                          ->orWhere('entries.company_id', '=', $company_id);
                                      })
                                      ->leftJoin('companies', 'company_id', '=', 'companies.id')
                                      ->leftJoin('departments', 'department_id', '=', 'departments.id')
                                      ->select('entries.*', 'companies.name as companyname', 'departments.name as departmentname')
                                      ->orderBy('companyname')
                                      ->orderBY('departmentname')
                                      ->get();
        return view('admin/orderoncalls', compact('oncallsvisible', 'oncallsall'));
    }

    /**
     * Show the form for editing the specified resource.This is for managing the oncalls!!!!!!!!
     *
     * @param  int  $company_id
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id, $oncall_id)
    {
        $oncall = \App\Entry::find($oncall_id);

        return view('admin/oncall', compact('oncall', 'company_id'));
    }

    /**
     * Update the specified resource in storage. Update the oncall
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_id, $oncall_id)
    { //return $request->all();
        //still to be validated
        $oncall =\App\Entry::find($oncall_id);
        //$oncall->save([$request->all()]);
        $oncall->name = $request->name;
        $oncall->phone = $request->phone;
        $oncall->shorttext = $request->shorttext;
        $oncall->bgcolor = $request->bgcolor;
        $oncall->present = $request->input('present', 0);
        $oncall->right = $request->input('right', 0);
        $oncall->onweekend = $request->input('onweekend', 0);
        $oncall->isvisible = $request->input('isvisible', 0);
        $oncall->isactive = $request->input('isactive', 0);
        $oncall->save();

        return view('admin/oncall', compact('oncall', 'company_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeOncallOrder(Request $request)
    {
        if($request->ajax()) {
            $entries = $request->entries;
            $i = 1;
            foreach($entries as $e)
            {
                $entry = \App\Entry::find($e);
                $entry->sort = $i;
                $entry->isvisible = $request->isvisible;
                $entry->save();
                $i ++;
            }
        }
    }
}
