<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Collection;

class Company extends Model
{
    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function departments()
    {
        return $this->hasMany('App\Department');
    }

    public function entries()
    {
        return $this->hasMany('App\Entry');
    }

    /**
     * The custom daes items that belong to the company.
     */
    public function customdatesitems()
    {
        return $this->belongsToMany('App\CustomDatesItems', 'companies_customdatesitems', 'companies_id', 'customdatesitems_id');
    }

    /*
    * Get users of the same company as Auth::user()
    */
    public function sameCompanyUsers()
    {
        return $this->hasManyThrough('App\User', 'App\Department');
    }

    /*
    * Get users of the same department as Auth::user()
    */
    public function sameDepartmentUsers()
    {
        return $this->hasManyThrough('App\User', 'App\Department')->where('department_id', '=', Auth::user()->department_id);
    }

    /*
    * Get companies according to users permission
    */
    public static function companiesUserRole()
    {
        if (Auth::user()->can('changecompany'))
        {
            $companies = \App\Company::orderBy('name')->get();//->lists('name', 'id');
        }
        else
        {
            $companies = null;
        }
        return $companies;
    }

    /* Scope the daytype
    * 1-7= weekday, 8 = holiday
    */
    public function scopedayType($query, $firstday, $lastday)
    {
        $result = $this->customdatesitems()
                    ->leftJoin('custom_dates', 'custom_dates.items_id', '=', 'companies_customdatesitems.customdatesitems_id')
                    ->whereBetween('date', [$firstday->toDateString(), $lastday->toDateString()])
                    ->selectRaw('custom_dates.date as date, custom_dates.name as name, 8 as type')->get()->toArray();

        $result = new \Illuminate\Support\Collection( $result );

        /*for ($d = clone $firstday; $d <= $lastday; $d->addDay()) {
            if($d->format('N') == 6){
                $result->push(['date' => $d->toDateString(), 'name' => 'Samstag', 'type' =>6]);
            }
            if($d->format('N') == 7){
                $result->push(['date' => $d->toDateString(), 'name' => 'Sonntag', 'type' => 7]);
            }
        }*/

        return $result;
    }



}
