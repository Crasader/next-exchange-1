<?php
/*
File created to validate Contact form before submitting.
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIcoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required|min:3|max:40',
            'symbol'                => 'required|min:2|max:7',
            'total_supply_token'    => 'required|integer|min:0',
            'stage'                 => 'required|ico_stage',
            'launch_date'           => 'required|date',
            'initial_price'         => 'required|numeric|min:0',
            'short_description'     => 'required|min:10|max:500',
            'full_description'      => 'required|min:10|max:100'
        ];
    }
}
