<?php

namespace App\Domains\Docs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DocsController extends Controller
{
    public function show($page = 'introduction')
    {
        // Sanitize path to prevent directory traversal
        $page = str_replace(['../', '..\\'], '', $page);
        $path = resource_path('docs/' . $page . '.md');

        if (!File::exists($path)) {
            abort(404, "Documentation page not found.");
        }

        $markdown = File::get($path);
        $content = Str::markdown($markdown);

        // Define Sidebar Navigation Structure
        $navigation = [
            'Getting Started' => [
                'introduction' => 'Introduction',
                'installation' => 'Installation Guide',
            ],
            'Integrations' => [
                'facebook-capi' => 'Facebook Conversion API',
                'ga4-mp' => 'Google Analytics 4 MP',
            ]
        ];

        return view('docs.show', compact('content', 'navigation', 'page'));
    }
}
