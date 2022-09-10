<?php

namespace App\Lib;

class Response
{
    public const HTTP_CODE_OK = 200;
    public const HTTP_CODE_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_CODE_NOT_FOUND = 404;

    private array $data;
    private array $errors;
    private int $statusCode;

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    public function toJSON(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');

        if (!empty($this->errors)) {
            echo json_encode($this->errors);
        } else {
            echo json_encode($this->data);
        }
    }
}
