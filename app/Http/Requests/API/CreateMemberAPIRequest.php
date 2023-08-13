<?php

namespace App\Http\Requests\API;

use App\Models\Member;
use InfyOm\Generator\Request\APIRequest;

class CreateMemberAPIRequest extends APIRequest
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
        $rules = Member::$rules;

        // to be set automatically at backend.
        $rules['members_uuid'] = 'nullable';
        $rules['type'] = 'nullable';
        $rules['status'] = 'nullable';
        $rules['confirm_password'] = 'required';

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'             => '「名稱」欄位是必填的',
            'email.required'            => '「電郵」欄位是必填的',
            'phone.required'            => '「電話號碼」欄位是必填的',
            'phone.integer'             => '「電話號碼」應為整數。',
            'phone.between'             => '「電話號碼」應介於 :min 和 :max 之間。',
            'password.required'         => '「密碼」欄位是必填的',
            'password.string'           => '「密碼」應為字串。',
            'password.min'              => '「密碼」應至少包含 :min 個字元。',
            'password.max'              => '「密碼」應最多包含 :max 個字元。',
            'confirm_password.required' => '「確認密碼」欄位是必填的',
        ];
    }
}
