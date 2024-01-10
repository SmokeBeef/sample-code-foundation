<?php
namespace App\Services;

class Service
{
    private array|null $data = null;
    private string $errorMessage = "";
    private int $errorCode;

    private $maxTake = 50;
    private $totalData = 0;
    private $page = 0;
    private $perPage = 0;


    protected function calcTakeSkip($page, $take = 25): array
    {
        if ($take > $this->maxTake) {
            $take = $this->maxTake;
        }
        if ($take < 1) {
            $take = 1;
        }
        if ($page < 1) {
            $page = 1;
        }
        $skip = $take * ($page - 1);

        $this->page = $page;
        $this->perPage = $take;
        return ["take" => $take, "skip" => $skip];
    }

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