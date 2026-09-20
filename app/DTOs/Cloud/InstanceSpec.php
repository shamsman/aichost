<?php

declare(strict_types=1);

namespace App\DTOs\Cloud;

use App\Support\Cloud\StartupScriptFactory;

final readonly class InstanceSpec
{
    public function __construct(
        public string $name,
        public string $machineType = 'e2-standard-2',
        public string $zone = 'us-central1-a',
        public int $diskGb = 80,
        public string $image = 'projects/almalinux-cloud/global/images/family/almalinux-9',
        public string $diskType = 'pd-balanced',
        public string $network = 'global/networks/default',
        public bool $assignPublicIp = true,
        public array $labels = [],
        public array $tags = ['http-server', 'https-server', 'cwp-server'],
        public string $startupScript = '',
    ) {}

    public static function forPlan(
        string $name,
        string $planSlug,
        string $zone = 'us-central1-a',
        ?string $adminEmail = 'admin@aichost.com'
    ): self {
        [$machineType, $diskGb] = match ($planSlug) {
            'vps-n2-standard-4' => ['n2-standard-4', 160],
            'vps-a2-highgpu-1g' => ['a2-highgpu-1g', 500],
            default             => ['e2-standard-2', 80],
        };

        return new self(
            name: $name,
            machineType: $machineType,
            zone: $zone,
            diskGb: $diskGb,
            startupScript: StartupScriptFactory::cwp($adminEmail ?? 'admin@aichost.com'),
            labels: ['app' => 'aichost', 'managed_cwp' => 'true'],
        );
    }
}
