<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;
use App\Http\Requests\StatsRequest;
use App\Classes\lists;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StatsRequest $request)
    {
        //Generate date
        if (!$request->has('month')){
            $startofmonth = Carbon::now()->startofmonth();
            $year = $startofmonth->year;
        }
        else{
            $month = $request->input('month');
            $year = $request->input('year');
            $startofmonth = Carbon::createFromFormat('Y-m-d', date($year.'-'.$month.'-1'));
        }
            
        if ($request->has('back'))
        {
            $startofmonth->subMonth();
        }
        if ($request->has('next'))
        {
            $startofmonth->addMonth();
        }
        if ($request->has('this'))
        {
            $startofmonth = Carbon::now()->startofmonth();
        }
        
        $endofmonth = clone $startofmonth;
        $endofmonth->endOfMonth();
        $department_id=Auth::user()->department_id;
        $company_id = Auth::user()->company->id;

        $entries = \App\Department::find(Auth::user()->department_id)->entries;

        $eventsInMonth = Auth::user()->events()->whereBetween('events.date', array($startofmonth->toDateString(), $endofmonth->toDateString()))
              ->distinct()  
              ->dayType(Auth::user()->company->id)
              ->selectRaw('events.entry_id as entry_id, events.date as edate, count(if(shortname<>"", 8, dayofweek(events.date))) as numberdays, if(shortname<>"", 8, dayofweek(events.date)) as weekday')
              ->groupBy('weekday')
              ->groupBy('events.entry_id')
              ->orderBy('weekday')
              ->get();

        $eventsInYear = Auth::user()->events()->whereYear('events.date', '=', $year)
              ->distinct()  
              ->dayType(Auth::user()->company->id)
              ->selectRaw('events.entry_id as entry_id, events.date as edate, count(if(shortname<>"", 8, dayofweek(events.date))) as numberdays, if(shortname<>"", 8, dayofweek(events.date)) as weekday')
              ->groupBy('weekday')
              ->groupBy('events.entry_id')
              ->orderBy('weekday')
              ->get();
        
        $eventsTotal = Auth::user()->events()
              ->distinct()  
              ->dayType(Auth::user()->company->id)
              ->selectRaw('events.entry_id as entry_id, events.date as edate, count(if(shortname<>"", 8, dayofweek(events.date))) as numberdays, if(shortname<>"", 8, dayofweek(events.date)) as weekday')
              ->groupBy('weekday')
              ->groupBy('events.entry_id')
              ->orderBy('weekday')
              ->get();

        
                            
        return view('stats', compact('startofmonth', 'endofmonth', 'entries', 'eventsInMonth', 'eventsInYear', 'eventsTotal'));
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
