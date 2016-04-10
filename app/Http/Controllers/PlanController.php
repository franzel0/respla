<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;
use App\Http\Requests\PlanRequest;
use App\Classes\lists;
use App;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entry = $request->entry;

        if (!$request->has('month')){
            $startofmonth = Carbon::now()->startofmonth();
        }
        else{
            $month = $request->input('month');
            $year = $request->input('year');
            $startofmonth = Carbon::createFromFormat('Y-m-d', date($year.'-'.$month.'-1'));
        }
        if (!$request->has('position'))
        {
            $position = null;
            $filterByPosition = '';
            $users = \App\Department::find(Auth::user()->department_id)->users()
                    ->orderBy('name', 'asc')->get();
        }
        else
        {
            $position = $request->position;
            $filterByPosition = ' where position_id = '.$position." ";
            $users = \App\Department::find(Auth::user()->department_id)->users()
                    ->where('position_id', '=', $position)
                    ->orderBy('name', 'asc')
                    ->get();
        }

        $endofmonth = clone $startofmonth;
        $endofmonth->endOfMonth();

        $events = \App\Department::find(Auth::user()->department_id)->events()
                ->whereBetween('events.date', [$startofmonth->toDateString(), $endofmonth->toDateString()])
                ->select('*', 'users.lastname as lastname', 'events.id as eventid')
                ->get();//toSql(); return $events;//get();

        $entries = \App\Department::find(Auth::user()->department_id)->entries()->where('wish', '=', 1)->get()->sortBy('name');

        $positionList  = [null =>'alle'] + Lists::positionlist(Auth::user()->department_id);

        if (!$request->has('entry')){
            $entry = $entries->first()->id;
        } 
        //return print_r($entries);

        $styles = \App\Entry::where('department_id', '=', Auth::user()->department_id)
                  ->orderBy('name', 'asc')
                  ->get();

        $customdate = \App\Company::find(Auth::user()->department->company->id)->customdatesitems()
                    ->leftJoin('custom_dates', 'custom_dates_items.id', '=', 'custom_dates.items_id')
                    ->whereBetween('custom_dates.date', [$startofmonth->toDateString(), $endofmonth->toDateString()])
                    ->select('custom_dates.date')
                    ->get('date')
                    ->toArray();
        $customdates = array_flatten($customdate, 'date');
        $cus = implode(",", $customdates);
        if(count($customdate)>0)
        {
          $string = ', if( date in("'.$cus.'"), "holiday", "") as holiday';
        }
        else
        {
          $string = ', "" as holiday';
        }

        $stats = \App\Department::find(Auth::user()->department_id)->events()->whereBetween('events.date', array($startofmonth->toDateString(), $endofmonth->toDateString()))
              ->where('events.entry_id', '=', $entry)
              ->dayType(Auth::user()->company->id)
              ->selectRaw('events.user_id as userid, events.date as edate, count(if(shortname<>"", 8, dayofweek(events.date))) as numberdays, if(shortname<>"", 8, dayofweek(events.date)) as weekday')
              ->groupBy('events.user_id')
              ->groupBy('weekday')
              ->orderBy('userid')
              ->orderBy('weekday')
              ->get();

        return view('plan', compact('entry', 'entries', 'position', 'positionList', 'startofmonth', 'endofmonth', 'events', 'users', 'styles', 'customdates', 'stats'));
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
     * Insert a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insertPlanEvent(PlanRequest $request)
    { 

        //First create a new event
        $old_event = \App\Event::where('user_id', '=', $request->user_id)->where('date', '=', $request->old_date);
        $event = \App\Event::firstOrNew(['user_id' => $request->user_id, 'date' => $request->new_date]);

        //Check if entry can be on weekend resp. customdate
        $entry = \App\Entry::find($request->entry_id);
        $day = new Carbon($request->new_date);
        $r =  ($day->isWeekend()) ? 1: 0;
        $custom_dates = Lists::customdatesinmonth($day, $request->user_id)->toArray();
        if( ($entry->onweekend == 0 && (in_array($request->new_date, $custom_dates))) || ($entry->onweekend == 0 && $r == 1) ) // strange?
        {
            // Not implemented
            App::abort(501, "Weekend");
        }
        
        //if there is an old event which was dragged delete it
        if($old_event->count() > 0){
            $old_event->delete();   
        }        

        $event->user_id = $request->user_id;
        $event->date = $request->new_date;
        $event->entry_id = $request->entry_id;
        $event->approved = 1;
        $event->save();
        
        //make a new table of events for saved user ($('#user{id}'))
        $u = \App\User::find($request->user_id);
        $startofmonth = new Carbon($request->new_date);
        $startofmonth->startOfMonth();
        $endofmonth = new Carbon($request->new_date);
        $endofmonth->endOfMonth();
        $events = \App\Event::where("user_id", "=", $u->id)
                ->whereBetween('events.date', [$startofmonth->toDateString(), $endofmonth->toDateString()])
                ->get();//toSql();
        $html = "";
        $html .=   "";
        for ($day = clone $startofmonth; $day <= $endofmonth; $day->addDay()){
            $html .= "<tr>";
            $ev = $events->filter(function($item) use ($day, $u) { if($item->date == $day->toDateString()&& $item->user_id == $u->id) return true;});
            if ($ev->count()>0){
                $html .= "<td class='item".$ev->first()->entry->id."'>".$ev->first()->entry->name."</td>";
            }
            $html .= "</tr>";      
        }
        return($html);
    }

    /**
     * update stats
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStats(PlanRequest $request)
    {
        $weekday = array(2 => "Montag",
                         3 => "Dienstag",
                         4 => "Mittwoch",
                         5 => "Donnerstag",
                         6 => "Freitag",
                         7 => "Samstag",
                         1 => "Sonntag",
                         8 => "Feiertag");

        $u = \App\User::find($request->user_id);
        $startofmonth = new Carbon($request->new_date);
        $startofmonth->startOfMonth();
        $endofmonth = new Carbon($request->new_date);
        $endofmonth->endOfMonth();

        $stats = \App\User::find($u->id)->events()->whereBetween('events.date', array($startofmonth->toDateString(), $endofmonth->toDateString()))
              ->where('events.entry_id', '=', $request->entry_id)
              ->dayType($u->company->id)
              ->selectRaw('events.user_id as userid, events.date as edate, count(if(shortname<>"", 8, dayofweek(events.date))) as numberdays, if(shortname<>"", 8, dayofweek(events.date)) as weekday')
              ->groupBy('events.user_id')
              ->groupBy('weekday')
              ->orderBy('userid')
              ->orderBy('weekday')
              ->get();
        $html = "<tr>
                 <th colspan=2>Statistik</th>
                 <tr>
                 <th>Tag</th>
                 <th>Anzahl</th>
                 </tr>";
        foreach($stats as $s)
        {
            $html .=    "<tr><td>".$weekday[$s->weekday]."</td><td>".$s->numberdays."</td></tr>";
        }

        return($html);

    }

    /**
     * Remove the specified resource from storage.     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function deletePlanEvent(PlanRequest $request)
    {
        $event = \App\Event::firstOrNew(['date' => $request->new_date, 'user_id' => $request->user_id]);
        $event->delete();

        //make a new table of events for saved user ($('#user{id}'))
        $u = \App\User::find($request->user_id);
        $startofmonth = new Carbon($request->new_date);
        $startofmonth->startOfMonth();
        $endofmonth = new Carbon($request->new_date);
        $endofmonth->endOfMonth();
        $events = \App\Event::where("user_id", "=", $u->id)
                ->whereBetween('events.date', [$startofmonth->toDateString(), $endofmonth->toDateString()])
                ->get();//toSql();
        $html = "";
        $html .=   "<tr>
                    <th>Termine von".$u->firstname." ".$u->lastname."</th>
                    </tr>";
        for ($day = clone $startofmonth; $day <= $endofmonth; $day->addDay()){
            $html .= "<tr>";
            $ev = $events->filter(function($item) use ($day, $u) { if($item->date == $day->toDateString()&& $item->user_id == $u->id) return true;});
            if ($ev->count()>0){
                $html .= "<td class='item".$ev->first()->entry->id."'>".$ev->first()->entry->name."</td>";
            }
            $html .= "</tr>";      
        }
        return($html);
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

}
