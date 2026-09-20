<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\Hosting\CreateAccountResult;
use App\DTOs\Hosting\SsoUrlResult;

interface HostingPanelInterface
{
    public function createAccount(
        string $username,
        string $password,
        string $domain,
        string $package,
        string $email
    ): CreateAccountResult;

    public function suspendAccount(string $username, ?string $reason = null): array;

    public function unsuspendAccount(string $username): array;

    public function generateSsoUrl(string $username): SsoUrlResult;

    public function isMockMode(): bool;
}
