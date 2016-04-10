<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class LocalAdminRequest extends Request
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
        
        if(Auth::user()->hasRole('companyadmin') && Auth::user()->company->id == $this->route('company') && ($departments->contains("id", $this->route('department')) || $this->route('department') == false))
        {
            return true;
        }
        if(Auth::user()->hasRole('localadmin') && Auth::user()->company->id == $this->route('company') && Auth::user()->department->id == $this->route('department'))
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
