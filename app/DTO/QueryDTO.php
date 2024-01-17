<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class QueryDTO extends BaseDTO
{
    protected array $column = ["*"];
    protected int $offset;
    protected int $page;
    protected int $limit = 25;
    protected ?string $search;
    protected string $sort;
    protected ?string $sortBy;

    public function __construct(int $page, int $perPage, array $filter)
    {

        ["limit" => $limit, "offset" => $offset] = $this->calcLimitOffset($page, $perPage);

        $this->page = ceil(($offset + 1) / $limit);

        $this->limit = $limit;
        $this->offset = $offset;


        $this->search = $filter["search"] ?? "";


        // check query sort is asc or desc
        $this->sort = in_array($filter["sort"], ["asc", "desc"]) ? $filter["sort"] : "asc";

        // check query sortBy is same as the column
        $isColumn = in_array($filter["sortBy"], $this->column);
        $this->sortBy = ($filter["sortBy"] && $isColumn) ? $filter["sortBy"] : "created_at";

    }


    public function getColumn(): array
    {
        return $this->column;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
    public function getPage(): int
    {
        return $this->page;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

}