<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

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

}
