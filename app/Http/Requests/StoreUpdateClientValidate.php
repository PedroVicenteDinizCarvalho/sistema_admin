<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FileCustonValidation;

class StoreUpdateClientValidate extends FormRequest
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
        return [
            'name'          => "required|min:3|max:100|unique:clients,name,{$this->id},id",
            'type'          => 'max:100',
            'image'         => 'image',
            'type_document' => 'max:100',
            'document'      => 'max:100',
            'email'         => 'max:100',
            'tel_ddd'       => 'max:100',
            'tel_number'    => 'max:100',
        ];
    }
}