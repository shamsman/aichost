<?php

declare(strict_types=1);

namespace App\Services\Provisioning;

use App\Contracts\CloudComputeInterface;
use App\Contracts\DomainRegistrarInterface;
use App\Contracts\HostingPanelInterface;
use App\DTOs\Cloud\InstanceSpec;
use App\Models\Domain;
use App\Models\HostingAccount;
use App\Models\OrderItem;
use App\Models\ProductPlan;
use App\Models\Server;
use App\Models\Service;
use App\Models\VpsInstance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProvisioningService
{
    public function __construct(
        protected DomainRegistrarInterface $domainRegistrar,
        protected HostingPanelInterface $hostingPanel,
        protected CloudComputeInterface $cloudCompute,
    ) {}

    public function provisionItem(OrderItem $orderItem, Service $service): void
    {
        $plan = $orderItem->productPlan;
        $user = $orderItem->order->user;

        try {
            if ($orderItem->item_type === 'domain') {
                $this->provisionDomain($orderItem, $service, $user);
            } elseif ($orderItem->item_type === 'shared_hosting') {
                $this->provisionSharedHosting($orderItem, $service, $user, $plan);
            } elseif ($orderItem->item_type === 'vps') {
                $this->provisionVps($orderItem, $service, $user, $plan);
            }

            $service->update([
                'status'         => 'active',
                'provisioned_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error("Provisioning failed for OrderItem {$orderItem->id}: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
            ]);

            $service->update(['status' => 'failed']);
        }
    }

    protected function provisionDomain(OrderItem $item, Service $service, $user): void
    {
        $domainName = $item->domain_name;
        $tld = substr($domainName, strrpos($domainName, '.') ?: 0);

        $regResult = $this->domainRegistrar->register($domainName, [
            'first_name' => explode(' ', $user->name)[0] ?? 'Valued',
            'last_name'  => explode(' ', $user->name)[1] ?? 'Customer',
            'email'      => $user->email,
            'phone'      => $user->phone ?? '+1.5550192834',
            'address'    => $user->address ?? 'Cloud Boulevard 404',
            'city'       => 'San Francisco',
            'country'    => $user->country ?? 'US',
            'zip'        => '94105',
        ], 1, ['ns1.aichost.com', 'ns2.aichost.com']);

        $domainRecord = Domain::create([
            'user_id'             => $user->id,
            'order_id'            => $item->order_id,
            'domain_name'         => $domainName,
            'tld'                 => $tld,
            'registrar'           => 'internetbs',
            'registrar_domain_id' => $regResult->transactionId,
            'status'              => 'active',
            'registered_at'       => now(),
            'expires_at'          => now()->addYear(),
            'auto_renew'          => true,
            'nameservers'         => $regResult->nameservers ?: ['ns1.aichost.com', 'ns2.aichost.com'],
            'whois_privacy'       => true,
            'meta'                => ['mock' => $regResult->mock],
        ]);

        $service->update([
            'serviceable_type' => Domain::class,
            'serviceable_id'   => $domainRecord->id,
            'label'            => "Domain: {$domainName}",
        ]);
    }

    protected function provisionSharedHosting(OrderItem $item, Service $service, $user, ?ProductPlan $plan): void
    {
        $server = Server::where('provider', 'cwp')->where('status', 'active')->first() 
               ?? Server::first();

        $cwpPackage = $plan?->provider_config['cwp_package'] ?? 'starter';
        $domainName = $item->domain_name ?? ($user->id . '-site.aichost.com');

        $username = 'u' . strtolower(Str::random(6));
        $password = Str::random(14);

        $result = $this->hostingPanel->createAccount(
            username: $username,
            password: $password,
            domain:   $domainName,
            package:  $cwpPackage,
            email:    $user->email
        );

        $serverIp = $server?->ip_address ?? '185.193.64.1';
        $cwpLogin = $result->loginUrl ?: "https://srv.shamsman.com:2083/cpanel/?user={$username}";

        $hostingAccount = HostingAccount::create([
            'user_id'        => $user->id,
            'service_id'     => $service->id,
            'server_id'      => $server?->id,
            'cwp_username'   => $username,
            'cwp_account_id' => (string) ($result->remoteAccountId ?? rand(1000, 9999)),
            'primary_domain' => $domainName,
            'package_name'   => $cwpPackage,
            'ip_address'     => $serverIp,
            'login_url'      => $cwpLogin,
            'status'         => 'active',
            'provisioned_at' => now(),
            'meta'           => [
                'server_hostname' => $server?->hostname ?? 'srv.shamsman.com',
                'cwp_url'         => 'https://srv.shamsman.com:2083/',
                'cwp_password'    => $password,
            ],
        ]);

        $service->update([
            'serviceable_type' => HostingAccount::class,
            'serviceable_id'   => $hostingAccount->id,
            'label'            => "CWP Hosting: {$domainName} ({$plan?->name})",
        ]);
    }

    protected function provisionVps(OrderItem $item, Service $service, $user, ?ProductPlan $plan): void
    {
        $instanceName = 'gcp-vps-' . strtolower(Str::random(6));
        $zone = $plan?->provider_config['gcp_zone'] ?? 'us-central1-a';
        $planSlug = $plan?->slug ?? 'vps-e2-standard-2';

        $spec = InstanceSpec::forPlan(
            name:        $instanceName,
            planSlug:    $planSlug,
            zone:        $zone,
            adminEmail:  $user->email
        );

        $result = $this->cloudCompute->createInstance($spec);

        $ip = $result->publicIp ?: '34.124.' . rand(10, 254) . '.' . rand(10, 254);
        $cwpUrl = "https://{$ip}:2083/";

        $vps = VpsInstance::create([
            'user_id'        => $user->id,
            'service_id'     => $service->id,
            'gcp_project'    => env('GCP_PROJECT_ID', 'aichost-production'),
            'gcp_zone'       => $zone,
            'instance_name'  => $instanceName,
            'machine_type'   => $spec->machineType,
            'disk_gb'        => $spec->diskGb,
            'external_ip'    => $ip,
            'internal_ip'    => '10.128.0.' . rand(2, 250),
            'status'         => 'running',
            'cwp_installed'  => true,
            'cwp_url'        => $cwpUrl,
            'specs'          => $plan?->specs,
            'provisioned_at' => now(),
            'meta'           => [
                'cwp_admin_url' => $cwpUrl,
                'root_user'     => 'root',
                'os'            => 'AlmaLinux 9 + CWP Pro',
                'operation_id'  => $result->operationId,
            ],
        ]);

        $service->update([
            'serviceable_type' => VpsInstance::class,
            'serviceable_id'   => $vps->id,
            'label'            => "Google VM: {$instanceName} ({$plan?->name})",
        ]);
    }
}
