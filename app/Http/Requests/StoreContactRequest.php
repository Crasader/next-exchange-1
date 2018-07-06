<?php
/*
File created to validate Contact form before submitting.
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
            'name' => 'required|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'email' => 'required|email',
            'message' => 'required|regex:/(^([a-zA-Z0-9 ]+)(\d+)?$)/u',
            'g-recaptcha-response'  => 'required'
        ];
    }
}
