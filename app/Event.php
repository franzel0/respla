<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes\lists;
use DB;

class Event extends Model
{
    protected $fillable = ['user_id', 'date', 'entry_id', 'comment', 'approved'];

    public function scopesamedepartment($query, $users)
    {
        return $query->wherein('user_id', $users);
    }

	public function scopebetween($query, $from, $to)
    {
        return $query->where('date', '>=', $from)
                     ->where('date', '<=', $to);
                     //->wherein('user_id', user()->select('id')->where('department_id', '=', Auth::user()->department_id));
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function entry()
    {
        return $this->hasOne('App\Entry', 'id', 'entry_id');
    }

    /**
     * Scope a query to get all holidays of company in range.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
    public function scopeHolidaysInMonth($query, $company, $day)
    {
        $dates = Lists::customdatesinmonth($day, 1);
        return $query->whereIn('date', $dates);
    }*/
    /*
    * Scope the daytype
    * 1-7= wekkday, 8 = holiday
    */
    public function scopedayType($query, $company)
    {
        return $query->leftJoin('custom_dates', 'custom_dates.date', '=', 'events.date')
                     ->leftJoin('companies_customdatesitems', function($join) use($company) {
                            $join->on('custom_dates.items_id', '=', 'companies_customdatesitems.customdatesitems_id')
                                 ->where('companies_id', '=', $company);
                        });
    }
}
