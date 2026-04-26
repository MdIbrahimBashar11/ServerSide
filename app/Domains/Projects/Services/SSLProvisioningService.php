<?php

namespace App\Domains\Projects\Services;

use App\Domains\Projects\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SSLProvisioningService
{
    /**
     * Start the SSL provisioning process.
     */
    public function provision(Project $project): bool
    {
        $project->update(['ssl_status' => 'pending']);

        try {
            // MOCK LOGIC FOR LOCAL DEVELOPMENT
            // In production, you would run a shell command or call a Cloudflare/SaaS API here.
            
            // Example Production Command (commented out):
            // $domain = $project->custom_domain;
            // $command = "sudo certbot --nginx -d {$domain} --non-interactive --agree-tos --email admin@servertrack.io";
            // exec($command, $output, $resultCode);
            // if ($resultCode !== 0) throw new \Exception("Certbot Failed: " . implode("\n", $output));

            // Delay simulation
            sleep(2);

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
