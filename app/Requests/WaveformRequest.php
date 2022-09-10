<?php

namespace App\Requests;

use App\Lib\Request;

class WaveformRequest extends Request
{
    private bool $isValid = true;
    private array $errors = [];

    public function __construct($params = [])
    {
        parent::__construct($params);
    }

    public function isValid(array $parameters): bool
    {
        if (!$this->audioStreamExists($parameters['filename'])) {
            $this->addError('File does not exists.');
            $this->isValid = false;
        }

        // Validation for file format

        return $this->isValid;
    }

    private function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function audioStreamExists(string $filename): bool
    {
        return file_exists(getcwd() . '/assets/' . $filename . '/user_channel') &&
            file_exists(getcwd() . '/assets/' . $filename . '/user_channel');
    }
}
