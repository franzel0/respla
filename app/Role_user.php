<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'user_id'];

    protected $table = 'role_user';

    public $primaryKey  = 'user_id';
}
