<?php

namespace App\Http\Requests\API;

use App\Models\ShopsReview;
use InfyOm\Generator\Request\APIRequest;

class UpdateShopsReviewAPIRequest extends APIRequest
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
        $rules = ShopsReview::$rules;
        $rules['shops_reviews_uuid'] = 'nullable';
        $rules['rating'] = 'nullable';
        $rules['status'] = 'nullable';

        return $rules;

    }
}
