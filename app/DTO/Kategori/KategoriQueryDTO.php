<?php
namespace App\DTO\Kategori;

use App\DTO\QueryDTO;

class KategoriQueryDTO extends QueryDTO
{
    protected array $column = ["kategori_id", "kategori_nama", "created_at"];
    protected array $alatColumn = ["alat_id", "alat_nama", "alat_deskripsi", "alat_hargaperhari", "alat_stok", "created_at"];

    protected $id;

    public function getAlatColumn (): array
    {
        return $this->alatColumn;
    }

}