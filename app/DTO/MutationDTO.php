<?php

namespace App\DTO;

class MutationDTO
{
    protected array $data;
    protected $id = null;

    public function __construct(array $data, int|string $id = null)
    {
        $this->data = $data;
        if (is_numeric($id)) {
            $this->id = $id;
        }
    }

    public function getData(): array
    {
        return $this->data;
    }
    public function getId(): null|int|string
    {
        return $this->id;
    }

}