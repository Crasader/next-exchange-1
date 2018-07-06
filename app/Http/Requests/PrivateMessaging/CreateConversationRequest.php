<?php

namespace App\Http\Requests\PrivateMessaging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateConversationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();

        $isContact = $user->acceptedConnections()->where('connected_user_id', $this->user_id)->exists();
        $isAdmin = $user->hasRole('admin');

        return $isContact || $isAdmin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'   => 'required|integer'
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
