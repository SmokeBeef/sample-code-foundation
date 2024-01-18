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
            "penyewaan_pelanggan_id" => "required|numeric|exists:pelanggans,pelanggan_id",
            "penyewaan_tglsewa" => "required|date|after_or_equal:today",
            "penyewaan_tglkembali" => "required|date|after:tglsewa",
            "penyewaan_sttspembayaran" => "required|in:Lunas,Belum Dibayar,DP",
            "penyewaan_sttskembali" => "required|in:Sudah Kembali,Belum Kembali",
            "penyewaan_totalharga" => "required|numeric",

            "detail.*.penyewaan_detail_alat_id" => "required|exists:alats,alat_id",
            "detail.*.penyewaan_detail_jumlah" => "required|numeric",
            "detail.*.penyewaan_detail_subharga" => "required|numeric",
        ];
        if ($this->isMethod("PATCH")) {
            $rule = [
                "penyewaan_sttspembayaran" => "nullable|in:Lunas,Belum Dibayar,DP",
                "penyewaan_sttskembali" => "nullable|in:Sudah Kembali,Belum Kembali",
            ];
        }

        return $rule;
    }
    protected function failedValidation(Validator $validator)
    {
        $operation = $this->isMethod("POST") ? "create" : "update";
        throw new HttpResponseException(response()->json([
            "success" => false,
            "code" => 400,
            "message" => "failed to $operation penyewaan",
            "errors" => $validator->getMessageBag()
        ], 400));
    }
    
}
