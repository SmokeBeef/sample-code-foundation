<?php

namespace App\DTO;

class BaseDTO
{
    private int $maxLimit = 50;



    protected function calcLimitOffset($page, $perPage): array
    {
        $limit = $perPage;
        if (!is_numeric($page))
            $page = 1;
        if (!is_numeric($perPage))
            $limit = 25;

        if ($limit > $this->maxLimit) {
            $limit = $this->maxLimit;
        }
        if ($limit < 1) {
            $limit = 1;
        }
        if ($page < 1) {
            $page = 1;
        }
        $offset = $limit * ($page - 1);

        $this->page = $page;
        $this->perPage = $limit;
        return ["limit" => $limit, "offset" => $offset];
    }

}