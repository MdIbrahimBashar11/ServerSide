<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MonitorConnectivityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:connectivity';

    /**
     * The console command description.
     */
    protected $description = 'Re-verify CNAME and SSL health for all verified projects to prevent silent tracking failures.';

    /**
     * Execute the console command.
     */
    public function handle(\App\Domains\Projects\Services\DNSVerificationService $dnsService, \App\Domains\Projects\Services\SSLProvisioningService $sslService)
    {
        $projects = \App\Domains\Projects\Models\Project::whereNotNull('custom_domain')
            ->whereIn('domain_status', ['verified', 'connection_lost'])
            ->get();

        $this->info("Checking connectivity for " . $projects->count() . " projects...");

        foreach ($projects as $project) {
            $this->comment("Auditing [{$project->custom_domain}]...");

            // 1. Check DNS Connectivity
            $isDnsValid = $dnsService->verify($project);

            // 2. Check SSL Health (if DNS is valid)
            if ($isDnsValid && $project->ssl_status === 'active') {
                $isSslValid = $sslService->checkHealth($project);
                
                if (!$isSslValid) {
                    $project->update(['ssl_status' => 'failed', 'ssl_error_log' => 'SSL Handshake failed or certificate expired.']);
                    $this->warn("SSL Error detected for {$project->custom_domain}.");
                }
            }

            // 3. Fallback to Connection Lost if DNS failed
            if (!$isDnsValid) {
                $project->update(['domain_status' => 'connection_lost']);
                $this->error("Connection LOST for {$project->custom_domain}.");
            } else {
                 $this->info("Handshake SUCCESS for {$project->custom_domain}.");
            }
        }

        $this->info('Connectivity audit completed.');
    }
}
