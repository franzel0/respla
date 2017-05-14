<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fullname','shortname', 'department_id'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    } 
}
