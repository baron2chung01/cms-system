<?php

namespace App\Http\Requests;

use App\Models\MaterialsCategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialsCategoryRequest extends FormRequest
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
        $rules = MaterialsCategory::$rules;
        
        return $rules;
    }
}
