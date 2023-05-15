<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        $id= $this->id ?? -1;


        $rules = [
            'name' => 'required|string|max:255,min:3',
            'email' => [
                'required',
                'string',
                'email',
                "unique:users,email,{$id},id",
            ],
            'password' => [
                'required',
                'min:3',
                'max:15'
            ]
            ];
        if($this->method('PUT')) {
            $rules['password'] = [
                'nullable',
                'min:3',
                'max:15'
            ];
        }
        return $rules;
    }
}
