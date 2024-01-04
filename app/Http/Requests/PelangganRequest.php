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
            "pelanggan_nama" => "required|string|max:150",
            "pelanggan_alamat" => "required|string|max:200",
            "pelanggan_notelp" => "required|string|max:13",
            "pelanggan_email" => "required|string|email|max:100",
            "pelanggan_data_jenis"=> "required|in:KTP,SIM",
            "pelanggan_data_photo" => "required|file|mimes:jpg,png,jpeg|max:2048" 
        ];
        if($this->isMethod("put")){
            unset($rule["pelanggan_data_jenis"], $rule["pelanggan_data_photo"]);    
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
