<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileFormRequest extends FormRequest
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
            'fullname' => '',
            'occupation' => '',
            'company' => '',
            'phone' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'postcode' => '',
            'bitcoin' => '',
            'ether' => '',
            'litecoin' => '',
            'facebook' => '',
            'linkedin' => '',
            'twitter' => '',
            'instagram' => ''
        ];
    }
}
