<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreatePdfRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Classes\lists;
use Auth;
use App;
use DB;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function month(CreatePdfRequest $request)
    {
        $monate = array(1=>"Januar",
                        2=>"Februar",
                        3=>"M&auml;rz",
                        4=>"April",
                        5=>"Mai",
                        6=>"Juni",
                        7=>"Juli",
                        8=>"August",
                        9=>"September",
                        10=>"Oktober",
                        11=>"November",
                        12=>"Dezember");

        $weekday = array(1 => "Montag",
                         2 => "Dienstag",
                         3 => "Mittwoch",
                         4 => "Donnerstag",
                         5 => "Freitag",
                         6 => "Samstag",
                         7 => "Sonntag");

        $position = $request->position;
        $department_id = $request->department;
        $startofmonth = new Carbon($request->startofmonth);
        $endofmonth = clone $startofmonth;
        $endofmonth->endofMonth();

        If ($position == null)
        {
            $filterByPosition = '';
        }
        else
        {
            $filterByPosition = ' where position_id = '.$position." ";
        }

        $daysinmonth = Lists::daysinmonth($startofmonth, $endofmonth, Auth::user()->id);

        $events = Lists::monthevents($department_id, Auth::user()->company, $filterByPosition, $startofmonth);

        $numberdays = $startofmonth->daysInMonth;

        $styles = \App\Entry::where('department_id', '=', $department_id)
                  ->orderBy('name', 'asc')
                  ->get();

        $date = $monate[$startofmonth->format('n')].' '. $startofmonth->format('Y');

        $data = compact('daysinmonth', 'numberdays', 'events', 'styles', 'date', 'year');

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.month', $data);
        return $pdf->setPaper('a4')->setOrientation('landscape')->stream();
        //only to test as html output
        return view('pdf/month', compact('daysinmonth', 'events', 'numberdays', 'styles', 'date', 'year'));
    }

    public function day(CreatePdfRequest $request)
    {
        $day = new Carbon($request->date);

        $department_id = $request->department;

        $orderby1 = "posp";
        $orderby2 = "fullname";
        $orderbycol = 1;

        if ($request->orderbycol == 2)
        {
            $orderby1 = "secname";
            $orderby2 = "fullname";
            $orderbycol = 2;
        }

        if ($request->orderbycol == 3)
        {
            $orderby1 = "entrypresent";
            $orderby2 = "entryname";
            $orderbycol = 3;
        }

        $styles = \App\Entry::where('department_id', '=', $department_id)
                  ->orderBy('name', 'asc')
                  ->get();

        $events = \App\User::where('users.department_id', '=', $department_id)
                            ->leftJoin('sections', 'sections.id', '=', 'users.section_id')
                            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
                            ->leftjoin(db::raw('(select * from events where date = ?) e'), 'users.id', '=', 'e.user_id')
                            ->addBinding($day->toDateString(), 'select')
                            ->leftjoin('entries', 'e.entry_id', '=', 'entries.id')
                            ->selectRaw('CONCAT(lastname, ", ", firstname) as fullname, positions.name as posname, positions.priority as posp, sections.fullname as secname, CONCAT("item", entries.id) as class, if(approved=1, "approved", "notapproved") as class2, users.id as uid, e.id as eventid, e.comment as eventscomment, entries.name as entryname, if (entries.present = 1 , 1, if(entries.present = 0, 2, 0)) as entrypresent')
                            ->orderBy($orderby1)
                            ->orderBY($orderby2)
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

        $comment = \App\Comment::where('department_id', '=', $department_id)
                                ->where('date', '=', $day->toDAteString())
                                ->get();

    $data = compact('styles', 'events', 'customdate', 'holiday', 'comment', 'day');

    $pdf = App::make('dompdf.wrapper');
    $pdf->loadView('pdf.day', $data);
    return $pdf->setPaper('a4')->setOrientation('portrait')->stream();
    //only to test as html output
    return view('pdf/day', $data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type)
    {
        return $type;//
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
