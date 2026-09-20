<?php

declare(strict_types=1);

namespace App\DTOs\Hosting;

final readonly class SsoUrlResult
{
    public function __construct(
        public bool $success,
        public string $username,
        public string $url,
        public ?int $expiresAt = null,
        public ?string $message = null,
        public bool $mock = false,
    ) {}

    public function toArray(): array
    {
        return [
            'success'   => $this->success,
            'username'  => $this->username,
            'url'       => $this->url,
            'expiresAt' => $this->expiresAt,
            'message'   => $this->message,
            'mock'      => $this->mock,
        ];
    }
}
