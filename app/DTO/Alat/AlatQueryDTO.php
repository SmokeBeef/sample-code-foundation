<?php
namespace App\DTO\Alat;

use App\DTO\QueryDTO;

class AlatQueryDTO extends QueryDTO
{
    protected array $fields = ["alat_id", "alat_kategori_id", "alat_nama", "alat_deskripsi", "alat_hargaperhari", "alat_stok"];
    protected array $kategoriColumn = ["kategori_id", "kategori_nama"];
    protected ?int $id = null;


    
    public function getId(): ?int
    {
        return $this->id;
    }

    protected function setId($id): void
    {
        $this->id = $id;
    }


    
}