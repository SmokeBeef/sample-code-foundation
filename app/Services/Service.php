<?php
namespace App\Services;

// - DTO
//     - BaseDTO
//     - QueryDTO
//     - MutationDTO
//     - Alat
//         - AlatQuery
//         - Alat Mutation

// - Operation
//     - BaseOperation
//     - Operation
class Service
{

    // operation = result, success, code, message, perpage, page, totalresults, totalpage -> gettr setter
    // {
    //     success,
    //     code,
    //     message,
    //     data -> get all [{}], get 1 {},
    //     meta: {
    //         perpage, $this->getPerPage()
    //         page,
    //         totalResult,
    //         totalPage
    //     },
    // }

    // result, perpage, page, totalResult. totalPage, limit, search -> query dto

    private array|null $data = null;
    private string $errorMessage = "";
    private int $errorCode;

    private $maxTake = 50; // -> limit
    private $totalData = 0;
    private $page = 0;
    private $perPage = 0;



    // getter
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
    public function getCode(): int
    {
        return $this->errorCode;
    }
    public function getData(): array|null
    {
        return $this->data;
    }
    public function getTotalData()
    {
        return $this->totalData;
    }
    public function getPaginate(): array
    {
        return [
            "page" => $this->page,
            "perPage" => $this->perPage
        ];
    }
    public function getPageNow(): int
    {
        return $this->page;
    }
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getMetaPagination(): array
    {
        return [
            "total_data" => $this->totalData,
            "per_page" => $this->perPage,
            "current_page" => $this->page,
            "total_page" => ceil($this->totalData / $this->perPage),
        ];
    }



    // setter
    protected function setError(string $msg, int $code): void
    {
        $this->errorMessage = $msg;
        $this->errorCode = $code;
    }
    protected function setData(array $data): void
    {
        $this->data = $data;
    }

    protected function setTotalData($total)
    {
        $this->totalData = $total;
    }

    protected function setPaginate($page, $perPage): void
    {
        $this->perPage = $perPage;
        $this->page = $page;
    }

}