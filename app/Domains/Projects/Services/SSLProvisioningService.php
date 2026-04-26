<?php

namespace App\Domains\Projects\Services;

use App\Domains\Projects\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SSLProvisioningService
{
    public function __construct(protected NginxConfigService $nginxService) {}

    /**
     * Start the SSL provisioning process.
     */
    public function provision(Project $project): bool
    {
        $project->update(['ssl_status' => 'pending']);
        $domain = $project->custom_domain;

        try {
            // 1. Update Nginx Config with the new domain
            if (!$this->nginxService->addDomain($domain)) {
                throw new \Exception("Failed to update Nginx configuration locally.");
            }

            // 2. Move temp config to Nginx and Reload (Requires sudo permissions)
            $moveCommand = "sudo mv " . storage_path('app/nginx_tmp_config') . " /etc/nginx/sites-available/recordsync";
            $reloadCommand = "sudo systemctl reload nginx";
            
            exec($moveCommand, $output, $res);
            if ($res !== 0) throw new \Exception("Failed to move Nginx config. Check sudo permissions.");
            
            exec($reloadCommand, $output, $res);
            if ($res !== 0) throw new \Exception("Nginx reload failed.");

            // 3. Run Certbot for SSL
            $certbotCommand = "sudo certbot --nginx -d {$domain} --non-interactive --agree-tos --register-unsafely-without-email";
            exec($certbotCommand, $output, $res);
            
            if ($res !== 0) {
                throw new \Exception("Certbot failed for domain {$domain}. Output: " . implode("\n", $output));
            }

            $project->update([
                'ssl_status' => 'active',
                'ssl_verified_at' => now(),
                'ssl_error_log' => null,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("SSL Provisioning Failed for Project {$project->id}: " . $e->getMessage());
            
            $project->update([
                'ssl_status' => 'failed',
                'ssl_error_log' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Check if a domain's SSL is still valid.
     */
     function checkHealth(Project $project): bool
    {
        if ($project->ssl_status !== 'active') return false;

        try {
            $context = stream_context_create([
                "ssl" => ["capture_peer_cert" => true, "verify_peer" => true]
            ]);
            $client = @stream_socket_client(
                "ssl://{$project->custom_domain}:443", 
                $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context
            );

            if ($client) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
    }
}
