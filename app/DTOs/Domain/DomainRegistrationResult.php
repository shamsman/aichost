<?php

declare(strict_types=1);

namespace App\DTOs\Domain;

final readonly class DomainRegistrationResult
{
    public function __construct(
        public bool $success,
        public string $domain,
        public ?string $transactionId = null,
        public ?string $expiresAt = null,
        public array $nameservers = [],
        public ?string $message = null,
        public bool $mock = false,
        public array $raw = [],
    ) {}

    public function toArray(): array
    {
        return [
            'success'       => $this->success,
            'domain'        => $this->domain,
            'transactionId' => $this->transactionId,
            'expiresAt'     => $this->expiresAt,
            'nameservers'   => $this->nameservers,
            'message'       => $this->message,
            'mock'          => $this->mock,
        ];
    }
}
