<?php
namespace App\DTO\Alat;

use App\DTO\QueryDTO;

class AlatQueryDTO extends QueryDTO
{
    protected array $column = ["alat_id", "alat_nama", "alat_deskripsi", "alat_hargaperhari", "alat_stok", "created_at"];
}