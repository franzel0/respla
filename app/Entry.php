<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['name', 'shorttext', 'bgcolor', 'textcolor', 'wish', 'present', 'right', 'onweeekend', 'department_id'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

}
