<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class SectionRequest extends Request
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
        
        $sections = \App\Section::where('department_id', '=', Auth::user()->department_id)->get();
        
        if(
            Auth::user()->hasRole('companyadmin') && 
            Auth::user()->company->id == $this->route('company') && 
            ($departments->contains("id", $this->route('department')) || $this->route('department') == false) &&
            ($sections->contains("id", $this->route('section')) || $this->route('section') == false)
            )        
        {
            return true;
        }
        $sections = \App\Department::find(Auth::user()->department->id)->sections;
        if(Auth::user()->hasRole('localadmin') && Auth::user()->company->id == $this->route('company') &&  Auth::user()->department_id == $this->route('department') && ($sections->contains("id", $this->route('section')) || $this->route('section') == false))
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
