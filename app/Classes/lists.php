<?php
namespace App\Classes;

use Auth;
use Carbon\Carbon;
use DB;
use Redirect;

class lists{

    protected $long_weekday = array(1 =>'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');

   	public static function positions($department_id)
   	{
        $positions = \App\Department::find($department_id)->positions->sortBy('priority');

        return $positions;
    }


    public static function sections($department_id)
   	{
        $sections = \App\Department::find($department_id)->sections->sortBy('fullname');

        return $sections;
    }

    public static function companies()
    {
    	if (Auth::user()->can('changecompany'))
    	{
    		$companies = \App\Company::orderBy('name')->lists('name', 'id');
    	}
    	else
    	{
    		$companies = null;
    	}

    	return $companies;
    }

    public static function departments($company_id)
    {
    	if (Auth::user()->can('changedepartment'))
    	{
    		$departments = \App\Company::find($company_id)->departments->sortBy('name')->lists('name', 'id');
    	}
    	else
    	{
    		$departments = null;
    	}

    	return $departments;
    }

    public static function userlist($department_id)
    {
        if (Auth::user()->can('changeevents'))
        {
            $userlist = \App\Department::find(1)->users()->select(DB::raw('concat (lastname,", ",firstname) as full_name,id'))->orderBy('full_name')->lists('full_name', 'id');
        }
        else
        {
            $userlist = null;
        }

        return $userlist;
    }

    public static function entrylist($department_id)
    {

        $entrylist = \App\Department::find($department_id)->entries()->orderBy('name')->get();

        return $entrylist;
    }

    public static function positionlist($department_id)
    {

       $positions = \App\Department::find($department_id)
                                    ->positions()
                                    ->orderBy('priority')
                                    ->lists('name', 'id')
                                    ->all();
        return $positions;

    }

    public static function users($department_id)
    {
        $users = \App\User::where('department_id', '=', $department_id)->selectRaw('CONCAT(lastname, ", ", firstname) as fullname, id')->orderBy('fullname')->get();

        return $users;
    }

    public static function roles()
    {
        //setting the various restriction for role which can be given
        if (Auth::user()->hasRole('admin'))
        {
            $roles = \App\Role::all()->sortBy('display_name')->lists('display_name', 'id');
        }
        elseif (Auth::user()->can('changecompanyroles'))
        {
            $roles = \App\Role::whereIn('id', [2, 3, 4, 5, 6, 7])->orderBy('display_name')->lists('display_name', 'id');
        }
        elseif (Auth::user()->can('changedepartmentroles'))
        {
            $roles = \App\Role::whereIn('id', [3, 4, 5, 6, 7])->orderBy('display_name')->lists('display_name', 'id');
        }
        else
        {
            $roles = Auth::user()->roles()->lists('display_name', 'id');
        }

        return $roles;
    }

    public static function entries($department_id)
    {
        $entries = \App\Department::find($department_id)->entries->sortBy('name');

        return $entries;
    }

    public static function holidays($department_id)
    {
        $entries = \App\Department::find($department_id)->holidays->sortBy('name');

        return $entries;
    }

    public static function comments($department_id)
    {
    	$entries = \App\Department::find($department_id)->comments;

    	return $entries;
    }

    public static function firstdepartment_id($company_id)
    {
    	$departments = \App\Company::find($company_id)->departments;

        if ($departments->count()==0)
            {
               return -1;
            }

        return $departments->sortBy('name')->first()->id;
    }

    public static function holidaysinmonth($day)
    {
       $firstday = Carbon::parse($day)->startOfMonth()->toDateString();
       $lastday = Carbon::parse($day)->endOfMonth()->toDateString();

       $result = \App\CustomDate::whereBetween('date', [$firstday, $lastday])->get();

       return $result;
    }

