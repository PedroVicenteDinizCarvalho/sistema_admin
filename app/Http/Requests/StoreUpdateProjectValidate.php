<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FileCustonValidation;

class StoreUpdateProjectValidate extends FormRequest
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
            'name'              => "required|min:3|max:100|unique:projects,name,{$this->id},id",
            'description'       => 'max 500',
            'type'              => 'max:100',
            'image'             => 'image',
            'price'             => 'min: 0',
            'plots'             => 'max:100',
            'paid_installments' => 'min:0',
            'deadline'          => 'min:0',
            'client'            => 'max: 100',
        ];
    }
}