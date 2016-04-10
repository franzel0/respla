<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CompanyAdminRequest extends Request
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
        if(Auth::user()->hasRole('companyadmin')  && Auth::user()->company->id == $this->route('company') && ($departments->contains("id", $this->route('department')) || $this->route('department') == false))
        {
            return true;
        }
        return false;
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
