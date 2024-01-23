<?php

namespace App\DTO\Penyewaan;

use App\DTO\QueryDTO;

class PenyewaanQueryDTO extends QueryDTO
{

    protected array $fields = [
        "penyewaan_id",
        "penyewaan_pelanggan_id",
        "penyewaan_tglsewa",
        "penyewaan_tglkembali",
        "penyewaan_sttspembayaran",
        "penyewaan_totalharga",
        "created_at"
    ];

    protected array $pelangganFields = [
        "pelanggan_id",
        "pelanggan_nama",
        "pelanggan_alamat",
        "pelanggan_notelp",
        "pelanggan_email",
        "created_at"
    ];
    protected array $penyewaanDetailFields = [
        "penyewaan_detail_alat_id",
        "penyewaan_detail_jumlah",
        "penyewaan_detail_subharga",
    ];


    public function getField(): array
    {
        $fields = array_map(function ($field) {
            return 'penyewaans.' . $field;
        }, $this->fields);

        $pelangganFields = array_map(function ($field) {
            return 'pelanggans.' . $field;
        }, $this->pelangganFields);
        $penyewaanDetailFields = array_map(function ($field) {
            return 'penyewaan_details.' . $field;
        }, $this->penyewaanDetailFields);

        return [...$fields, ...$pelangganFields, ...$penyewaanDetailFields];
    }

}