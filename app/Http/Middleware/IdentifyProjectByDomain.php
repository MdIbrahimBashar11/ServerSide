<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Domains\Projects\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class IdentifyProjectByDomain
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);

        // If it's a tracking request from a custom domain
        if ($host !== $appHost) {
            $project = Project::where('custom_domain', $host)
                ->where('domain_status', 'verified')
                ->where('is_active', true)
                ->first();

            if ($project) {
                // Enforce HTTPS for verified domains with active SSL
                if ($project->ssl_status === 'active' && !$request->secure()) {
                    return redirect()->secure($request->getRequestUri());
                }

                // Attach the resolved project to the request
                $request->merge(['resolved_project' => $project]);
                
                // Also set tracking_id if it's missing but we resolved by domain
                if (!$request->has('tracking_id')) {
                    $request->merge(['tracking_id' => $project->tracking_id]);
                }
            }
        }

        return $next($request);
    }
}
