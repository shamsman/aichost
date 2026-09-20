<?php

declare(strict_types=1);

namespace App\Support\Cloud;

class StartupScriptFactory
{
    /**
     * Generates a first-boot bash startup script for Google Cloud VM
     * that automatically installs CentOS Web Panel (CWP) on AlmaLinux 9 / CentOS Stream 9.
     */
    public static function cwp(
        string $adminEmail = 'admin@aichost.com',
        string $mysqlRootPassword = '',
    ): string {
        $mysqlRootPassword = $mysqlRootPassword !== ''
            ? $mysqlRootPassword
            : bin2hex(random_bytes(10));

        return <<<BASH
#!/bin/bash
set -e
exec > >(tee -a /var/log/aichost-cwp-bootstrap.log) 2>&1
echo "=== AICHost CWP Google VM Bootstrap Started: $(date -Is) ==="

# Disable SELinux for CWP installation
setenforce 0 2>/dev/null || true
if [ -f /etc/selinux/config ]; then
  sed -i 's/^SELINUX=.*/SELINUX=disabled/' /etc/selinux/config
fi

# Install dependencies
dnf -y update || true
dnf -y install wget curl tar policycoreutils epel-release || true

# Download and install CWP
cd /usr/local/src
wget -q -O cwp-el9-latest https://centos-webpanel.com/cwp-el9-latest
sh cwp-el9-latest -y || true

# Mark ready
echo "{$adminEmail}" > /etc/aichost-owner
touch /var/log/aichost-cwp-ready
echo "=== AICHost CWP Setup Completed: $(date -Is) ==="
BASH;
    }
}
