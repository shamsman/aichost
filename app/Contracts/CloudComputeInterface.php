<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\Cloud\InstanceResult;
use App\DTOs\Cloud\InstanceSpec;
use App\DTOs\Cloud\InstanceStatus;

interface CloudComputeInterface
{
    public function createInstance(InstanceSpec $spec): InstanceResult;

    public function startInstance(string $name, ?string $zone = null): InstanceResult;

    public function stopInstance(string $name, ?string $zone = null): InstanceResult;

    public function restartInstance(string $name, ?string $zone = null): InstanceResult;

    public function getInstanceStatus(string $name, ?string $zone = null): InstanceStatus;

    public function isMockMode(): bool;
}
