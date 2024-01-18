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

    public static function onSuccess(string $message, int $code, mixed $result, ?array $paginate = null): self
    {
        $operation = new Operation();
        $operation->setMessage($message);
        $operation->setCode($code);
        $operation->setResult($result);
        $operation->setIsSuccess(true);

        return $operation;
    }
    public static function onPaginate(string $message, mixed $result, int $total, int $page, int $perPage): self
    {
        $operation = new Operation();
        $operation->setMessage($message);
        $operation->setCode(200);
        $operation->setResult($result);
        $operation->setIsSuccess(true);

        $operation->setPaginate($page, $perPage);
        $operation->setTotal($total);

        return $operation;
    }

    protected function setMessageCode(string $message, int $code): void
    {
        $this->setCode($code);
        $this->setMessage($message);
    }

    protected function setIsSuccess(bool $isSuccess): void
    {
        $this->isSuccess = $isSuccess;
    }

    public function setPaginate(int $page, int $perPage): void
    {
        $this->page = $page;
        $this->perPage = $perPage;
    }

    protected function setOnSuccess(string $message, int $code, array $result): void
    {
        $this->setMessage($message);
        $this->setCode($code);
        $this->setResult($result);
        $this->setIsSuccess(true);
    }
    protected function setOnError(string $message, int $code): void
    {
        $this->setMessage($message);
        $this->setCode($code);
        $this->setIsSuccess(false);
    }










    // Protected setter for $result
    protected function setResult(?array $result): void
    {
        $this->result = $result;
    }

    // Public getter for $result
    public function getResult(): ?array
    {
        return $this->result;
    }

    // Protected setter for $message
    protected function setMessage(string $message): void
    {
        $this->message = $message;
    }

    // Public getter for $message
    public function getMessage(): string
    {
        return $this->message;
    }

    // Protected setter for $code
    protected function setCode(int $code): void
    {
        $this->code = $code;
    }

    // Public getter for $code
    public function getCode(): int
    {
        return $this->code;
    }

    // Protected setter for $total
    protected function setTotal($total): void
    {
        $this->total = $total;
    }

    // Public getter for $total
    public function getTotal()
    {
        return $this->total;
    }

    // Protected setter for $page
    protected function setPage($page): void
    {
        $this->page = $page;
    }

    // Public getter for $page
    public function getPage()
    {
        return $this->page;
    }

    // Protected setter for $perPage
    protected function setPerPage($perPage): void
    {
        $this->perPage = $perPage;
    }

    // Public getter for $perPage
    public function getPerPage()
    {
        return $this->perPage;
    }

}