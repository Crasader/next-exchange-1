<?php

namespace App\Http\Requests\PrivateMessaging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SendPrivateMessageRequest extends FormRequest
{
    /**
     * Allow sending private message only for connected users
     * and when authenticated user is an admin
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
            'conversation_id'   => 'required|integer|exists:conversations,id',
            'message'           => 'required|max:700'
        ];
    }

    public function all($keys = null)
    {
        return array_merge(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
