<?php
namespace App\Services;

trait ServiceTrait
{
    public array|null $data = null;
    public string $errorMessage;
    public int $errorCode;

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
    public function getCode(): int
    {
        return $this->errorCode;
    }
    public function setError(string $msg, int $code): void
    {
        $this->errorMessage = $msg;
        $this->errorCode = $code;
    }
    public function getData(): array|null
    {
        return $this->data;
    }
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}