<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\DomainRegistrarInterface;
use App\DTOs\Domain\DomainAvailabilityResult;
use App\DTOs\Domain\DomainRegistrationResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class InternetBsService implements DomainRegistrarInterface
{
    private readonly string $apiKey;
    private readonly string $password;
    private readonly string $baseUrl;
    private readonly bool $mock;

    public function __construct(
        ?string $apiKey = null,
        ?string $password = null,
        ?string $baseUrl = null,
    ) {
        $this->apiKey   = trim($apiKey ?? (string) env('INTERNETBS_API_KEY', ''));
        $this->password = trim($password ?? (string) env('INTERNETBS_PASSWORD', ''));
        $this->baseUrl  = rtrim($baseUrl ?? (string) env('INTERNETBS_API_URL', 'https://api.internet.bs'), '/');

        $isSandbox = filter_var(env('INTERNETBS_SANDBOX', true), FILTER_VALIDATE_BOOLEAN);

        // Fall back to safe deterministic mock mode if no credentials are configured
        $this->mock = empty($this->apiKey) || empty($this->password) || $isSandbox;
    }

    public function isMockMode(): bool
    {
        return $this->mock;
    }

    public function checkAvailability(string $domain): DomainAvailabilityResult
    {
        $domain = $this->normalizeDomain($domain);

        if ($this->mock) {
            return $this->mockAvailabilityCheck($domain);
        }

        try {
            $response = Http::asForm()->timeout(15)->post("{$this->baseUrl}/Domain/Check", [
                'ApiKey'         => $this->apiKey,
                'Password'       => $this->password,
                'ResponseFormat' => 'JSON',
                'Domain'         => $domain,
            ]);

            $data = $response->json() ?? [];
            $status = strtoupper((string) ($data['status'] ?? ''));
            $isAvailable = $status === 'AVAILABLE';

            return new DomainAvailabilityResult(
                domain:    $domain,
                available: $isAvailable,
                premium:   strtoupper((string) ($data['premium'] ?? 'no')) === 'YES',
                price:     isset($data['price']) ? (float) $data['price'] : null,
                currency:  $data['currency'] ?? 'USD',
                reason:    $isAvailable ? null : ($data['status'] ?? 'UNAVAILABLE'),
                mock:      false,
                raw:       $data,
            );
        } catch (\Throwable $e) {
            Log::warning("InternetBS checkAvailability error: {$e->getMessage()}, returning mock response");
            return $this->mockAvailabilityCheck($domain);
        }
    }

    public function register(
        string $domain,
        array $registrant,
        int $years = 1,
        array $nameservers = []
    ): DomainRegistrationResult {
        $domain = $this->normalizeDomain($domain);

        if ($this->mock) {
            return new DomainRegistrationResult(
                success:       true,
                domain:        $domain,
                transactionId: 'IBS-MOCK-' . strtoupper(substr(md5($domain . time()), 0, 10)),
                expiresAt:     now()->addYears($years)->toIso8601String(),
                nameservers:   $nameservers ?: ['ns1.aichost.com', 'ns2.aichost.com'],
                message:       'Domain registered successfully (InternetBS Sandbox/Mock)',
                mock:          true,
            );
        }

        try {
            $params = [
                'ApiKey'         => $this->apiKey,
                'Password'       => $this->password,
                'ResponseFormat' => 'JSON',
                'Domain'         => $domain,
                'Period'         => $years,
                'Firstname'      => $registrant['first_name'] ?? 'Admin',
                'Lastname'       => $registrant['last_name'] ?? 'Customer',
                'Email'          => $registrant['email'] ?? 'admin@aichost.com',
                'Phone'          => $registrant['phone'] ?? '+1.5555555555',
                'Address1'       => $registrant['address'] ?? 'Silicon Way 101',
                'City'           => $registrant['city'] ?? 'San Jose',
                'CountryCode'    => $registrant['country'] ?? 'US',
                'PostalCode'     => $registrant['zip'] ?? '95113',
            ];

            if (!empty($nameservers)) {
                foreach ($nameservers as $idx => $ns) {
                    $params['Ns' . ($idx + 1)] = $ns;
                }
            }

            $response = Http::asForm()->timeout(30)->post("{$this->baseUrl}/Domain/Create", $params);
            $data = $response->json() ?? [];
            $status = strtoupper((string) ($data['status'] ?? ''));

            $isSuccess = $status === 'SUCCESS';

            return new DomainRegistrationResult(
                success:       $isSuccess,
                domain:        $domain,
                transactionId: $data['transactid'] ?? null,
                expiresAt:     $data['expirationdate'] ?? now()->addYears($years)->toIso8601String(),
                nameservers:   $nameservers,
                message:       $isSuccess ? 'Domain registered successfully' : ($data['message'] ?? 'Registration failed'),
                mock:          false,
                raw:           $data,
            );
        } catch (\Throwable $e) {
            Log::error("InternetBS register error: {$e->getMessage()}");
            return new DomainRegistrationResult(
                success: false,
                domain:  $domain,
                message: "Registration error: {$e->getMessage()}",
                mock:    false,
            );
        }
    }

    private function mockAvailabilityCheck(string $domain): DomainAvailabilityResult
    {
        $takenPatterns = ['google', 'apple', 'microsoft', 'amazon', 'facebook', 'openai', 'anthropic'];
        $isTaken = false;

        foreach ($takenPatterns as $pattern) {
            if (str_contains($domain, $pattern)) {
                $isTaken = true;
                break;
            }
        }

        $extension = substr($domain, strrpos($domain, '.') ?: 0);
        $price = match ($extension) {
            '.ai'    => 79.99,
            '.io'    => 39.99,
            '.net'   => 12.99,
            '.cloud' => 18.99,
            default  => 11.99,
        };

        return new DomainAvailabilityResult(
            domain:    $domain,
            available: !$isTaken,
            premium:   false,
            price:     $price,
            currency:  'USD',
            reason:    $isTaken ? 'Domain is already registered by a known entity' : null,
            mock:      true,
        );
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = strtolower(trim($domain));
        return preg_replace('#^https?://#', '', $domain) ?? $domain;
    }
}
