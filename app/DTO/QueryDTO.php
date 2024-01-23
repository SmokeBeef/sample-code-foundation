<?php

namespace App\DTO;

use App\DTO\BaseDTO;

class QueryDTO extends BaseDTO
{
    protected array $fields = ["*"]; //  fields
    protected int $offset;
    protected int $page;
    protected int $limit = 25;
    protected ?string $search;
    protected string $sortOrder;
    protected ?string $sortBy;

    public function __construct(array $configs)
    {
        $validatedConfigs = $this->queryConfigsValidation($configs, $this->fields);
        $this->setLimit($validatedConfigs["limit"]);
        $this->setOffset($validatedConfigs["offset"]);
        $this->setPage($validatedConfigs["page"]);
        $this->setSortOrder($validatedConfigs["sortOrder"]);
        $this->setSortBy($validatedConfigs["sortBy"]);
        $this->setSearch($validatedConfigs["search"]);
    }



    public function getField(): array
    {
        return $this->fields;
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

    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    protected function setField(array $fields): void
    {
        $this->fields = $fields;
    }

    protected function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    protected function setPage(int $page): void
    {
        $this->page = $page;
    }

    protected function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    protected function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    protected function setSortOrder(string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    protected function setSortBy(?string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }
}