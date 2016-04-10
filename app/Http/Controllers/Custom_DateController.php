<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\lists;
use Carbon\Carbon;
use Auth;

class Custom_DateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $route = $request->route();
        $start = new CArbon("2016-01-01");
        $end = new Carbon("2016-01-31");
        $customdate = \App\Company::find(Auth::user()->department->company->id)->customdatesitems()
                    ->leftJoin('custom_dates', 'custom_dates_items.id', '=', 'custom_dates.items_id')
                    ->whereBetween('custom_dates.date', [$start->toDateString(), $end->toDateString()])
                    ->select('custom_dates.date')
                    ->get()
                    ->toArray();

        $custom_dates = array_flatten($customdate, 'date');
        return view('start', compact('route', 'custom_dates', 'start', 'end'));
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
        //
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
