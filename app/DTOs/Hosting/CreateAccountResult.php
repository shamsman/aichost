<?php

declare(strict_types=1);

namespace App\DTOs\Hosting;

final readonly class CreateAccountResult
{
    public function __construct(
        public bool $success,
        public string $username,
        public string $domain,
        public string $package,
        public ?string $message = null,
        public ?string $loginUrl = null,
        public ?int $remoteAccountId = null,
        public bool $mock = false,
        public array $raw = [],
    ) {}

    public function toArray(): array
    {
        return [
            'success'         => $this->success,
            'username'        => $this->username,
            'domain'          => $this->domain,
            'package'         => $this->package,
            'message'         => $this->message,
            'loginUrl'        => $this->loginUrl,
            'remoteAccountId' => $this->remoteAccountId,
            'mock'            => $this->mock,
        ];
    }
}
