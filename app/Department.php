<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
     /**
     * Fillable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id'
    ];
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    } 

    public function positions()
    {
        return $this->hasMany('App\Position');
    }
    
    public function comments()
    {
    	return $this->hasMany('App\Comment');
    }

    public function sections()
    {
    	return $this->hasMany('App\Section');
    }

    public function users()
    {
    	return $this->hasMany('App\User');
    }

    public function entries()
    {
        return $this->hasMany('App\Entry');         
    }

    public function holidays()
    {
        return $this->hasMany('App\Holiday');         
    }

    public function events()
    {
        return $this->hasManyThrough('App\Event', 'App\User');         
    }

    public function  getffullNameAttribute()
	{
    	return $this->attributes['lastname'] .', '. $this->attributes['firstName'];
	}
    
}
