<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['name', 'shorttext', 'bgcolor', 'textcolor', 'wish', 'present', 'right', 'onweeekend', 'isvisible', 'company_id', 'department_id'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function entries()
    {
        return $this->hasMany('App\Entry');
    }
    public function events()
    {
        return $this->hasMany('App\Event');
    }
    /**
     * The oncalls that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->orderBy('users.name');
    }

}
