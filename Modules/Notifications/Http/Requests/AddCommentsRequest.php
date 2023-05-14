<?php

namespace Modules\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'comment' => 'required',
            //'tag_user_id'=> 'array',
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