    public static function customdatesinmonth($dayofmonth, $user_id)
    {
        $q = \App\Company::find(\App\User::find($user_id)->department->company->id)->customdatesitems()->lists('id');

        $custom_dates = \App\CustomDate::whereBetween('date', [$dayofmonth->startofmonth()->toDateString(), $dayofmonth->endofmonth()])
                        ->whereIn('items_id', $q)->lists('date');

        return $custom_dates;
    }

    public static function customdatesinperiod($startofperiod, $endofperiod, $user_id)
    {
        $q = \App\Company::find(\App\User::find($user_id)->department->company->id)->customdatesitems()->lists('id');

        $custom_dates = \App\CustomDate::whereBetween('date', [$startofperiod->toDateString(), $endofperiod->toDateString()])
                        ->whereIn('items_id', $q)->lists('date');

        return $custom_dates;
    }

    // this function is for retrieving a single row only
    public static function eventsinmonth($firstday, $lastday, $user_id)
    {
        $short_weekday = array(1 =>'Mo', 2 =>'Di', 3=>'Mi', 4=>'Do', 5=>'Fr', 6=>'Sa', 7=>'So');

        $events = \App\Event::with ('user')->where('user_id', '=', $user_id)->whereBetween('date', [$firstday->toDateString(), $lastday->toDateString()])->orderBy('date')->get()->keyBy('date');
        //$events = \App\User::find($user_id)->events()->whereBetween('date', [$firstday->toDateString(), $lastday->toDateString()])->orderBy('date')->get()->keyBy('date');

        $q = \App\Company::find(\App\User::find($user_id)->department->company->id)->customdatesitems()->lists('id');

        $customdates = \App\CustomDate::whereBetween('date', [$firstday->toDateString(), $lastday->toDateString()])
                        ->whereIn('items_id', $q)
                        ->orderBY('date')->get()->keyBy('date');

        $result = array();

        for ($day=clone $firstday; $day<=$lastday  ; $day->addDay())
        {

            // check if date is a weekend day
            $weekend = ($day->isWeekend()) ? ' weekend' : '';

            // check if day is a custom date
            if (!$customdates->isEmpty() && $day->toDateString() == $customdates->first()->date)
            {
                $customdate_class = ' customdate';
                $customdate_name = $customdates->first()->name;
                $customdate_shortname = $customdates->first()->shortname;
                $customdates->shift();
            }
            else
            {
                $customdate_class = '';
                $customdate_name = '';
                $customdate_shortname = '';
            }

            //Check if event is approved and return class approved

            $r['event_name'] = ($customdate_name<>"") ? $customdate_name : "";

            // check if event on $day
            if (!$events->isEmpty() && $day->toDateString() == $events->first()->date)
            {
                $r['event_id'] = $events->first()->id;
                $r['event_shortname'] = $events->first()->entry->shorttext;
                $r['event_name'] .= ', '.$events->first()->entry->name;
                $notapproved = ($events->first()->approved == 0) ? ' notapproved ' : ' approved';
                $bg_present_na = ($events->first()->approved == 0 && $events->first()->entry->present == 1) ? ' present_na' : '';
                $r['notapproved'] = $notapproved;
                $r['class'] = "item".$events->first()->entry->id.$weekend.$customdate_class.$notapproved.$bg_present_na;
                $r['comment'] = $events->first()->comment;
                $events->shift();
            }
            else
            {
                $r['event_id'] = 0;
                $r['event_shortname'] = '';
                $r['event_name'] .= '';
                $r['notapproved'] ='';
                $r['class'] = $weekend.$customdate_class;
                $r['comment'] = '';;
            }

            $r['date'] = $day->toDateString();

            $result[] = $r;

        }

        return $result;
    }

