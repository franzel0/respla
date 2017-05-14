<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oncall extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    /**
     * The oncalls that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->orderBy('name');
    }

}
