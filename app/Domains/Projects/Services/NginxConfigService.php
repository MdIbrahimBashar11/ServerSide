<?php

namespace App\Domains\Projects\Services;

use Illuminate\Support\Facades\Log;

class NginxConfigService
{
    protected $configPath = '/etc/nginx/sites-available/recordsync';

    /**
     * Add a custom domain to the Nginx server_name directive.
     */
    public function addDomain(string $domain): bool
    {
        if (!file_exists($this->configPath)) {
            Log::error("Nginx config file not found at {$this->configPath}");
            return false;
        }

        $content = file_get_contents($this->configPath);
        
        // Regex to find server_name line and append the new domain if it's not already there
        $pattern = '/server_name\s+([^;]+);/i';
        
        if (preg_match($pattern, $content, $matches)) {
            $existingDomains = explode(' ', trim($matches[1]));
            
            if (!in_array($domain, $existingDomains)) {
                $newDomains = trim($matches[1]) . ' ' . $domain;
                $newContent = preg_replace($pattern, "server_name {$newDomains};", $content);
                
                // Write back to file (Requires sudo/permissions)
                // We will use a temporary file and move it with sudo in the job
                file_put_contents(storage_path('app/nginx_tmp_config'), $newContent);
                return true;
            }
        }

        return true; // Already exists
    }
}
