<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['name', 'date_from', 'date_to', 'department_id'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

}
