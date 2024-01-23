<?php

namespace App\DTO\Penyewaan;

use App\DTO\MutationDTO;

class PenyewaanMutationDTO extends MutationDTO
{

    protected $dataDetailPenyewaan = [];

    public function __construct(array $data, $id = null)
    {
        if ($id) {
            $this->data = $data;
            $this->id = $id;
        } else {
            $this->dataDetailPenyewaan = $data["detail"];
            unset($data["detail"]);
            $this->data = $data;
        }
    }

    public function getDataDetailPenyewaan(): array
    {
        return $this->dataDetailPenyewaan;
    }

}