<?php

namespace Modules\Billing\Http\Requests;


use App\Http\Requests\APIRequest;

class CustomerRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|numeric',
            'due_on' => 'required',
            'area_id' => 'required',
            'subscription_type_id' => 'required',
            'monthly_bill' => 'required',
            'status' => 'required',
            'users' => 'required'
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
