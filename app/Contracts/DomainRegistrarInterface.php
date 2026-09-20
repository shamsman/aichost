<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\Domain\DomainAvailabilityResult;
use App\DTOs\Domain\DomainRegistrationResult;

interface DomainRegistrarInterface
{
    public function checkAvailability(string $domain): DomainAvailabilityResult;

    public function register(
        string $domain,
        array $registrant,
        int $years = 1,
        array $nameservers = []
    ): DomainRegistrationResult;

    public function isMockMode(): bool;
}
