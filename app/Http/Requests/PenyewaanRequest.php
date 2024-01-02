<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PenyewaanRequest extends FormRequest
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
        // default for create 
        $rule = [
            "pelanggan_id" => "required|numeric|exists:pelanggans,id",
            "tglsewa" => "required|date|after:now",
            "tglkembali" => "required|date|after:tglsewa",
            "sttspembayaran" => "required|in:Lunas,Belum Dibayar,DP",
            "sttskembali" => "required|in:Sudah Kembali,Belum Kembali",
            "totalharga" => "required|numeric",

            "detail.*.alat_id" => "required|exists:alats,id",
            "detail.*.jumlah" => "required|numeric",
            "detail.*.subharga" => "required|numeric",
        ];
        if ($this->isMethod("PATCH")) {
            $rule = [
                "sttspembayaran" => "nullable|in:Lunas,Belum Dibayar,DP",
                "sttskembali" => "nullable|in:Sudah Kembali,Belum Kembali",
            ];
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
