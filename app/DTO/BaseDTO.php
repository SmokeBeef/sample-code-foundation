<?php

namespace App\DTO;

class BaseDTO
{



    protected function queryConfigsValidation(array $configs, array $fields): array
    {
        $sortbyList = ["asc", "desc"];

        $page = $configs["page"] ?? 1;
        $perPage = $configs["perpage"] ?? 25;
        $sortBy = $configs["sortBy"] ?? "";
        $sortOrder = $configs["sortOrder"] ?? "asc";

        if ($page < 1 || !is_numeric($page))
            $page = 1;

        [$limit, $offset] = $this->calcLimitOffset($page, $perPage);

        if (!in_array($sortOrder, $sortbyList)) {
            $sortOrder = "asc";
        }

        if (!in_array($sortBy, $fields)) {
            $sortBy = $fields[0];
        }

        return [
            ...$configs,
            "page" => $page,
            "offset" => $offset,
            "limit" => $limit,
            "sortOrder" => $sortOrder,
            "sortBy" => $sortBy
        ];

    }

    protected function calcLimitOffset($page, $perPage): array
    {
        $maxLimit = 50;
        $limit = $perPage;


        if (!is_numeric($perPage))
            $limit = 25;

        if ($limit > $maxLimit) {
            $limit = $maxLimit;
        }
        if ($limit < 1) {
            $limit = 1;
        }

        $offset = $limit * ($page - 1);

        return [$limit, $offset];
    }

}