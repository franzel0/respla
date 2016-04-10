<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class EntryRequest extends Request
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

        $entries = \App\Entry::where('department_id', '=', Auth::user()->department_id)->get();
        
        if(
            Auth::user()->hasRole('companyadmin') && 
            Auth::user()->company->id == $this->route('company') && 
            ($departments->contains("id", $this->route('department')) || $this->route('department') == false) && 
            ($entries->contains("id", $this->route('entry')) || $this->route('entry') == false)
        )
        {
            return true;
        }
        
        $entries = \App\Department::find(Auth::user()->department->id)->entries;
        
        if(Auth::user()->hasRole('localadmin') && Auth::user()->company->id == $this->route('company') &&  Auth::user()->department_id == $this->route('department') && ($entries->contains("id", $this->route('entry')) || $this->route('entry') == false))
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
