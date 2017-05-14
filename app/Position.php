<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','priority', 'active', 'department_id'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    } 
}
