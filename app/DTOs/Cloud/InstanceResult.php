<?php

declare(strict_types=1);

namespace App\DTOs\Cloud;

final readonly class InstanceResult
{
    public function __construct(
        public bool $success,
        public string $name,
        public string $zone,
        public string $status,
        public ?string $publicIp = null,
        public ?string $operationId = null,
        public ?string $message = null,
        public bool $mock = false,
        public array $raw = [],
    ) {}

    public function toArray(): array
    {
        return [
            'success'     => $this->success,
            'name'        => $this->name,
            'zone'        => $this->zone,
            'status'      => $this->status,
            'publicIp'    => $this->publicIp,
            'operationId' => $this->operationId,
            'message'     => $this->message,
            'mock'        => $this->mock,
        ];
    }
}
