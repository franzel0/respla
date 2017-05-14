<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon;

class DutyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id)
    {
        $duties = \App\Company::find($company_id)->oncalls->sortBy('order');

        $startOfMonth = new Carbon();
        $startOfMonth->startOfMonth();
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->endOfMonth();

        //$customdates = \App\Company::find($company_id)->dayType($startOfMonth, $endOfMonth);
        $customdates = \App\Company::find($company_id)->customdatesitems()
                                              ->leftJoin('custom_dates', 'custom_dates.items_id', '=', 'companies_customdatesitems.customdatesitems_id')
                                              ->whereBetween('date', ['2016-01-01', '2016-12-31'])
                                              ->selectRaw('custom_dates.date as date, custom_dates.name as name, 8 as type')
                                              ->get();
                                              //->toSql(); return $customdates;

        $customdates = \App\Company::find($company_id)->dayType($startOfMonth, $endOfMonth);
        return view('duties', compact('duties', 'startOfMonth', 'endOfMonth', 'customdates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
