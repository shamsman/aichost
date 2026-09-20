<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPlan;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedAdminUser();
            $this->seedServers();
            $this->seedProducts();
            $this->seedDomainPlans();
            $this->seedSharedHostingPlans();
            $this->seedVpsPlans();
        });
    }

    protected function seedAdminUser(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aichost.com'],
            [
                'name'     => 'AICHost Admin',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'status'   => 'active',
                'balance'  => 1000.00,
                'company'  => 'AICHost Inc.',
                'country'  => 'US',
            ]
        );

        User::updateOrCreate(
            ['email' => 'demo@aichost.com'],
            [
                'name'     => 'Demo Customer',
                'password' => Hash::make('password123'),
                'role'     => 'customer',
                'status'   => 'active',
                'balance'  => 250.00,
                'company'  => 'AI Innovations Lab',
                'country'  => 'US',
            ]
        );
    }

    protected function seedServers(): void
    {
        Server::updateOrCreate(
            ['hostname' => 'srv.shamsman.com'],
            [
                'name'                 => 'CWP Primary Node',
                'provider'             => 'cwp',
                'hostname'             => 'srv.shamsman.com',
                'ip_address'           => env('CWP_SERVER_IP', '185.193.64.1'),
                'api_url'              => env('CWP_API_URL', 'https://srv.shamsman.com:2031/api/'),
                'api_key_encrypted'    => env('CWP_API_KEY', 'demo_cwp_api_key'),
                'api_secret_encrypted' => env('CWP_API_SECRET', 'demo_cwp_secret'),
                'region'               => env('CWP_SERVER_REGION', 'us-central1'),
                'capacity'             => (int) env('CWP_SERVER_CAPACITY', 500),
                'used_slots'           => 12,
                'status'               => 'active',
            ]
        );
    }

    protected function seedProducts(): void
    {
        $products = [
            [
                'type'        => Product::TYPE_DOMAIN,
                'slug'        => 'domains',
                'name'        => 'Domain Registration',
                'tagline'     => 'Claim your AI identity across global TLDs',
                'description' => 'Fast registration via InternetBS with instant DNS provisioning, WHOIS privacy protection, and full DNS record control.',
                'icon'        => 'globe',
                'sort_order'  => 1,
            ],
            [
                'type'        => Product::TYPE_SHARED_HOSTING,
                'slug'        => 'shared-hosting',
                'name'        => 'AI Cloud Shared Hosting',
                'tagline'     => 'Optimized CWP Web Hosting on Google Cloud Infrastructure',
                'description' => 'Ultra-fast NVMe SSD shared hosting on CentOS Web Panel (CWP) with automated SSL, PHP 8.3/8.4, MariaDB, and 1-click installer.',
                'icon'        => 'server',
                'sort_order'  => 2,
            ],
            [
                'type'        => Product::TYPE_VPS,
                'slug'        => 'cloud-vps',
                'name'        => 'Google Cloud VPS with CWP',
                'tagline'     => 'Dedicated Compute Engine VMs Pre-Configured with CWP',
                'description' => 'Enterprise Google Cloud VMs powered by Intel Xeon / AMD EPYC / NVIDIA A100 GPUs with pre-installed CentOS Web Panel.',
                'icon'        => 'cpu',
                'sort_order'  => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                array_merge($product, ['is_active' => true])
            );
        }
    }

    protected function seedDomainPlans(): void
    {
        $product = Product::where('slug', 'domains')->firstOrFail();

        $tlds = [
            ['tld' => '.ai',    'register' => 79.99, 'renew' => 89.99, 'popular' => true, 'sort' => 1],
            ['tld' => '.com',   'register' => 11.99, 'renew' => 14.99, 'popular' => true, 'sort' => 2],
            ['tld' => '.io',    'register' => 39.99, 'renew' => 49.99, 'popular' => false, 'sort' => 3],
            ['tld' => '.net',   'register' => 12.99, 'renew' => 15.99, 'popular' => false, 'sort' => 4],
            ['tld' => '.cloud', 'register' => 18.99, 'renew' => 22.99, 'popular' => false, 'sort' => 5],
            ['tld' => '.tech',  'register' => 9.99,  'renew' => 19.99, 'popular' => false, 'sort' => 6],
        ];

        foreach ($tlds as $row) {
            $tld  = $row['tld'];
            $slug = 'domain-' . ltrim($tld, '.');

            ProductPlan::updateOrCreate(
                ['slug' => $slug],
                [
                    'product_id'      => $product->id,
                    'name'            => $tld . ' Domain',
                    'specs'           => [
                        'tld'                 => $tld,
                        'registration_period' => '1 Year',
                        'popular'             => $row['popular'],
                        'features'            => [
                            'Free WHOIS Privacy Protection',
                            'Real-Time DNS Management',
                            'Automated DNSSEC Support',
                            'Full Registrar Lock Protection',
                            'Email & URL Forwarding',
                        ],
                    ],
                    'price_monthly'   => $row['register'],
                    'price_annually'  => $row['renew'],
                    'setup_fee'       => 0,
                    'currency'        => 'USD',
                    'provider_type'   => 'internetbs',
                    'provider_config' => [
                        'registrar'  => 'internetbs',
                        'tld'        => $tld,
                        'auto_renew' => true,
                    ],
                    'is_active'       => true,
                    'sort_order'      => $row['sort'],
                ]
            );
        }
    }

    protected function seedSharedHostingPlans(): void
    {
        $product = Product::where('slug', 'shared-hosting')->firstOrFail();

        $plans = [
            [
                'slug'           => 'cwp-starter',
                'name'           => 'AI Starter',
                'price_monthly'  => 4.99,
                'price_annually' => 49.99,
                'sort'           => 1,
                'cwp_package'    => 'starter',
                'specs'          => [
                    'badge'          => 'Entry Level',
                    'websites'       => '1 Website',
                    'disk'           => '20 GB NVMe Storage',
                    'disk_gb'        => 20,
                    'bandwidth'      => 'Unmetered Traffic',
                    'databases'      => '5 MariaDB Databases',
                    'email_accounts' => '10 Mailboxes',
                    'panel'          => 'CWP Control Panel',
                    'ssl'            => 'Free Let\'s Encrypt SSL',
                    'features'       => [
                        '20 GB NVMe Ultra Storage',
                        '1 Hosted Domain',
                        'Free AutoSSL Certificates',
                        'CentOS Web Panel (CWP) Access',
                        'PHP 8.2 & 8.3 Selector',
                        'Daily Cloud Backups',
                        'DDoS Shield Protection',
                    ],
                ],
            ],
            [
                'slug'           => 'cwp-pro',
                'name'           => 'Developer Pro',
                'price_monthly'  => 9.99,
                'price_annually' => 99.99,
                'sort'           => 2,
                'cwp_package'    => 'pro',
                'specs'          => [
                    'badge'          => 'Most Popular',
                    'popular'        => true,
                    'websites'       => '5 Websites',
                    'disk'           => '60 GB NVMe Storage',
                    'disk_gb'        => 60,
                    'bandwidth'      => 'Unmetered Traffic',
                    'databases'      => 'Unlimited Databases',
                    'email_accounts' => '50 Mailboxes',
                    'panel'          => 'CWP Control Panel',
                    'ssl'            => 'Free Wildcard SSL',
                    'features'       => [
                        '60 GB NVMe Ultra Storage',
                        '5 Hosted Domains',
                        'Free Domain for 1st Year',
                        'One-Click CWP SSO Login',
                        'Node.js & Python App Manager',
                        'Git Version Control Integration',
                        'Redis Object Cache support',
                        'Priority 24/7 AI Support',
                    ],
                ],
            ],
            [
                'slug'           => 'cwp-enterprise',
                'name'           => 'Enterprise AI',
                'price_monthly'  => 19.99,
                'price_annually' => 199.99,
                'sort'           => 3,
                'cwp_package'    => 'enterprise',
                'specs'          => [
                    'badge'          => 'Maximum Power',
                    'websites'       => 'Unlimited Websites',
                    'disk'           => '150 GB NVMe Storage',
                    'disk_gb'        => 150,
                    'bandwidth'      => 'Unmetered Traffic',
                    'databases'      => 'Unlimited Databases',
                    'email_accounts' => 'Unlimited Mailboxes',
                    'panel'          => 'CWP Control Panel',
                    'ssl'            => 'Free Wildcard SSL',
                    'features'       => [
                        '150 GB NVMe Enterprise Storage',
                        'Unlimited Websites & Subdomains',
                        'Dedicated IPv4 Address',
                        'CWP Pro Licensed Features',
                        'LiteSpeed Web Server acceleration',
                        'Real-time Malware Scanner & WAF',
                        'Automated Hourly Snapshots',
                        'Dedicated VIP Engineer Support',
                    ],
                ],
            ],
        ];

        foreach ($plans as $plan) {
            ProductPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                [
                    'product_id'      => $product->id,
                    'name'            => $plan['name'],
                    'specs'           => $plan['specs'],
                    'price_monthly'   => $plan['price_monthly'],
                    'price_annually'  => $plan['price_annually'],
                    'setup_fee'       => 0,
                    'currency'        => 'USD',
                    'provider_type'   => 'cwp',
                    'provider_config' => [
                        'server_hostname' => 'srv.shamsman.com',
                        'cwp_package'     => $plan['cwp_package'],
                        'auto_ssl'        => true,
                    ],
                    'is_active'       => true,
                    'sort_order'      => $plan['sort'],
                ]
            );
        }
    }

    protected function seedVpsPlans(): void
    {
        $product = Product::where('slug', 'cloud-vps')->firstOrFail();

        $plans = [
            [
                'slug'           => 'vps-e2-standard-2',
                'name'           => 'GCP Standard 2 vCPU',
                'price_monthly'  => 28.00,
                'price_annually' => 280.00,
                'sort'           => 1,
                'machine_type'   => 'e2-standard-2',
                'gcp_zone'       => 'us-central1-a',
                'disk_gb'        => 80,
                'specs'          => [
                    'badge'       => 'Balanced Workload',
                    'vcpu'        => 2,
                    'ram'         => '8 GB RAM',
                    'ram_gb'      => 8,
                    'disk'        => '80 GB NVMe SSD',
                    'disk_gb'     => 80,
                    'network'     => '3 Gbps Network',
                    'cpu_family'  => 'Intel Xeon / AMD EPYC',
                    'cwp_status'  => 'CWP Pre-Installed',
                    'gpu'         => null,
                    'features'    => [
                        '2 Dedicated vCPU (Google Compute)',
                        '8 GB DDR4 RAM',
                        '80 GB Balanced Persistent NVMe',
                        'CentOS Web Panel (CWP) Ready',
                        'Dedicated Static External IP',
                        'Google Global VPC Network',
                        'Full Root & Terminal Access',
                    ],
                ],
            ],
            [
                'slug'           => 'vps-n2-standard-4',
                'name'           => 'GCP Compute Pro 4 vCPU',
                'price_monthly'  => 68.00,
                'price_annually' => 680.00,
                'sort'           => 2,
                'machine_type'   => 'n2-standard-4',
                'gcp_zone'       => 'us-central1-a',
                'disk_gb'        => 160,
                'specs'          => [
                    'badge'       => 'High Performance',
                    'popular'     => true,
                    'vcpu'        => 4,
                    'ram'         => '16 GB RAM',
                    'ram_gb'      => 16,
                    'disk'        => '160 GB NVMe SSD',
                    'disk_gb'     => 160,
                    'network'     => '10 Gbps Network',
                    'cpu_family'  => 'Intel Cascade Lake',
                    'cwp_status'  => 'CWP Pre-Installed',
                    'gpu'         => null,
                    'features'    => [
                        '4 High-Frequency vCPUs',
                        '16 GB DDR4 High-Speed RAM',
                        '160 GB High-IOPS NVMe Storage',
                        'CWP Pro Control Panel Ready',
                        'Dedicated Static External IP',
                        'Google Cloud Armor DDoS Defense',
                        'Automated GCP Cloud Snapshots',
                    ],
                ],
            ],
            [
                'slug'           => 'vps-a2-highgpu-1g',
                'name'           => 'GCP AI GPU Server (NVIDIA A100)',
                'price_monthly'  => 499.00,
                'price_annually' => 4990.00,
                'sort'           => 3,
                'machine_type'   => 'a2-highgpu-1g',
                'gcp_zone'       => 'us-central1-a',
                'disk_gb'        => 500,
                'specs'          => [
                    'badge'       => 'AI / LLM Dedicated',
                    'vcpu'        => 12,
                    'ram'         => '85 GB RAM',
                    'ram_gb'      => 85,
                    'disk'        => '500 GB Extreme NVMe',
                    'disk_gb'     => 500,
                    'network'     => '24 Gbps Network',
                    'cpu_family'  => 'Intel Xeon Platinum',
                    'cwp_status'  => 'CWP Pre-Installed',
                    'gpu'         => '1x NVIDIA Ampere A100 (40 GB HBM2)',
                    'features'    => [
                        '1x NVIDIA A100 Tensor Core 40GB',
                        '12 vCPUs + 85 GB High-Speed RAM',
                        '500 GB NVMe SSD Persistent Disk',
                        'CentOS Web Panel + CUDA Driver Stack',
                        'Optimized for PyTorch / TensorFlow / vLLM',
                        '24 Gbps Low Latency Interconnect',
                        'Dedicated Account Manager',
                    ],
                ],
            ],
        ];

        foreach ($plans as $plan) {
            ProductPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                [
                    'product_id'      => $product->id,
                    'name'            => $plan['name'],
                    'specs'           => $plan['specs'],
                    'price_monthly'   => $plan['price_monthly'],
                    'price_annually'  => $plan['price_annually'],
                    'setup_fee'       => 0,
                    'currency'        => 'USD',
                    'provider_type'   => 'gcp',
                    'provider_config' => [
                        'gcp_project'  => env('GCP_PROJECT_ID', 'aichost-production'),
                        'gcp_zone'     => $plan['gcp_zone'],
                        'machine_type' => $plan['machine_type'],
                        'disk_gb'      => $plan['disk_gb'],
                        'install_cwp'  => true,
                    ],
                    'is_active'       => true,
                    'sort_order'      => $plan['sort'],
                ]
            );
        }
    }
}
