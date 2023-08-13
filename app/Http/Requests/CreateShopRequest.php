<?php

namespace App\Http\Requests;

use App\Models\Shop;
use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
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
        $rules = Shop::$rules;
        $rules['payment_methods'] = 'nullable|string';
        $rules['payments'] = 'nullable';
        $rules['file-input'] = 'nullable';

        return $rules;
    }

    public function messages()
    {
        return [
            'desc.required' => '請輸入商店簡介',
        ];
    }
}
