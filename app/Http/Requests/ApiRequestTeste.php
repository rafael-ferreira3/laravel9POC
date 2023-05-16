<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ApiRequestTeste extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        $rules = [
            'nome' => 'required|min:3',
            'sobrenome' => 'required|min:3',
            'email' => 'required|min:3',
            'estado' => 'required|min:3',
            'cidade' => 'required|min:3',
            'rua' => 'required|min:3',
            'numero' => 'required|min:3'
            ];

        return $rules;
    }

}
