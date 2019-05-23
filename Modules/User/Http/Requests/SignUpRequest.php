<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\APIRequest;

class SignUpRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companyName' => 'required|unique:companies,name',
            'companyPhone' => 'required|unique:companies,phone',
            'companyAddress' => 'required',
            'adminName' => 'required|unique:users,name',
            'adminEmail' => 'required|unique:users,email',
            'adminPassword' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