    public static function daysinmonth($firstday, $lastday, $user_id)
    {
        $short_weekday = array(1 =>'Mo', 2 =>'Di', 3=>'Mi', 4=>'Do', 5=>'Fr', 6=>'Sa', 7=>'So');

        $q = \App\Company::find(\App\User::find($user_id)->department->company->id)->customdatesitems()->lists('id');

        $customdates = \App\CustomDate::whereBetween('date', [$firstday->toDateString(), $lastday->toDateString()])
                        ->whereIn('items_id', $q)
                        ->orderBY('date')->get()->keyBy('date');

        $result = array();

        for ($day=clone $firstday; $day<=$lastday  ; $day->addDay())
        {

            $day_name = $short_weekday[$day->format('N')];

            // check if date is a weekend day
            $weekend = ($day->isWeekend()) ? ' weekend' : '';

            // check if day is a custom date
            if (!$customdates->isEmpty() && $day->toDateString() == $customdates->first()->date)
            {
                $customdate_class = ' customdate';
                $customdate_name = ', '.$customdates->first()->name;
                $customdates->shift();
            }
            else
            {
                $customdate_class = '';
                $customdate_name = '';
            }

            // check if day is a holidays
            $holiday = \App\Department::find(\App\User::find($user_id)->department->id)
                        ->holidays()
                        ->where('date_from', '<=', $day->toDateString())
                        ->where('date_to', '>=', $day->toDateString())
                        ->get();


            if( $holiday->count()>0)
            {
                $holiday_class = ' holiday';
                $holiday_name = '';
                foreach ($holiday as $h)
                {
                    $holiday_name .= ', '.$h->name;
                }
            }
            else
            {
                $holiday_class = '';
                $holiday_name = '';
            }

            // check if today
            If ($day->toDateString() == Carbon::today()->toDateString())
            {
                $today_class = " today";
            }
            else
            {
                $today_class = "";
            }

            $r['dayofmonth'] = $day_name.'<br>'.$day->day;
            $r['class'] = $customdate_class.$weekend.$holiday_class.$today_class;
            $r['title'] = $day_name.$customdate_name.$holiday_name;

            $result[] = $r;

        }
        return $result;
    }
    public static function monthevents($department_id, $company_id, $filterByPosition, $startofmonth)
    {
        $sql="select
            d,
            monthday,
            username,
            fullname,
            userid,
            if(events.id is null, '0', events.id) as eventid,
            concat('item', entry_id) as class1,
            if(custbl.cusname is not null, ' customdate', '') as class2,
            if(dayofweek(d) in (1,7), ' weekend', '') as class3,
            weekday(d) as weekday,
            bgcolor,
            textcolor,
            wish,
            if(events.approved is null, '', if(events.approved = 1 , 'approved', 'notapproved')) as class4,
            shorttext,
            entries.name as entryname,
            present,
            `right`,
            custbl.cusname as cusname,
            comment
        from
            (select
                monthday,
                concat(:partdate, monthday) as d,
                users.id as userid,
                name as username,
                position_id,
                concat(users.firstname, ' ', users.lastname) as fullname
            from
                days_in_months, users
            where
                department_id = :department_id and monthday <= :numberdays) as m
                left join
            events ON (userid = events.user_id and d = date)
                left join
            entries ON (entry_id = entries.id)
                left join
            positions ON (userid = position_id)
                left join
             (select
                custom_dates.date as cusdate, custom_dates.name as cusname
            from
                companies_customdatesitems
            left join custom_dates ON (custom_dates.items_id = companies_customdatesitems.customdatesitems_id
                and companies_id = :company) and year(date) = year(:startofmonth)) as custbl ON (d = custbl.cusdate)".$filterByPosition."order by username asc , d asc";

        // parameters
        $partdate = $startofmonth->format('Y-m-');
        $numberdays = $startofmonth->daysInMonth;

        //DB::setFetchMode(PDO::FETCH_ASSOC);
        $events = db::select(db::raw($sql), [":partdate" =>$partdate, ":company" => $company_id, "numberdays" => $numberdays, "department_id" => $department_id, "startofmonth" => $startofmonth]);

        return $events;
    }

}
