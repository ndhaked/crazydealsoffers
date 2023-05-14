<?php

namespace Modules\StaticPages\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStaticPagesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_en' => 'required|unique:static_pages|max:100',
            'meta_keyword_en' => 'max:255',
            'meta_description_en' => 'max:255',
            'description_en' => 'required'
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
