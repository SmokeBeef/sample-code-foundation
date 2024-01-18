<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class QueryDTO extends BaseDTO
{
    protected array $column = ["*"]; //  fields
    protected int $offset;
    protected int $page;
    protected int $limit = 25;
    protected ?string $search;
    protected string $sort;
    protected ?string $sortBy;

    public function __construct(array $configs)
    {
        $page = $configs["page"];
        $perPage = $configs["perpage"];
        $sortBy = $configs["sortBy"];
        $sortOrder = $configs["sortOrder"];
        $search = $configs["search"];

        ["limit" => $limit, "offset" => $offset] = $this->calcLimitOffset($page, $perPage);

        $this->page = ceil(($offset + 1) / $limit);

        $this->setLimit($limit);
        $this->setOffset($offset);

        $this->setSearch($search ?? "");


        // check query sort is asc or desc
        $this->sort = in_array($sortOrder, ["asc", "desc"]) ? $sortOrder : "asc";

        // check query sortBy is same as the column
        $isColumn = in_array($sortBy, $this->column);
        $this->sortBy = ($sortBy && $isColumn) ? $sortBy : "created_at";
        
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

    protected function setColumn(array $column): void {
        $this->column = $column;
    }

    protected function setOffset(int $offset): void {
        $this->offset = $offset;
    }

    protected function setPage(int $page): void {
        $this->page = $page;
    }

    protected function setLimit(int $limit): void {
        $this->limit = $limit;
    }

    protected function setSearch(?string $search): void {
        $this->search = $search;
    }

    protected function setSort(string $sort): void {
        $this->sort = $sort;
    }

    protected function setSortBy(?string $sortBy): void {
        $this->sortBy = $sortBy;
    }
}