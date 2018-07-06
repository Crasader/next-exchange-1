<?php

namespace App\Http\Requests\Ico;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ListUserIcosRequest extends FormRequest
{
    /**
     * Determine if the user in connections list
     * to see target ICO's/projects
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $target */
        $target = User::findOrFail($this->id);

        $hasAccess = $target->acceptedConnections()->where('connected_user_id', Auth::id())->exists();
        $hasAccess = $hasAccess || $target->id === Auth::id();

        return $hasAccess;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
