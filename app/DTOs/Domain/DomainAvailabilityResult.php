<?php

declare(strict_types=1);

namespace App\DTOs\Domain;

final readonly class DomainAvailabilityResult
{
    public function __construct(
        public string $domain,
        public bool $available,
        public bool $premium = false,
        public ?float $price = null,
        public ?string $currency = 'USD',
        public ?string $reason = null,
        public bool $mock = false,
        public array $raw = [],
    ) {}

    public function toArray(): array
    {
        return [
            'domain'    => $this->domain,
            'available' => $this->available,
            'premium'   => $this->premium,
            'price'     => $this->price,
            'currency'  => $this->currency,
            'reason'    => $this->reason,
            'mock'      => $this->mock,
        ];
    }
}
