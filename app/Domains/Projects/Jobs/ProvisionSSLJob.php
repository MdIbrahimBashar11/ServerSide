<?php

namespace App\Domains\Projects\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Services\SSLProvisioningService;

class ProvisionSSLJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60; // Wait 60 seconds before retrying

    public function __construct(public Project $project)
    {
    }

    public function handle(SSLProvisioningService $sslService): void
    {
        $sslService->provision($this->project);
    }
}
