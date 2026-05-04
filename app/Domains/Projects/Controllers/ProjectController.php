<?php

namespace App\Domains\Projects\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Models\Event;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'custom_domain' => 'required|string|max:255|unique:projects,custom_domain',
            'plan_id' => 'nullable|exists:subscription_plans,id',
        ]);

        if ($request->user()->projects()->count() >= 5) {
            return back()->with('error', 'You have reached your tier limit for maximum projects.');
        }

        $project = $request->user()->projects()->create([
            'name' => $request->name,
            'custom_domain' => str_replace(['http://', 'https://', '/'], '', $request->custom_domain),
            'tracking_id' => 'trk_' . strtoupper(Str::random(12)),
            'is_active' => true,
        ]);

        if ($request->filled('plan_id')) {
            $plan = \App\Models\SubscriptionPlan::find($request->plan_id);
            if ($plan && $plan->price > 0) {
                return redirect()->route('billing.pay', $plan->id)->with('status', 'Please complete the payment for the selected plan.');
            }
        }

        return back()->with('status', 'Tracking infrastructure generated for ' . $project->custom_domain);
    }

    public function show(Project $project, Request $request)
    {
        if($project->user_id !== auth()->id()) abort(403);
        
        $totalEvents = Event::where('project_id', $project->id)->count();

        $successfulEvents = Event::where('project_id', $project->id)
            ->whereHas('deliveryLogs', function($q) {
                $q->where('status', 'success');
            })->count();

        $failedEvents = Event::where('project_id', $project->id)
            ->whereHas('deliveryLogs', function($q) {
                $q->where('status', 'failed');
            })->count();

        $pendingEvents = Event::where('project_id', $project->id)
            ->whereDoesntHave('deliveryLogs')
            ->count();

        $blockedEvents = Event::where('project_id', $project->id)
            ->where('source', 'blocked')
            ->count();

        $duplicatedEvents = Event::where('project_id', $project->id)
            ->where('source', 'duplicate')
            ->count();

        $latestEvent = Event::where('project_id', $project->id)->latest('event_time')->first();
        if (!$latestEvent) {
            $liveStatus = 'pending';
            $statusText = 'Awaiting First Event / Setup Pending';
        } elseif ($latestEvent->event_time->lt(now()->subDay())) {
            $liveStatus = 'error';
            $statusText = 'No Recent Data / Connection Idle';
        } else {
            $liveStatus = 'verified';
            $statusText = 'Active & Operational';
        }

        $query = Event::where('project_id', $project->id)->orderBy('event_time', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('event_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $events = $query->limit(10)->get();

        return view('projects.show', compact(
            'project', 'totalEvents', 'successfulEvents', 'failedEvents', 
            'pendingEvents', 'blockedEvents', 'duplicatedEvents', 'events', 
            'liveStatus', 'statusText'
        ));
    }

    public function export(Project $project, Request $request)
    {
        if($project->user_id !== auth()->id()) abort(403);

        $query = Event::where('project_id', $project->id)->orderBy('event_time', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('event_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=eventrix_export_' . date('Y-m-d') . '.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $callback = function() use($query) {
            $file = fopen('php://output', 'w');
            // Write Headers
            fputcsv($file, ['ID', 'Event Name', 'Platform', 'IP Address', 'User Agent', 'FBP', 'FBC', 'Event Time']);

            // Chunk DB retrieval to process infinitely without RAM crashing
            $query->chunk(1000, function($eventsChunk) use(&$file) {
                foreach ($eventsChunk as $e) {
                    $userData = $e->user_data ?? [];
                    fputcsv($file, [
                        $e->id,
                        $e->event_name,
                        $e->platform,
                        $userData['client_ip_address'] ?? 'Unknown',
                        $userData['client_user_agent'] ?? 'Unknown',
                        $userData['fbp'] ?? '',
                        $userData['fbc'] ?? '',
                        $e->event_time
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        $events = \App\Domains\Projects\Models\Event::where('project_id', $project->id)->orderBy('event_time', 'desc')->limit(10)->get();
        return view('projects.edit', compact('project', 'events'));
    }

    public function update(Request $request, Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        
        $request->validate([
            'name' => 'required|string',
            'website_url' => 'required|url',
            'custom_domain' => 'required|string|max:255|unique:projects,custom_domain,' . $project->id,
            'platform' => 'required|string'
        ]);

        $project->update([
            'name' => $request->name,
            'website_url' => $request->website_url,
            'custom_domain' => str_replace(['http://', 'https://', '/'], '', $request->custom_domain),
            'platform' => $request->platform
        ]);

        return back()->with('status', 'Basic Info saved successfully.');
    }

    public function setup(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        $events = \App\Domains\Projects\Models\Event::where('project_id', $project->id)->orderBy('event_time', 'desc')->limit(10)->get();
        return view('projects.setup', compact('project', 'events'));
    }

    public function verifyDomain(Project $project, \App\Domains\Projects\Services\DNSVerificationService $dnsService)
    {
        if($project->user_id !== auth()->id()) abort(403);

        $isVerified = $dnsService->verify($project);

        if ($isVerified) {
            return back()->with('status', 'Domain successfully connected and verified.');
        }

        return back()->with('error', 'Verification failed. Please ensure your CNAME record is correctly configured.');
    }
    public function updateDestinations(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $request->validate([
            'fb_pixel_id' => 'nullable|string|max:255',
            'fb_access_token' => 'nullable|string|max:1024',
            'tt_pixel_id' => 'nullable|string|max:255',
            'tt_access_token' => 'nullable|string|max:1024',
        ]);

        // Update Facebook CAPI
        if ($request->filled('fb_pixel_id') || $request->filled('fb_access_token')) {
            $project->destinations()->updateOrCreate(
                ['platform' => 'fb_capi'],
                [
                    'dataset_id' => $request->fb_pixel_id,
                    'access_token' => $request->fb_access_token,
                    'is_active' => true
                ]
            );
        }

        // Update TikTok API
        if ($request->filled('tt_pixel_id') || $request->filled('tt_access_token')) {
            $project->destinations()->updateOrCreate(
                ['platform' => 'tiktok'],
                [
                    'dataset_id' => $request->tt_pixel_id,
                    'access_token' => $request->tt_access_token,
                    'is_active' => true
                ]
            );
        }

        return back()->with('status', 'API Destinations updated successfully.');
    }

    public function events(Project $project)
    {
        if($project->user_id !== auth()->id()) abort(403);
        $events = Event::where('project_id', $project->id)->orderBy('event_time', 'desc')->paginate(50);
        return view('projects.events', compact('project', 'events'));
    }

    public function deliveryLogs(Project $project, Event $event)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $logs = \App\Domains\Projects\Models\EventDeliveryLog::where('event_id', $event->id)
            ->with(['event', 'destination'])
            ->latest()
            ->limit(50)
            ->get();

        return response()->json($logs);
    }

    public function eventsJson(Project $project)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $events = Event::where('project_id', $project->id)
            ->orderBy('event_time', 'desc')
            ->limit(10)
            ->get();

        return response()->json($events);
    }

    public function downloadPlugin(Project $project)
    {
        if ($project->user_id !== auth()->id()) abort(403);

        $trackingUrl = $project->domain_status === 'verified'
            ? "https://{$project->custom_domain}/api/track-event"
            : config('app.url') . "/api/track-event";
        $trackingId = $project->tracking_id;

        $pluginCode = '<?php' . PHP_EOL . '/**' . PHP_EOL .
            ' * Plugin Name: EVENTRIX' . PHP_EOL .
            ' * Description: Advanced Server-side & Client-side conversions tracking for RecordSync.' . PHP_EOL .
            ' * Version: 1.0.0' . PHP_EOL .
            ' * Author: RecordSync' . PHP_EOL .
            ' */' . PHP_EOL . PHP_EOL .
            'if (!defined(\'ABSPATH\')) exit;' . PHP_EOL . PHP_EOL .
            '// On activation set defaults' . PHP_EOL .
            'register_activation_hook(__FILE__, \'eventrix_set_plugin_defaults\');' . PHP_EOL .
            'function eventrix_set_plugin_defaults() {' . PHP_EOL .
            '    add_option(\'eventrix_status\', \'active\');' . PHP_EOL .
            '    add_option(\'eventrix_mode\', \'live\');' . PHP_EOL .
            '    add_option(\'eventrix_test_id\', \'\');' . PHP_EOL .
            '    add_option(\'eventrix_tracking_url\', \'' . $trackingUrl . '\');' . PHP_EOL .
            '    add_option(\'eventrix_tracking_id\', \'' . $trackingId . '\');' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            '// Add menu' . PHP_EOL .
            'add_action(\'admin_menu\', \'eventrix_add_admin_menu\');' . PHP_EOL .
            'function eventrix_add_admin_menu() {' . PHP_EOL .
            '    add_menu_page(\'EVENTRIX Settings\', \'EVENTRIX\', \'manage_options\', \'eventrix\', \'eventrix_admin_page\', \'dashicons-chart-line\');' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            '// Add direct Settings link on plugins page' . PHP_EOL .
            'add_filter(\'plugin_action_links_\' . plugin_basename(__FILE__), \'eventrix_add_plugin_settings_link\');' . PHP_EOL .
            'function eventrix_add_plugin_settings_link($links) {' . PHP_EOL .
            '    $settings_link = \'<a href="admin.php?page=eventrix">\' . __(\'Settings\') . \'</a>\';' . PHP_EOL .
            '    array_unshift($links, $settings_link);' . PHP_EOL .
            '    return $links;' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            '// Add settings' . PHP_EOL .
            'add_action(\'admin_init\', \'eventrix_register_plugin_settings\');' . PHP_EOL .
            'function eventrix_register_plugin_settings() {' . PHP_EOL .
            '    register_setting(\'eventrix-group\', \'eventrix_status\');' . PHP_EOL .
            '    register_setting(\'eventrix-group\', \'eventrix_mode\');' . PHP_EOL .
            '    register_setting(\'eventrix-group\', \'eventrix_test_id\');' . PHP_EOL .
            '    register_setting(\'eventrix-group\', \'eventrix_tracking_url\');' . PHP_EOL .
            '    register_setting(\'eventrix-group\', \'eventrix_tracking_id\');' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            'function eventrix_admin_page() {' . PHP_EOL .
            '    ?>' . PHP_EOL .
            '    <div class="wrap" style="background:#fff; padding:20px; border-radius:12px; border:1px solid #e5e7eb; max-width:800px; margin-top:20px; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;">' . PHP_EOL .
            '        <h1 style="font-weight:800; font-size:28px; color:#111827; letter-spacing:-0.025em; margin-bottom:10px;">EVENTRIX Configuration</h1>' . PHP_EOL .
            '        <p style="color:#6b7280; font-size:14px; margin-bottom:30px;">Manage your server-side conversion tracking for RecordSync.</p>' . PHP_EOL .
            '        <form method="post" action="options.php">' . PHP_EOL .
            '            <?php settings_fields(\'eventrix-group\'); ?>' . PHP_EOL .
            '            <?php do_settings_sections(\'eventrix-group\'); ?>' . PHP_EOL .
            '            <table class="form-table" style="width:100%;">' . PHP_EOL .
            '                <tr valign="top">' . PHP_EOL .
            '                    <th scope="row" style="font-weight:600; font-size:14px; color:#374151; width:200px;">Plugin Status</th>' . PHP_EOL .
            '                    <td>' . PHP_EOL .
            '                        <select name="eventrix_status" style="width:100%; max-width:400px; border-radius:8px; border:1px solid #d1d5db; padding:8px; height:auto;">' . PHP_EOL .
            '                            <option value="active" <?php selected(get_option(\'eventrix_status\'), \'active\'); ?>>Active</option>' . PHP_EOL .
            '                            <option value="inactive" <?php selected(get_option(\'eventrix_status\'), \'inactive\'); ?>>Inactive</option>' . PHP_EOL .
            '                        </select>' . PHP_EOL .
            '                    </td>' . PHP_EOL .
            '                </tr>' . PHP_EOL .
            '                <tr valign="top">' . PHP_EOL .
            '                    <th scope="row" style="font-weight:600; font-size:14px; color:#374151;">Tracking Mode</th>' . PHP_EOL .
            '                    <td>' . PHP_EOL .
            '                        <select name="eventrix_mode" id="eventrix_mode" style="width:100%; max-width:400px; border-radius:8px; border:1px solid #d1d5db; padding:8px; height:auto;">' . PHP_EOL .
            '                            <option value="live" <?php selected(get_option(\'eventrix_mode\'), \'live\'); ?>>Live Mode</option>' . PHP_EOL .
            '                            <option value="test" <?php selected(get_option(\'eventrix_mode\'), \'test\'); ?>>Test Mode</option>' . PHP_EOL .
            '                        </select>' . PHP_EOL .
            '                    </td>' . PHP_EOL .
            '                </tr>' . PHP_EOL .
            '                <tr valign="top" id="test_id_row">' . PHP_EOL .
            '                    <th scope="row" style="font-weight:600; font-size:14px; color:#374151;">Test ID (test_event_code)</th>' . PHP_EOL .
            '                    <td>' . PHP_EOL .
            '                        <input type="text" name="eventrix_test_id" value="<?php echo esc_attr(get_option(\'eventrix_test_id\')); ?>" style="width:100%; max-width:400px; border-radius:8px; border:1px solid #d1d5db; padding:8px;" />' . PHP_EOL .
            '                        <p class="description" style="font-size:12px; color:#9ca3af; margin-top:4px;">Required only when viewing events on Meta Test Events tab.</p>' . PHP_EOL .
            '                    </td>' . PHP_EOL .
            '                </tr>' . PHP_EOL .
            '                <tr valign="top">' . PHP_EOL .
            '                    <th scope="row" style="font-weight:600; font-size:14px; color:#374151;">Tracking URL</th>' . PHP_EOL .
            '                    <td>' . PHP_EOL .
            '                        <input type="text" name="eventrix_tracking_url" value="<?php echo esc_attr(get_option(\'eventrix_tracking_url\')); ?>" style="width:100%; max-width:400px; border-radius:8px; border:1px solid #d1d5db; padding:8px;" />' . PHP_EOL .
            '                    </td>' . PHP_EOL .
            '                </tr>' . PHP_EOL .
            '                <tr valign="top">' . PHP_EOL .
            '                    <th scope="row" style="font-weight:600; font-size:14px; color:#374151;">Tracking ID</th>' . PHP_EOL .
            '                    <td>' . PHP_EOL .
            '                        <input type="text" name="eventrix_tracking_id" value="<?php echo esc_attr(get_option(\'eventrix_tracking_id\')); ?>" style="width:100%; max-width:400px; border-radius:8px; border:1px solid #d1d5db; padding:8px;" />' . PHP_EOL .
            '                    </td>' . PHP_EOL .
            '                </tr>' . PHP_EOL .
            '            </table>' . PHP_EOL .
            '            <div style="margin-top:30px;">' . PHP_EOL .
            '                <?php submit_button(\'Save Settings\', \'primary\', \'submit\', true, [\'style\' => \'background:#111827; border-color:#111827; border-radius:8px; padding:12px 24px; height:auto; color:#fff; font-weight:bold; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);\']); ?>' . PHP_EOL .
            '            </div>' . PHP_EOL .
            '        </form>' . PHP_EOL .
            '    </div>' . PHP_EOL .
            '    <script>' . PHP_EOL .
            '        document.addEventListener(\'DOMContentLoaded\', function() {' . PHP_EOL .
            '            var modeSelect = document.getElementById(\'eventrix_mode\');' . PHP_EOL .
            '            var testIdRow = document.getElementById(\'test_id_row\');' . PHP_EOL .
            '            function toggleTestRow() {' . PHP_EOL .
            '                if (modeSelect.value === \'test\') {' . PHP_EOL .
            '                    testIdRow.style.display = \'\';' . PHP_EOL .
            '                } else {' . PHP_EOL .
            '                    testIdRow.style.display = \'none\';' . PHP_EOL .
            '                }' . PHP_EOL .
            '            }' . PHP_EOL .
            '            modeSelect.addEventListener(\'change\', toggleTestRow);' . PHP_EOL .
            '            toggleTestRow();' . PHP_EOL .
            '        });' . PHP_EOL .
            '    </script>' . PHP_EOL .
            '    <?php' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            '// Insert Script and track via WP Actions' . PHP_EOL .
            'add_action(\'wp_head\', \'eventrix_inject_tracking_script\');' . PHP_EOL .
            'function eventrix_inject_tracking_script() {' . PHP_EOL .
            '    if (get_option(\'eventrix_status\') !== \'active\') return;' . PHP_EOL .
            '    $tracking_url = get_option(\'eventrix_tracking_url\');' . PHP_EOL .
            '    $tracking_id = get_option(\'eventrix_tracking_id\');' . PHP_EOL .
            '    $mode = get_option(\'eventrix_mode\');' . PHP_EOL .
            '    $test_id = get_option(\'eventrix_test_id\');' . PHP_EOL . PHP_EOL .
            '    if (empty($tracking_url) || empty($tracking_id)) return;' . PHP_EOL .
            '    ?>' . PHP_EOL .
            '    <script>' . PHP_EOL .
            '    (function() {' . PHP_EOL .
            '        var trackingUrl = \'<?php echo esc_url($tracking_url); ?>\';' . PHP_EOL .
            '        var trackingId = \'<?php echo esc_js($tracking_id); ?>\';' . PHP_EOL .
            '        var mode = \'<?php echo esc_js($mode); ?>\';' . PHP_EOL .
            '        var testId = \'<?php echo esc_js($test_id); ?>\';' . PHP_EOL . PHP_EOL .
            '        window.eventrixTrackEvent = function(eventName, customData) {' . PHP_EOL .
            '            var payload = {' . PHP_EOL .
            '                event_name: eventName,' . PHP_EOL .
            '                event_id: \'view_\' + Math.random().toString(36).substr(2, 9),' . PHP_EOL .
            '                timestamp: Math.floor(Date.now() / 1000),' . PHP_EOL .
            '                user_data: {' . PHP_EOL .
            '                    client_user_agent: navigator.userAgent,' . PHP_EOL .
            '                    page_url: window.location.href,' . PHP_EOL .
            '                    referrer: document.referrer' . PHP_EOL .
            '                },' . PHP_EOL .
            '                custom_data: customData || {}' . PHP_EOL .
            '            };' . PHP_EOL . PHP_EOL .
            '            if (mode === \'test\' && testId) {' . PHP_EOL .
            '                payload.custom_data.test_event_code = testId;' . PHP_EOL .
            '            }' . PHP_EOL . PHP_EOL .
            '            fetch(trackingUrl, {' . PHP_EOL .
            '                method: \'POST\',' . PHP_EOL .
            '                headers: {' . PHP_EOL .
            '                    \'Content-Type\': \'application/json\',' . PHP_EOL .
            '                    \'X-Tracking-Id\': trackingId' . PHP_EOL .
            '                },' . PHP_EOL .
            '                body: JSON.stringify(payload)' . PHP_EOL .
            '            }).catch(function(e) { console.error(\'Eventrix Error:\', e); });' . PHP_EOL .
            '        };' . PHP_EOL . PHP_EOL .
            '        // Initial PageView' . PHP_EOL .
            '        eventrixTrackEvent(\'PageView\');' . PHP_EOL .
            '    })();' . PHP_EOL .
            '    </script>' . PHP_EOL .
            '    <?php' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            '// WooCommerce Server-Side Hook' . PHP_EOL .
            'add_action(\'woocommerce_add_to_cart\', \'eventrix_track_add_to_cart\', 10, 6);' . PHP_EOL .
            'function eventrix_track_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {' . PHP_EOL .
            '    if (get_option(\'eventrix_status\') !== \'active\') return;' . PHP_EOL .
            '    $product = wc_get_product($product_id);' . PHP_EOL .
            '    if ($product) {' . PHP_EOL .
            '        eventrix_server_send(\'AddToCart\', [' . PHP_EOL .
            '            \'value\' => (float)$product->get_price(),' . PHP_EOL .
            '            \'currency\' => get_woocommerce_currency(),' . PHP_EOL .
            '            \'content_ids\' => [$product_id],' . PHP_EOL .
            '            \'content_name\' => $product->get_name(),' . PHP_EOL .
            '            \'content_type\' => \'product\'' . PHP_EOL .
            '        ]);' . PHP_EOL .
            '    }' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            'add_action(\'woocommerce_before_checkout_form\', \'eventrix_track_initiate_checkout\');' . PHP_EOL .
            'function eventrix_track_initiate_checkout() {' . PHP_EOL .
            '    if (get_option(\'eventrix_status\') !== \'active\') return;' . PHP_EOL .
            '    if (!function_exists(\'WC\') || !WC()->cart) return;' . PHP_EOL .
            '    $cart = WC()->cart;' . PHP_EOL .
            '    $items = $cart->get_cart();' . PHP_EOL .
            '    $content_ids = [];' . PHP_EOL .
            '    foreach ($items as $item) { $content_ids[] = $item[\'product_id\']; }' . PHP_EOL .
            '    eventrix_server_send(\'InitiateCheckout\', [' . PHP_EOL .
            '        \'value\' => (float)$cart->total,' . PHP_EOL .
            '        \'currency\' => get_woocommerce_currency(),' . PHP_EOL .
            '        \'content_ids\' => $content_ids,' . PHP_EOL .
            '        \'content_type\' => \'product\'' . PHP_EOL .
            '    ]);' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            'add_action(\'woocommerce_thankyou\', \'eventrix_track_purchase\');' . PHP_EOL .
            'function eventrix_track_purchase($order_id) {' . PHP_EOL .
            '    if (get_option(\'eventrix_status\') !== \'active\') return;' . PHP_EOL .
            '    $order = wc_get_order($order_id);' . PHP_EOL .
            '    if ($order) {' . PHP_EOL .
            '        $items = $order->get_items();' . PHP_EOL .
            '        $content_ids = [];' . PHP_EOL .
            '        foreach ($items as $item) { $content_ids[] = $item->get_product_id(); }' . PHP_EOL .
            '        eventrix_server_send(\'Purchase\', [' . PHP_EOL .
            '            \'value\' => (float)$order->get_total(),' . PHP_EOL .
            '            \'currency\' => $order->get_currency(),' . PHP_EOL .
            '            \'content_ids\' => $content_ids,' . PHP_EOL .
            '            \'content_type\' => \'product\'' . PHP_EOL .
            '        ]);' . PHP_EOL .
            '    }' . PHP_EOL .
            '}' . PHP_EOL . PHP_EOL .
            'function eventrix_server_send($eventName, $customData) {' . PHP_EOL .
            '    $tracking_url = get_option(\'eventrix_tracking_url\');' . PHP_EOL .
            '    $tracking_id = get_option(\'eventrix_tracking_id\');' . PHP_EOL .
            '    $mode = get_option(\'eventrix_mode\');' . PHP_EOL .
            '    $test_id = get_option(\'eventrix_test_id\');' . PHP_EOL . PHP_EOL .
            '    if (empty($tracking_url) || empty($tracking_id)) return;' . PHP_EOL . PHP_EOL .
            '    $payload = [' . PHP_EOL .
            '        \'event_name\' => $eventName,' . PHP_EOL .
            '        \'event_id\' => \'wc_\' . $eventName . \'_\' . uniqid(),' . PHP_EOL .
            '        \'timestamp\' => time(),' . PHP_EOL .
            '        \'user_data\' => [' . PHP_EOL .
            '            \'client_ip_address\' => $_SERVER[\'REMOTE_ADDR\'] ?? \'\',' . PHP_EOL .
            '            \'client_user_agent\' => $_SERVER[\'HTTP_USER_AGENT\'] ?? \'\',' . PHP_EOL .
            '            \'page_url\' => home_url(add_query_arg([], $GLOBALS[\'wp\']->request ?? \'\')),' . PHP_EOL .
            '        ],' . PHP_EOL .
            '        \'custom_data\' => $customData' . PHP_EOL .
            '    ];' . PHP_EOL . PHP_EOL .
            '    if ($mode === \'test\' && !empty($test_id)) {' . PHP_EOL .
            '        $payload[\'custom_data\'][\'test_event_code\'] = $test_id;' . PHP_EOL .
            '    }' . PHP_EOL . PHP_EOL .
            '    wp_remote_post($tracking_url, [' . PHP_EOL .
            '        \'method\' => \'POST\',' . PHP_EOL .
            '        \'timeout\' => 15,' . PHP_EOL .
            '        \'headers\' => [' . PHP_EOL .
            '            \'Content-Type\' => \'application/json\',' . PHP_EOL .
            '            \'X-Tracking-Id\' => $tracking_id' . PHP_EOL .
            '        ],' . PHP_EOL .
            '        \'body\' => json_encode($payload),' . PHP_EOL .
            '        \'data_format\' => \'body\'' . PHP_EOL .
            '    ]);' . PHP_EOL .
            '}';

        $zipFile = tempnam(sys_get_temp_dir(), 'eventrix') . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
            $zip->addFromString('eventrix/eventrix.php', $pluginCode);
            $zip->close();
        }

        return response()->download($zipFile, 'eventrix.zip')->deleteFileAfterSend(true);
    }
}
