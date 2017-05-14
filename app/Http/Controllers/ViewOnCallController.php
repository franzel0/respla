<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon;
use DB;

class ViewOnCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_id)
    {
        //Generate and navigate date
        if ($request->has('month') && $request->has('year')){
            $month = $request->input('month');
            $year = $request->input('year');
            $startOfMonth = Carbon::createFromFormat('Y-m-d', date($year.'-'.$month.'-1'));
        }
        else{
            $startOfMonth = Carbon::now()->startofmonth();
        }
        if ($request->has('back'))
        {
            $startOfMonth->subMonth();
        }
        if ($request->has('next'))
        {
            $startOfMonth->addMonth();
        }
        if ($request->has('this'))
        {
            $startOfMonth = Carbon::now()->startofmonth();
        }
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->endOfMonth();
        $departments = \App\Company::find($company_id)
                                    ->departments()
                                    ->lists("departments.id"); //dd($departments);
        //company-wide- and department-entries which are visible
        $allentries = \App\Entry::where('isvisible', '=', 1)
                                ->where('isactive', '=', 1)
                                ->where(function($q) use($departments, $company_id) {
                                   $q->whereIn('department_id', $departments)
                                   ->orWhere('entries.company_id', '=', $company_id);
                                 })
                                ->orderBy('sort')->get();
        //get the events
        $events = \App\Event::whereIn('entry_id', $allentries->lists('id'))
                            ->leftJoin('users', 'users.id', '=', 'events.user_id')
                            ->where('approved', '=', 1)
                            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                            ->select(DB::raw('events.*, CONCAT(lastname, ", ", firstname) AS fullname, users.phone1 as phone'))
                            ->orderBy('users.lastname', 'asc')
                            ->get();

                            //dd($events);
        // get the customdates
        $customdates = \App\Company::find($company_id)->dayType($startOfMonth, $endOfMonth); //dd($customdates);
        return view('ViewOncalls', compact('allentries', 'startOfMonth', 'endOfMonth', 'customdates', 'company_id', 'events'));
    }

    /**
     * Show the users for modal 'change user'
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $entry = \App\Entry::find($request->entry_id);
        if ($entry->company_id>0)
        {
            $users = $entry->users;
        }
        else
        {
            $users = \App\Department::find($entry->department_id)->users;
        }
        $result ="";
        foreach ($users as $key => $u) {
            $selected = ($u->id == $request->user_id) ? 'selected' : '';
            $result .="<option value=$u->id $selected >$u->lastname, $u->firstname</option>";
        }
        return $result;
    }

    /*
     * Check if user has already an event for modal 'change user'
     *
     * @return \Illuminate\Http\Response
     */
    public function modalUserHasEvent(Request $request)
    {
        if($event = \App\Event::with('Entry')->where('date', '=', $request->date)->where('user_id', '=', $request->user_id)
                    ->leftJoin('entries', 'entries.id', '=', 'events.entry_id')
                    ->select('events.id as id', 'entries.name as name')
                    ->get()
                    ->first()) {
            return $event->toJson();
        }
        else {
            return json_encode(array('id' => 0));
        }
    }

    /*
     * Change the user
     *
     * @return \Illuminate\Http\Response
     */
    public function ChangeUser(Request $request)
    {
        if($request->oldevent_id > 0) {
            try {
                \App\Event::destroy($request->oldevent_id);
            } catch (Exception $e) {}
        }
        $event = \App\Event::findornew($request->event_id);
        $event->user_id = $request->user_id;
        $event->date = $request->date;
        $event->entry_id = $request->entry_id;
        $event->comment = $request->comment;
        $event->approved = 1;
        $event->save();
        return $event->toJson();
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
