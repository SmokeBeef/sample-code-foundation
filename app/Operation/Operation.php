<?php 
namespace App\Operation;

class Operation extends BaseOperation
{
    private array|null $result = null;
    private string $message = "";
    private int $code;
    private $total = 0;
    private $page = 0;
    private $perPage = 0;
    public bool $isSuccess;

    protected function setMessageCode(string $message, int $code): void
    {
        $this->setCode($code);
        $this->setMessage($message);
    }

    protected function setIsSuccess(bool $isSuccess): void
    {
        $this->isSuccess = $isSuccess;
    }

    protected function setPaginate(int $page, int $perPage): void
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }











    // Protected setter for $result
    protected function setResult(?array $result): void {
        $this->result = $result;
    }

    // Public getter for $result
    public function getResult(): ?array {
        return $this->result;
    }

    // Protected setter for $message
    protected function setMessage(string $message): void {
        $this->message = $message;
    }

    // Public getter for $message
    public function getMessage(): string {
        return $this->message;
    }

    // Protected setter for $code
    protected function setCode(int $code): void {
        $this->code = $code;
    }

    // Public getter for $code
    public function getCode(): int {
        return $this->code;
    }

    // Protected setter for $total
    protected function setTotal($total): void {
        $this->total = $total;
    }

    // Public getter for $total
    public function getTotal() {
        return $this->total;
    }

    // Protected setter for $page
    protected function setPage($page): void {
        $this->page = $page;
    }

    // Public getter for $page
    public function getPage() {
        return $this->page;
    }

    // Protected setter for $perPage
    protected function setPerPage($perPage): void {
        $this->perPage = $perPage;
    }

    // Public getter for $perPage
    public function getPerPage() {
        return $this->perPage;
    }

}