<?php

namespace Modules\AdvertiseAffiliated\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvertiseAffiliatedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'banner_image' => 'required',
            //'banner_description' => 'required|string',
            // 'image_1' => 'required|string',
            // 'description_1' => 'required|string',
            // 'image_2' => 'required|string',
            // 'description_2' => 'required|string',
           // 'description' => 'required|string',
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
