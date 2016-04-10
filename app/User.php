<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    use EntrustUserTrait; 

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'firstname', 'lastname', 'email', 'position_id', 'section_id', 'active', 'department_id', 'company_id', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function company()
    {
        return $this->department->belongsTo('App\Company');    
    }
    public function position()
    {
        return $this->hasOne('App\Position', 'id', 'position_id');    
    }
    public function section()
    {
        return $this->hasOne('App\Section', 'id', 'section_id');    
    } 
    
    public function events()
    {
        return $this->hasMany('App\Event');         
    }
    
    public function itemsbetween($from, $to)
    {
        return $this->items
                    ->where('date', '>=', $from)
                    ->where('date', '<=', $to);
    }
    
    public function create_user()
    {
    }

    public function ownrole()
    {
        return $this->hasOne('\App\Role_user', 'user_id');
    }

    public function getFullName()
    {
        return $this->attributes['firstname'] .' '. $this->attributes['lastname'];
    }
}
