<?php

namespace Modules\EmailTemplates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
        return [
            'name' => 'required|unique:email_templates,name,' . $this->request->get('id'),
            'subject' => 'required|max:255',
            'body' => 'required',
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
