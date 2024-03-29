<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class KategoriRequest extends FormRequest
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
            "kategori_nama" => ["required", "string", "max:50", "unique:kategoris,kategori_nama"],
        ];

        return $rule;
    }

    protected function failedValidation(Validator $validator)
    {
        $operation = $this->isMethod("POST") ? "create" : "update";
        throw new HttpResponseException(response()->json([
            "success" => false,
            "code" => 400,
            "message" => "failed to $operation kategori",
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
