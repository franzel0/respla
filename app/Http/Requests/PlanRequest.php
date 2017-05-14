<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use Illuminate\Http\Response;

class PlanRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can("planevent") || Auth::user()->id == $this->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_date' => 'date',
            'new_date' => 'date',
            'user_id'  => 'numeric',
            'entry' => 'numeric',
            'entry_id' => 'numeric'
        ];
        //validation for new_date array('regex:^(((19|20)(([0][48])|([2468][048])|([13579][26]))|2000)[\-](([0][13578]|[1][02])[\-]([012][0-9]|[3][01])|([0][469]|11)[\-]([012][0-9]|30)|02[\-]([012][0-9]))|((19|20)(([02468][1235679])|([13579][01345789]))|1900)[\-](([0][13578]|[1][02])[\-]([012][0-9]|[3][01])|([0][469]|11)[\-]([012][0-9]|30)|02[\-]([012][0-8])))$^')
        //validation for old_date array('regex:^(((19|20)(([0][48])|([2468][048])|([13579][26]))|2000)[\-](([0][13578]|[1][02])[\-]([012][0-9]|[3][01])|([0][469]|11)[\-]([012][0-9]|30)|02[\-]([012][0-9]))|((19|20)(([02468][1235679])|([13579][01345789]))|1900)[\-](([0][13578]|[1][02])[\-]([012][0-9]|[3][01])|([0][469]|11)[\-]([012][0-9]|30)|02[\-]([012][0-8])))$^')
    }

    /**
     * Get the response for a forbidden operation.
     *
     * @return \Illuminate\Http\Response
     */
    public function forbiddenResponse()
    {
        return new Response('Forbidden', 401);
    }
}
