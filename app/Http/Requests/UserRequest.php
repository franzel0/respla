<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->hasRole('admin'))
        {
            return true;
        }
        $departments = \App\Company::find(Auth::user()->company->id)->departments;

        $users = \App\Company::find(Auth::user()->company->id)->sameCompanyUsers;

        if(
            Auth::user()->hasRole('companyadmin') &&
            Auth::user()->company->id == $this->route('company') &&
            ($departments->contains("id", $this->route('department')) || $this->route('department') == false) &&
            ($users->contains("id", $this->route('user')) || $this->route('user') == false)
        )
        {
            return true;
        }
        $users = \App\Department::find(Auth::user()->department->id)->users;
        if(Auth::user()->hasRole('localadmin') && Auth::user()->company->id == $this->route('company') &&  Auth::user()->department_id == $this->route('department') && ($users->contains("id", $this->route('user')) || $this->route('user') == false))
        {
            return true;
        }
        if(Auth::user()->department_id == $this->route("department"))
        {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
