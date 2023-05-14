<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'coupon_code' => 'required|string|max:50',
            //'price' => 'required|numeric',
            //'off_on_product' => 'required|numeric',
            'expiry_date' => 'required',
            'item_purchase_link' => 'required',
            'description' => 'required',
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
