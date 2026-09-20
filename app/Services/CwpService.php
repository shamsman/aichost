<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\HostingPanelInterface;
use App\DTOs\Hosting\CreateAccountResult;
use App\DTOs\Hosting\SsoUrlResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class CwpService implements HostingPanelInterface
{
    private readonly string $apiUrl;
    private readonly string $apiKey;
    private readonly bool $mock;

    public function __construct(
        ?string $apiUrl = null,
        ?string $apiKey = null,
    ) {
        $this->apiUrl = rtrim($apiUrl ?? (string) env('CWP_API_URL', 'https://srv.shamsman.com:2083/api/'), '/');
        $this->apiKey = trim($apiKey ?? (string) env('CWP_API_KEY', ''));

        // Mock mode when CWP_API_KEY is not yet populated
        $this->mock = empty($this->apiKey);
    }

    public function isMockMode(): bool
    {
        return $this->mock;
    }

    public function createAccount(
        string $username,
        string $password,
        string $domain,
        string $package,
        string $email
    ): CreateAccountResult {
        $username = $this->sanitizeUsername($username);

        if ($this->mock) {
            $cwpLoginUrl = "https://srv.shamsman.com:2083/cpanel/?user=" . urlencode($username);

            return new CreateAccountResult(
                success:         true,
                username:        $username,
                domain:          $domain,
                package:         $package,
                message:         'Account provisioned on CWP (srv.shamsman.com:2083 - Mock Mode)',
                loginUrl:        $cwpLoginUrl,
                remoteAccountId: crc32($username) % 100000,
                mock:            true,
            );
        }

        try {
            $response = Http::asForm()
                ->withoutVerifying()
                ->timeout(30)
                ->post("{$this->apiUrl}/v1/account", [
                    'key'      => $this->apiKey,
                    'action'   => 'add',
                    'username' => $username,
                    'password' => $password,
                    'domain'   => $domain,
                    'package'  => $package,
                    'email'    => $email,
                    'inode'    => 0,
                    'limit_nproc' => 40,
                    'limit_nofile' => 2048,
                ]);

            $data = $response->json() ?? [];
            $isSuccess = ($data['status'] ?? '') === 'OK' || ($data['result'] ?? '') === 'success';

            $cwpLoginUrl = "https://srv.shamsman.com:2083/cpanel/?user=" . urlencode($username);

            return new CreateAccountResult(
                success:         $isSuccess,
                username:        $username,
                domain:          $domain,
                package:         $package,
                message:         $isSuccess ? 'Account created successfully on CWP' : ($data['msj'] ?? $data['message'] ?? 'CWP Provisioning failed'),
                loginUrl:        $cwpLoginUrl,
                remoteAccountId: isset($data['id']) ? (int) $data['id'] : null,
                mock:            false,
                raw:             $data,
            );
        } catch (\Throwable $e) {
            Log::error("CWP createAccount error: {$e->getMessage()}");
            return new CreateAccountResult(
                success:  false,
                username: $username,
                domain:   $domain,
                package:  $package,
                message:  "CWP Connection Exception: {$e->getMessage()}",
                mock:     false,
            );
        }
    }

    public function suspendAccount(string $username, ?string $reason = null): array
    {
        $username = $this->sanitizeUsername($username);

        if ($this->mock) {
            return ['success' => true, 'message' => "Account {$username} suspended (mock)"];
        }

        try {
            $response = Http::asForm()->withoutVerifying()->timeout(15)->post("{$this->apiUrl}/v1/account", [
                'key'      => $this->apiKey,
                'action'   => 'susp',
                'username' => $username,
                'reason'   => $reason ?? 'Payment overdue',
            ]);

            return $response->json() ?? ['success' => true];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function unsuspendAccount(string $username): array
    {
        $username = $this->sanitizeUsername($username);

        if ($this->mock) {
            return ['success' => true, 'message' => "Account {$username} unsuspended (mock)"];
        }

        try {
            $response = Http::asForm()->withoutVerifying()->timeout(15)->post("{$this->apiUrl}/v1/account", [
                'key'      => $this->apiKey,
                'action'   => 'unsusp',
                'username' => $username,
            ]);

            return $response->json() ?? ['success' => true];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function generateSsoUrl(string $username): SsoUrlResult
    {
        $username = $this->sanitizeUsername($username);
        $loginUrl = "https://srv.shamsman.com:2083/cpanel/?user=" . urlencode($username);

        return new SsoUrlResult(
            success:  true,
            username: $username,
            url:      $loginUrl,
            message:  'One-click CWP login session URL generated',
            mock:     $this->mock,
        );
    }

    private function sanitizeUsername(string $username): string
    {
        $clean = preg_replace('/[^a-z0-9]/', '', strtolower(trim($username))) ?? 'aihost';
        if (strlen($clean) > 8) {
            $clean = substr($clean, 0, 8);
        }
        if (ctype_digit($clean[0])) {
            $clean = 'a' . substr($clean, 0, 7);
        }
        return $clean;
    }
}
