<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class PositionRequest extends Request
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
        
        $positions = \App\Position::where('department_id', '=', Auth::user()->department_id)->get();
        
        if(
            Auth::user()->hasRole('companyadmin') && 
            (Auth::user()->company->id == $this->route('company') || Auth::user()->company->id == $this->company_id) && 
            ($departments->contains("id", $this->route('department')) ||($departments->contains("id", $this->department_id)) || $this->route('department') == false) &&
            ($positions->contains("id", $this->route('position')) || $this->route('position') == false)
            )
        {
            return true;
        }
        $positions = \App\Department::find(Auth::user()->department->id)->positions;
        if( 
            Auth::user()->hasRole('localadmin') && 
            (Auth::user()->company->id == $this->route('company') || Auth::user()->company->id == $this->company_id) && 
            (Auth::user()->department_id == $this->route('department') || Auth::user()->department_id == $this->department_id) && ($positions->contains("id", $this->route('position')) || $this->route('position') == false))
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
