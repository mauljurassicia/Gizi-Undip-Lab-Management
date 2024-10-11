<?php

namespace App\Helpers;

use App\Enums\ResponseCodeEnum;
use Illuminate\Http\JsonResponse;

class ResponseJson
{

    private array $details;

    private function __construct(
        private int $status,
        private string $message,
        private mixed $data = null,
        private mixed $error = null
    ) {
        $this->details = [
            "uri" => request()->path(),
            "method" => request()->getMethod(),
            "status_code" => $this->status,
            "query" => request()->getQueryString(),
        ];
    }

    public static function make(ResponseCodeEnum|int $status, string $message, mixed $data = null, mixed $error = null): self
    {
        return new self($status instanceof ResponseCodeEnum ? $status->getCode() : $status, $message, $data, $error);
    }

    private function getResponse(): array
    {
        return [
            "details" => $this->details,
            "data" => $this->data,
            "errors" => $this->error,
            "message" => $this->message,
            "valid" => $this->status < 400
        ];
    }

    public function __get(string $name): mixed
    {
        return property_exists($this, $name) ? $this->{$name} : null;
    }

    public function send(): JsonResponse
    {
        return response()->json($this->getResponse(), $this->status);
    }
}
