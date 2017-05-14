<?php 

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	/**
     * Fillable fields
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];
}
