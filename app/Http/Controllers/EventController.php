<?php

namespace App\Http\Controllers;

//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Request;
use Carbon\Carbon;
use Auth;
use Reponse;
use Input;
use App\Classes\lists;
use DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return ("hallo");
    }

    /**
     * Show events in a month
    */
    public function showMonth(Request $request)
    {

        //Generate date
        if (!$request->has('month')){
            $startofmonth = Carbon::now()->startofmonth();
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
        $position= $request->position;
        //return $request->next;

        $userlist = Lists::userlist($department_id);

        $entrylist = Lists::entrylist($department_id);

        If ($position == null)
        {
            $filterByPosition = '';
            $users = \App\User::where('department_id', '=', $department_id)
                    ->orderBy('name', 'asc')
                    ->get();
        }
        else
        {
            $filterByPosition = ' where position_id = '.$position." ";
            $users = \App\User::where('department_id', '=', $department_id)
                    ->where('position_id', '=', $position)
                    ->orderBy('name', 'asc')
                    ->get();
        }

        $positionList  = [null =>'alle'] + Lists::positionlist($department_id);

        $options = array();
        foreach($entrylist as $el)
        {
            $options[] = "<option class=' border-option item".$el->id."'' value='".$el->id."'>".$el->name."</option>";
        }
        //return($options);

        $styles = \App\Entry::where('department_id', '=', $department_id)
                  ->orderBy('name', 'asc')
                  ->get();

        $daysinmonth = Lists::daysinmonth($startofmonth, $endofmonth, Auth::user()->id);

        $events = Lists::monthevents($department_id, $company_id, $filterByPosition, $startofmonth);

        $numberdays = $startofmonth->daysInMonth;

        return view('month', compact('users', 'startofmonth', 'endofmonth', 'events', 'styles', 'holidays', 'events', 'daysinmonth', 'numberdays', 'userlist', 'options', 'entrylist', 'positionList', 'position'));

    }

    /**
     * Show events in a day
    */
    public function showDay(Request $request)
    {
        $this->validate($request, [
            'day' => 'date_format:"d.m.Y"',
        ]);

        //Generate date
        if (!$request->has('day')){
            $day = Carbon::now();
        }
        else{
            $day = new Carbon($request->input('day'));
        }

        if ($request->has('back'))
        {
            $day->subDay();
        }
        if ($request->has('next'))
        {
            $day->addDay();
        }
        if ($request->has('today'))
        {
            $day = Carbon::now();
        }

        $orderby1 = "posp";
        $orderby2 = "fullname";
        $orderbycol = 1;

        if ($request->has('section'))
        {
            $orderby1 = "secname";
            $orderby2 = "fullname";
            $orderbycol = 2;
        }

        if ($request->has('entry'))
        {
            $orderby1 = "entrypresent";
            $orderby2 = "entryname";
            $orderbycol = 3;
        }

        $department_id=Auth::user()->department_id;

        if ($request->has('save_comment') && $request->has('comment'))
        {
            //  return $request->input('comment');
            $comment = \App\Comment::findornew($request->input('commentid'));

            $comment->date = $day->toDateString();

            $comment->text = $request->input('comment');

            \App\Department::find($department_id)->comments()->save($comment);
        }

        $users = \App\User::where('department_id', '=', $department_id)
                ->orderBy('name', 'asc')
                ->get();

        $userlist = Lists::userlist($department_id);

        $entrylist = Lists::entrylist($department_id);

        $options = array();
        foreach($entrylist as $el)
        {
            $options[] = "<option class=' border-option item".$el->id."'' value='".$el->id."'>".$el->name."</option>";
        }
        //return($options);

        $styles = \App\Entry::where('department_id', '=', $department_id)
                  ->orderBy('name', 'asc')
                  ->get();


        $events = \App\User::where('users.department_id', '=', $department_id)
                            ->leftJoin('sections', 'sections.id', '=', 'users.section_id')
                            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
                            ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id')
                            ->addBinding($day->toDateString(), 'select')
                            ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                            ->selectRaw('CONCAT(lastname, ", ", firstname) as fullname, positions.name as posname, positions.priority as posp, sections.fullname as secname, CONCAT("item", entries.id) as class, if(approved = 1 or e.id is null, "approved", "notapproved") as class2, users.id as uid, e.id as eventid, e.comment as eventscomment, entries.name as entryname, if (entries.present = 1 , 1, if(entries.present = 0, 2, 0)) as entrypresent')
                            ->orderBy($orderby1)
                            ->orderBY($orderby2)
                            ->get();

        $events_present = \App\User::where('users.department_id', '=', $department_id)
                            ->leftJoin('sections', 'sections.id', '=', 'users.section_id')
                            ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id')
                            ->addBinding($day->toDateString(), 'select')
                            ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                            ->selectraw('if(present=0, "Abwesend", "Anwesend") as presence, count(if(present=0, 0, 1)) as p, count(if(present=1, 0, 1)) as p1')
                            ->groupBy('presence')
                            ->orderBy('presence', 'desc')
                            ->get();



        $events_summary = \App\User::where('users.department_id', '=', $department_id)
                            ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id')
                            ->addBinding($day->toDateString(), 'select')
                            ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                            ->groupBy('entries.name')
                            ->orderBY('entries.name')
                            ->selectraw('entries.name as entryname, entries.present as entrypresent, count(present) as entrycount')
                            ->get();

        $customdate = \App\Company::find(Auth::user()->department->company->id)->customdatesitems()
                    ->leftJoin('custom_dates', 'custom_dates_items.id', '=', 'custom_dates.items_id')
                    ->where('custom_dates.date', '=', $day->toDateString())
                    ->select('custom_dates.name as cusname')
                    ->get();

        $holiday = \App\Department::find($department_id)->holidays()
                    ->where('date_from', '<=', $day->toDateString())
                    ->where('date_to', '>=', $day->toDateString())
                    ->get();

        $usersthisday = \App\User::where('users.department_id', '=',$department_id)
                        ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id') //this is neccessary to get all users not only those with an event date
                        ->addBinding($day->toDateString(), 'select')
                        ->leftJoin('entries', 'e.entry_id', '=', 'entries.id')
                        ->get();

        $comment = \App\Comment::where('department_id', '=', $department_id)
                                ->where('date', '=', $day->toDAteString())
                                ->get();
        //return $usersthisday;

        return view('day', compact('users', 'day', 'events', 'styles', 'userlist', 'options', 'entrylist', 'customdate', 'holiday', 'events_present', 'events_summary', 'comment', 'orderbycol'));

    }

    /*
     * Insert Data from ajax-request
     */
    public function insertEvents(Request $request)
    {
        if($request->ajax())
        {

            $message = [
                'user_id.in' => 'Diesen Mitarbeiter koennen Sie nicht bearbeiten',
            ];

            $val_user_id= 'in:'.((Auth::user()->can('changeevents')) ? \App\Department::find(Auth::user()->department->id)->users()->get()->implode('id', ',') : Auth::user()->id);

            $this->validate($request, [
                'user_id' => $val_user_id,
            ], $message);

            $user_id = $request->input('user_id');
            $date_from = new Carbon($request->input('date_from'));
            $date_to = new Carbon($request->input('date_to'));

            //check event_id exists. If not create a new  array according to the submitted dates
            if ($request->has('event_id'))
            {
                $event_id = $request->input('event_id');
            }
            else
            {
                $event_id = array();
                for ($d=clone $date_from; $d <= $date_to ; $d->addDay())
                {
                   if($e = \App\Event::where('date', '=', $d->toDateString())->where('user_id', '=', $user_id)->first())
                   {
                        $event_id[] =  $e->id;
                    }
                    else
                    {
                        $event_id[] = 0;
                    }
                }
            }

            switch ($request->input('entry_id')) {
                case 0:

                    \App\Event::leftJoin('entries', 'entry_id', '=', 'entries.id')
                                ->select(DB::raw(' where right = 0 or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                                ->whereIn('events.id', $event_id)->delete();
                    break;

                case -1:

                    DB::table('events')
                        ->leftJoin('entries', 'entry_id', '=', 'entries.id')
                        ->select(DB::raw(' where right = 0 or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                        ->whereIn('events.id', $event_id)
                        ->update(['comment' => $request->input('comment')]);

                case -2;
                    DB::table('events')
                        ->leftJoin('entries', 'entry_id', '=', 'entries.id')
                        ->select(DB::raw(' where right = 0 or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                        ->whereIn('events.id', $event_id)
                        ->update(['approved' => $request->input('approved')]);

                    /*DB::update('update events set votes = 100 where name = ?', array('John'));

                    \App\Event::leftJoin('entries', 'entry_id', '=', 'entries.id')
                                ->select(DB::raw(' where right = 0 or or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                                ->whereIn('events.id', $event_id)
                                ->update( array('comment' => DB::raw(  ) ) );*/
                    break;

                default:

                    $entry = \App\Entry::find($request->input('entry_id'));
                    $onweekend = $entry->onweekend;
                    $right = $entry->right;
                    $d1 = clone $date_from;
                    $d2 = clone $date_to;
                    $custom_dates = Lists::customdatesinperiod($d1, $d2, $user_id)->toArray();

                    for ($day = clone $date_from; $day <= $date_to ; $day->addDay()) {
                        // only process if is to be inserted
                        if( ($day->isWeekday() && !in_array($day->toDateString(), $custom_dates)) || $onweekend || ($day->isWeekday() && in_array($day->toDateString(), $custom_dates) && $onweekend) )
                        {
                            $event = \App\Event::firstorNew(['user_id' => $user_id, 'date' => $day->toDateString()]);

                            if (!$event->exists)
                            {
                                $old_entry_right = 0;
                            }
                            else
                            {
                                $old_entry_right = $event->entry->right;
                            }

                            /* Either user has the right to confirm events
                            *  or the preexisting event (which will be overwritten) does not need right = permission to confirm ('can confirm')
                            *  or old event needs right but is not approved (just a wish)
                            */
                            if (Auth::user()->can('confirmentry') || !$old_entry_right || ($old_entry_right && !$event->approved))
                            {
                                $event->user_id = $user_id;
                                $event->date = $day->toDateString();
                                $event->entry_id = $request->input('entry_id');
                                $event->comment = $request->input('comment');
                                $event->approved = (!$right) ? 1 : ((Auth::user()->can('confirmentry')) ? $request->input('approved') : 0) ;
                                $event->save();
                            }

                        }
                    }

                    break;
            }
        }

        if ($request->input('sender') == 'month')
        {
            $events = Lists::eventsinmonth($date_from->startofmonth(), $date_to->endofmonth(), $request->input('user_id'));

            $html = '';
            foreach($events as $e)
            {
                $badge = ($e['comment']<>'') ? '<br><span class="badge">K</span>' : '';
                $html .= "<td class='".$e['class']."' title='Datum: ".strftime('%A', strtotime($e['date']))."&#10;Eintrag: ".$e['event_name']."&#10;Bemerkung: ".$e['comment']."' data-date='".$e['date']."' data-event_id=".$e['event_id']." data-approved=".$e['notapproved'].">".$e['event_shortname'].$badge."</td>";
            }
        }

        if ($request->input('sender') == 'day')
        {

            $event = \App\User::where('users.id', '=', $user_id)
                            ->leftJoin('sections', 'sections.id', '=', 'users.section_id')
                            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
                            ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id')
                            ->addBinding($date_from->toDateString(), 'select')
                            ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                            ->selectRaw('CONCAT(lastname, ", ", firstname) as fullname, positions.name as posname, positions.priority as posp, sections.fullname as secname, CONCAT("item", entries.id) as class, if(approved=1 or e.id is null, "approved", "notapproved") as class2, users.id as uid, e.id as eventid, e.comment as eventscomment, entries.name as entryname, if (entries.present = 1 , 1, if(entries.present = 0, 2, 0)) as entrypresent')
                            ->first();
            $present_na = ($event->entrypresent && $event->class2 == 'notapproved') ? "present_na" : "w";
            $html = "<td class='col1 ".$event->class." ".$event->class2." ".$present_na."'>".$event->fullname."</td>
                     <td class='col2 ".$event->class." ".$event->class2." ".$present_na."'>".$event->secname."</td>
                     <td class='col3 ".$event->class." ".$event->class2." ".$present_na." entry'>".$event->entryname."</td>
                     <td class='col4 ".$event->class." ".$event->class2." ".$present_na." entry'>".$event->eventscomment."</td>"; // $html = $event;

        }

        return($html);
    }

/*
     * Insert Data from modal
     */
    public function modalinsertEvents(Request $request)
    {

        $val_user_id= 'in:'.((Auth::user()->can('changeevents')) ? \App\Department::find(Auth::user()->department->id)->users()->get()->implode('id', ',') : Auth::user()->id);

        $this->validate($request, [
            'user_id' => $val_user_id,
            'date_from' => 'date_format:j.n.Y|required',
            'date_to' => 'date_format:j.n.Y|required',

        ]);

        $user_id = $request->input('user_id');

        $date_from = new Carbon($request->input('date_from'));
        $date_to = new Carbon($request->input('date_to'));


        //check event_id exists. If not create a new  array according to the submitted dates
        if ($request->has('event_id'))
        {
            $event_id = $request->input('event_id');
        }
        else
        {
            $event_id = array();
            for ($d=clone $date_from; $d <= $date_to ; $d->addDay())
            {
               if($e = \App\Event::where('date', '=', $d->toDateString())->where('user_id', '=', $user_id)->first())
               {
                    $event_id[] =  $e->id;
                }
                else
                {
                    $event_id[] = 0;
                }
            }
        }

        switch ($request->input('entry_id')) {

            case 0:
                \App\Event::leftJoin('entries', 'entry_id', '=', 'entries.id')
                            ->select(DB::raw(' where right = 0 or or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                            ->whereIn('events.id', $event_id)->delete();
                break;

            case -1:
                DB::table('events')
                    ->leftJoin('entries', 'entry_id', '=', 'entries.id')
                    ->select(DB::raw(' where right = 0 or or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                    ->whereIn('events.id', $event_id)
                    ->update(['comment' => $request->input('comment')]);
                break;

            case -2:
                    DB::table('events')
                        ->leftJoin('entries', 'entry_id', '=', 'entries.id')
                        ->select(DB::raw(' where right = 0 or right ='.Auth::user()->can('confirmentry').' and right = 1'))
                        ->whereIn('events.id', $event_id)
                        ->update(['approved' => $request->input('approved')]);

            default:

                $entry = \App\Entry::find($request->input('entry_id'));
                $onweekend = $entry->onweekend;
                $right = $entry->right;
                // Either the right does not need permission 'confirmentry' or it does and user has the permission
                if( Auth::user()->can('confirmentry') || !$right )
                {
                    $d1 = clone $date_from;
                    $d2 = clone $date_to;
                    $custom_dates = Lists::customdatesinperiod($d1, $d2, $user_id)->toArray(); //return $custom_dates;
                    $key = 0;
                    for ($day = clone $date_from; $day <= $date_to ; $day->addDay()) {
                        // only process if is to be inserted
                        if( ($day->isWeekday() && !in_array($day->toDateString(), $custom_dates)) || $onweekend || ($day->isWeekday() && in_array($day->toDateString(), $custom_dates) && $onweekend) )
                        {

                            $event = \App\Event::firstorNew(['user_id' => $user_id, 'date' => $day->toDateString()]);

                            if (!$event->exists)
                            {
                                $old_entry_right = 0;
                            }
                            else
                            {
                                $old_entry_right = $event->entry->right;
                            }

                            if (Auth::user()->can('confirmentry') || !$old_entry_right )
                            {
                                /*$event->user_id = $user_id;
                                $event->date = $day->toDateString();*/
                                $event->entry_id = $request->input('entry_id');
                                $event->comment = $request->input('comment');
                                $event->approved = (!$right) ? 1 : ((Auth::user()->can('confirmentry')) ? $request->input('approved') : 0);
                                $event->save();
                            }
                        }
                    }
                }
                else
                {
                    return ('2');
                }
                break;
        }

        //Generate table row
        $events = Lists::eventsinmonth($date_from->startofmonth(), $date_to->endofmonth(), $request->input('user_id'));

        $html = '';
        foreach($events as $e)
        {
            $badge = ($e['comment']<>'') ? '<br><span class="badge">K</span>' : '';
            $html .= "<td class='".$e['class']."' title='Eintrag: ".$e['event_name'].", Bemerkung: ".$e['comment']."' data-date='".$e['date']."' data-event_id=".$e['event_id'].">".$e['event_shortname'].$badge."</td>";
        }

        return($html);
    }

    /**
     * Show the week.
     *
     * @request Request
     * @return Response
     */
    public function showWeek(Request $request)
    {
        //Generate date
        if (!$request->has('day')){
            $day = Carbon::now();
        }
        else{
            $day = new Carbon($request->input('day'));
        }

        if ($request->has('back'))
        {
            $day->subweek();
        }
        if ($request->has('next'))
        {
            $day->addweek();
        }
        if ($request->has('thisweek'))
        {
            $day = Carbon::now();
        }
        if($request->has('setweekofyear'))
        {
            $day = $day->startofYear()->addWeeks($request->weekofyear-1);
        }

        $weekOfYear = $day->weekOfYear;
        $monday = clone $day;
        $monday->startOfWeek();
        $friday = clone $day;
        $friday->startOfWeek();
        $friday->adddays(6);

        $department_id=Auth::user()->department_id;

        $orderby1 = "posp";
        $orderby2 = "fullname";
        $orderbycol = 1;

        if ($request->has('section'))
        {
            $orderby1 = "secname";
            $orderby2 = "fullname";
            $orderbycol = 2;
        }

        if ($request->has('entry'))
        {
            $orderby1 = "entrypresent";
            $orderby2 = "entryname";
            $orderbycol = 3;
        }

        //Get the data

        $styles = \App\Entry::where('department_id', '=', $department_id)
                  ->orderBy('name', 'asc')
                  ->get();

        $customdate = \App\Company::find(Auth::user()->department->company->id)->customdatesitems()
                    ->leftJoin('custom_dates', 'custom_dates_items.id', '=', 'custom_dates.items_id')
                    ->whereBetween('custom_dates.date', [$monday->toDateString(), $friday->toDateString()])
                    ->select('custom_dates.date')
                    ->get()
                    ->toArray();

        $customdates = array_flatten($customdate, 'date');

        $holidays = \App\Department::find($department_id)->holidays()
                    ->where('date_from', '<=', $monday->toDateString())
                    ->where('date_to', '>=', $friday->toDateString())
                    ->get();

        $comments = \App\Comment::where('department_id', '=', $department_id)
                                ->whereBetween('date', [$monday->toDateString(), $friday->toDateString()])
                                ->get()
                                ->keyBy("date");

        $entrylist = Lists::entrylist($department_id);

        $userlist = Lists::userlist($department_id);

        $options = array();
        foreach($entrylist as $el)
        {
            $options[] = "<option class=' border-option item".$el->id."'' value='".$el->id."'>".$el->name."</option>";
        }


        for ($day2 = clone $monday; $day2 <= $friday ; $day2->addDay()) {

            $events[$day2->toDateString()] = \App\User::where('users.department_id', '=', $department_id)
                                            ->leftJoin('sections', 'sections.id', '=', 'users.section_id')
                                            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
                                            ->leftjoin(db::raw('(select * from events where date = ? ) e'), 'users.id', '=', 'e.user_id')
                                            ->addBinding($day2->toDateString(), 'select')
                                            ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                                            ->selectRaw('CONCAT(lastname, ", ", firstname) as fullname, positions.name as posname, positions.priority as posp, sections.fullname as secname, CONCAT("item", entries.id) as class, if(e.id is null, "", if(approved=1, "approved", "notapproved")) as class2, users.id as uid, e.id as eventid, e.comment as eventscomment, entries.name as entryname, if (entries.present is null, 2, if (entries.present = 1 , 1, 0)) as entrypresent')
                                            ->orderBy($orderby1)
                                            ->orderBY($orderby2)
                                            ->get();

            $events_present[$day2->toDateString()] = \App\User::where('users.department_id', '=', $department_id)
                                                    ->leftJoin('sections', 'sections.id', '=', 'users.section_id')
                                                    ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id')
                                                    ->addBinding($day2->toDateString(), 'select')
                                                    ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                                                    ->selectraw('if(present=0, "Abwesend", "Anwesend") as presence, count(if(present=0, 0, 1)) as p, count(if(present=1, 0, 1)) as p1')
                                                    ->groupBy('presence')
                                                    ->orderBy('presence', 'desc')
                                                    ->get();

        }

        //return $events['2015-12-31'];

        return view('week', compact('day', 'monday', 'friday', 'customdates', 'holiday', 'events', 'events_present', 'entrylist', 'userlist', 'options', 'comments', 'weekOfYear', 'styles'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
