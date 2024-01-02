<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class PelangganRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rule = [
            "nama" => "required|string|max:150",
            "alamat" => "required|string|max:200",
            "notelp" => "required|string|max:13",
            "email" => "required|string|email|max:100",
            "jenis"=> "required|in:KTP,SIM",
            "photo" => "required|file|mimes:jpg,png,jpeg|max:2048" 
        ];
        if($this->isMethod("put")){
            unset($rule["jenis"], $rule["photo"]);    
        }

        return $rule;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
