<?php

namespace App\Domains\Projects\Services;

use App\Domains\Projects\Models\Project;
use Illuminate\Support\Facades\Log;

class DNSVerificationService
{
    protected $targetDomain = 'sdk.recordsync.cam';

    /**
     * Verify the CNAME of a custom domain.
     */
    public function verify(Project $project): bool
    {
        if (!$project->custom_domain) {
            return false;
        }

        $records = dns_get_record($project->custom_domain, DNS_CNAME);
        $isVerified = false;

        // 1. Check for CNAME
        foreach ($records as $record) {
            if (isset($record['target']) && strtolower($record['target']) === strtolower($this->targetDomain)) {
                $isVerified = true;
                break;
            }
        }

        // 2. Fallback: Check for A Record (If on the same server)
        if (!$isVerified) {
            $ipA = gethostbyname($project->custom_domain);
            $ipTarget = gethostbyname($this->targetDomain);
            
            if ($ipA !== $project->custom_domain && $ipA === $ipTarget) {
                $isVerified = true;
            }
        }

        $status = $isVerified ? 'verified' : 'failed';
        
        $project->update([
            'domain_status' => $status,
            'verified_at' => $isVerified ? now() : $project->verified_at,
            'last_check_at' => now(),
        ]);

        if ($isVerified) {
            \App\Domains\Projects\Jobs\ProvisionSSLJob::dispatch($project);
        }

        return $isVerified;
    }
}
