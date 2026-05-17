<?php
/**
 * Plugin Name: EVENTRIX - Conversion API & Pixel
 * Description: The complete server side tracking solution. Automatically generates DataLayer events for WooCommerce and connects to EVENTRIX.
 * Version: 1.2.1
 * Author: RecordSync
 * Author URI: https://recordsync.cam
 * License: GPL2
 * Text Domain: eventrix-pixel
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (!defined('EVENTRIX_VERSION')) {
    define('EVENTRIX_VERSION', '1.2.1');
}

/**
 * Add custom links (Docs, Support)
 */
add_filter( 'plugin_row_meta', 'eventrix_add_row_meta_links', 10, 2 );

function eventrix_add_row_meta_links( $links, $file ) {
    if ( plugin_basename( __FILE__ ) === $file ) {
        $row_meta = array(
            'docs'    => '<a href="https://recordsync.cam/docs" target="_blank" aria-label="View Documentation">Documentation</a>',
            'support' => '<a href="https://recordsync.cam/tickets" target="_blank" aria-label="Contact Support">Support</a>',
        );
        return array_merge( $links, $row_meta );
    }
    return $links;
}

class Eventrix_Plugin {

    public function __construct() {
        // Activation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Admin
        add_action('admin_menu', array($this, 'create_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        
        // Frontend
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
    }

    public function activate() {
        if (get_option('eventrix_mode') === false) {
            update_option('eventrix_mode', 'datalayer');
        }
        if (get_option('eventrix_tracking_url') === false) {
            update_option('eventrix_tracking_url', '%%TRACKING_URL%%');
        }
        if (get_option('eventrix_tracking_id') === false) {
            update_option('eventrix_tracking_id', '%%TRACKING_ID%%');
        }
        if (get_option('eventrix_excluded_urls') === false) {
            update_option('eventrix_excluded_urls', '');
        }
        // Default AddToCart triggers
        if (get_option('eventrix_addtocart_triggers') === false) {
            $default_triggers = array(
                array('value' => 'ajax_add_to_cart', 'type' => 'class'),
                array('value' => 'add_to_cart_button', 'type' => 'class'),
                array('value' => 'single_add_to_cart_button', 'type' => 'class'),
                array('value' => 'add-to-cart', 'type' => 'name'),
                array('value' => 'addtocart', 'type' => 'id')
            );
            update_option('eventrix_addtocart_triggers', $default_triggers);
        }
        // Default custom events (empty)
        if (get_option('eventrix_custom_events') === false) {
            update_option('eventrix_custom_events', array());
        }
    }

    public function deactivate() {
        // No cleanup required yet
    }

    // --- ADMIN ---

    public function create_menu() {
        add_options_page('EVENTRIX', 'EVENTRIX', 'manage_options', 'eventrix-settings', array($this, 'settings_page_html'));
    }

    public function register_settings() {
        register_setting('eventrix_group', 'eventrix_tracking_id', array('sanitize_callback' => 'sanitize_text_field'));
        register_setting('eventrix_group', 'eventrix_mode', array('sanitize_callback' => array($this, 'sanitize_mode')));
        register_setting('eventrix_group', 'eventrix_tracking_url', array('sanitize_callback' => array($this, 'sanitize_tracking_url')));
        register_setting('eventrix_group', 'eventrix_excluded_urls', array('sanitize_callback' => array($this, 'sanitize_excluded_urls')));
        register_setting('eventrix_group', 'eventrix_addtocart_triggers', array('sanitize_callback' => array($this, 'sanitize_addtocart_triggers')));
        register_setting('eventrix_group', 'eventrix_custom_events', array('sanitize_callback' => array($this, 'sanitize_custom_events')));
    }

    public function sanitize_mode($input) {
        $valid_modes = array('datalayer', 'event', 'tracker', 'websocket');
        return in_array($input, $valid_modes, true) ? $input : 'datalayer';
    }

    public function sanitize_tracking_url($input) {
        $input = sanitize_text_field($input);
        return empty($input) ? '%%TRACKING_URL%%' : $input;
    }

    public function sanitize_excluded_urls($input) {
        $lines = explode("\n", $input);
        $sanitized = array();
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $line = preg_replace('/[^a-zA-Z0-9\/\-_.?=&:]/', '', $line);
                if (!empty($line)) {
                    $sanitized[] = $line;
                }
            }
        }
        return implode("\n", $sanitized);
    }

    public function is_url_excluded() {
        $excluded_urls = get_option('eventrix_excluded_urls', '');
        if (empty($excluded_urls)) {
            return false;
        }

        $current_url = $_SERVER['REQUEST_URI'] ?? '';
        $current_path = parse_url($current_url, PHP_URL_PATH) ?: $current_url;
        
        $current_path = ltrim($current_path, '/');
        
        $excluded_lines = explode("\n", $excluded_urls);
        foreach ($excluded_lines as $excluded) {
            $excluded = trim($excluded);
            if (empty($excluded)) continue;
            
            $excluded = ltrim($excluded, '/');
            
            if ($current_path === $excluded || strpos($current_path, $excluded) === 0) {
                return true;
            }
            
            if (strpos($excluded, $_SERVER['HTTP_HOST'] ?? '') !== false) {
                $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . $current_url;
                if (strpos($full_url, $excluded) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }

    public function sanitize_addtocart_triggers($input) {
        if (!is_array($input)) {
            return array();
        }
        $sanitized = array();
        foreach ($input as $trigger) {
            if (!isset($trigger['value']) || !isset($trigger['type'])) {
                continue;
            }
            $value = sanitize_text_field($trigger['value']);
            $type = sanitize_text_field($trigger['type']);
            
            $valid_types = array('class', 'name', 'id', 'href', 'data-attribute');
            if (!in_array($type, $valid_types, true)) {
                continue;
            }
            
            if (!empty($value)) {
                $sanitized[] = array(
                    'value' => $value,
                    'type' => $type
                );
            }
        }
        return $sanitized;
    }

    public function sanitize_custom_events($input) {
        if (!is_array($input)) {
            return array();
        }
        $sanitized = array();
        foreach ($input as $event) {
            if (!isset($event['custom_url']) || !isset($event['custom_event_name'])) {
                continue;
            }
            $custom_url = sanitize_text_field($event['custom_url']);
            $custom_event_name = sanitize_text_field($event['custom_event_name']);
            $value = isset($event['value']) ? floatval($event['value']) : 0;
            
            if (!empty($custom_url) && !empty($custom_event_name)) {
                $sanitized[] = array(
                    'custom_url' => $custom_url,
                    'custom_event_name' => $custom_event_name,
                    'value' => $value
                );
            }
        }
        return $sanitized;
    }

    public function admin_scripts($hook) {
        if ($hook !== 'settings_page_eventrix-settings') {
            return;
        }
        wp_enqueue_style(
            'eventrix-admin', 
            plugins_url('assets/css/admin.css', __FILE__), 
            array(), 
            EVENTRIX_VERSION
        );
        wp_enqueue_script(
            'eventrix-admin',
            plugins_url('assets/js/admin.js', __FILE__),
            array('jquery'),
            EVENTRIX_VERSION,
            true
        );
    }

    public function settings_page_html() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'eventrix-pixel'));
        }
        ?>
        <div class="wrap st-wrapper">
            <div class="st-header">
                <div class="st-logo">
                    <img src="%%FAVICON_URL%%" alt="EVENTRIX" style="height: 32px; width: 32px; border-radius: 4px;">
                    <span>EVENTRIX</span>
                </div>
                <div class="st-status-badge">Version <?php echo esc_html( EVENTRIX_VERSION ); ?></div>
            </div>

            <form method="post" action="options.php">
                <?php settings_fields('eventrix_group'); ?>
                <div class="st-grid">
                    <div class="st-column-main">
                        <div class="st-card">
                            <h2><?php echo esc_html__('Configuration', 'eventrix-pixel'); ?></h2>
                            <div class="st-form-group">
                                <label class="st-label" for="eventrix_tracking_id"><?php echo esc_html__('Tracking ID', 'eventrix-pixel'); ?></label>
                                <input type="text" id="eventrix_tracking_id" class="st-input" name="eventrix_tracking_id" value="<?php echo esc_attr(get_option('eventrix_tracking_id')); ?>" placeholder="trk_..." />
                                <p class="st-help"><?php echo esc_html__('Your specific first-party Project tracking identifier.', 'eventrix-pixel'); ?></p>
                            </div>
                            <div class="st-form-group">
                                <label class="st-label" for="eventrix_mode"><?php echo esc_html__('Tracking Mode', 'eventrix-pixel'); ?></label>
                                <select id="eventrix_mode" class="st-select" name="eventrix_mode">
                                    <option value="datalayer" <?php selected(get_option('eventrix_mode'), 'datalayer'); ?>><?php echo esc_html__('Full Plug-and-Play (Recommended)', 'eventrix-pixel'); ?></option>
                                    <option value="websocket" <?php selected(get_option('eventrix_mode'), 'websocket'); ?>><?php echo esc_html__('Advanced Blocker Bypass Mode', 'eventrix-pixel'); ?></option>
                                </select>
                                <p class="st-help"><?php echo esc_html__('Recommended mode handles full event compilation and first-party cloaking automatically.', 'eventrix-pixel'); ?></p>
                            </div>
                            <div class="st-form-group">
                                <label class="st-label" for="eventrix_tracking_url"><?php echo esc_html__('Custom Tracking URL', 'eventrix-pixel'); ?></label>
                                <input type="text" id="eventrix_tracking_url" class="st-input" name="eventrix_tracking_url" value="<?php echo esc_attr(get_option('eventrix_tracking_url', '%%TRACKING_URL%%')); ?>" placeholder="https://track.yourdomain.com/api/track-event" />
                                <div class="st-alert">
                                    <strong><?php echo esc_html__('First-Party CNAME Cloaking', 'eventrix-pixel'); ?></strong>
                                    <p style="margin:5px 0 10px;"><?php echo esc_html__('To bypass ad blockers completely, set up a custom subdomain in your DNS pointing to recordsync.cam.', 'eventrix-pixel'); ?></p>
                                    <a href="https://recordsync.cam/docs" target="_blank" style="text-decoration:none; color:#013D29; font-weight:600;">
                                        <?php echo esc_html__('Read the CNAME Setup Guide &rarr;', 'eventrix-pixel'); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="st-form-group">
                                <label class="st-label" for="eventrix_excluded_urls"><?php echo esc_html__('Excluded URLs', 'eventrix-pixel'); ?></label>
                                <textarea id="eventrix_excluded_urls" class="st-textarea" name="eventrix_excluded_urls" rows="6" placeholder="/checkout&#10;/cart&#10;/my-account"><?php echo esc_textarea(get_option('eventrix_excluded_urls', '')); ?></textarea>
                                <p class="st-help"><?php echo esc_html__('Enter one URL or path per line. Tracking will be disabled on matching pages. Examples: /checkout, /cart, /privacy-policy, /terms', 'eventrix-pixel'); ?></p>
                            </div>
                        </div>

                        <!-- AddToCart Triggers Repeater -->
                        <div class="st-card" style="margin-top: 30px;">
                            <h2><?php echo esc_html__('Add To Cart - Triggers', 'eventrix-pixel'); ?></h2>
                            <p class="st-help" style="margin-bottom: 15px;"><?php echo esc_html__('Configure custom selectors to detect AddToCart buttons. Any match will trigger the event.', 'eventrix-pixel'); ?></p>
                            
                            <div id="addtocart-triggers-repeater">
                                <?php 
                                $triggers = get_option('eventrix_addtocart_triggers', array());
                                if (empty($triggers)) {
                                    $triggers = array(
                                        array('value' => 'ajax_add_to_cart', 'type' => 'class'),
                                        array('value' => 'add_to_cart_button', 'type' => 'class'),
                                        array('value' => 'single_add_to_cart_button', 'type' => 'class'),
                                        array('value' => 'add-to-cart', 'type' => 'name'),
                                        array('value' => 'addtocart', 'type' => 'id')
                                    );
                                }
                                foreach ($triggers as $index => $trigger) {
                                    ?>
                                    <div class="st-repeater-row" data-index="<?php echo esc_attr($index); ?>">
                                        <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 10px; margin-bottom: 10px; align-items: center;">
                                            <input type="text" 
                                                   name="eventrix_addtocart_triggers[<?php echo esc_attr($index); ?>][value]" 
                                                   value="<?php echo esc_attr($trigger['value'] ?? ''); ?>" 
                                                   placeholder="ajax_add_to_cart" 
                                                   class="st-input" />
                                            <select name="eventrix_addtocart_triggers[<?php echo esc_attr($index); ?>][type]" class="st-select">
                                                <option value="class" <?php selected($trigger['type'] ?? '', 'class'); ?>>class</option>
                                                <option value="name" <?php selected($trigger['type'] ?? '', 'name'); ?>>name</option>
                                                <option value="id" <?php selected($trigger['type'] ?? '', 'id'); ?>>id</option>
                                                <option value="href" <?php selected($trigger['type'] ?? '', 'href'); ?>>href</option>
                                                <option value="data-attribute" <?php selected($trigger['type'] ?? '', 'data-attribute'); ?>>data-attribute</option>
                                            </select>
                                            <button type="button" class="st-btn-remove st-repeater-remove" style="padding: 5px 10px; background: #dc3232; color: white; border: none; cursor: pointer;">Delete</button>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <button type="button" class="st-btn-add" id="add-addtocart-trigger" style="margin-top: 10px; padding: 8px 15px; background: #013D29; color: white; border: none; cursor: pointer;">Add More +</button>
                        </div>

                        <!-- Custom Events Repeater -->
                        <div class="st-card" style="margin-top: 30px;">
                            <h2><?php echo esc_html__('Custom Events', 'eventrix-pixel'); ?></h2>
                            <p class="st-help" style="margin-bottom: 15px;"><?php echo esc_html__('Track custom events on specific URL patterns.', 'eventrix-pixel'); ?></p>
                            
                            <div id="custom-events-repeater">
                                <?php 
                                $custom_events = get_option('eventrix_custom_events', array());
                                foreach ($custom_events as $index => $event) {
                                    ?>
                                    <div class="st-repeater-row" data-index="<?php echo esc_attr($index); ?>">
                                        <div style="display: grid; grid-template-columns: 2fr 2fr 1fr auto; gap: 10px; margin-bottom: 10px; align-items: center;">
                                            <input type="text" 
                                                   name="eventrix_custom_events[<?php echo esc_attr($index); ?>][custom_url]" 
                                                   value="<?php echo esc_attr($event['custom_url'] ?? ''); ?>" 
                                                   placeholder="/cart" 
                                                   class="st-input" />
                                            <input type="text" 
                                                   name="eventrix_custom_events[<?php echo esc_attr($index); ?>][custom_event_name]" 
                                                   value="<?php echo esc_attr($event['custom_event_name'] ?? ''); ?>" 
                                                   placeholder="cart" 
                                                   class="st-input" />
                                            <input type="number" 
                                                   name="eventrix_custom_events[<?php echo esc_attr($index); ?>][value]" 
                                                   value="<?php echo esc_attr($event['value'] ?? 0); ?>" 
                                                   placeholder="0" 
                                                   step="0.01"
                                                   class="st-input" />
                                            <button type="button" class="st-btn-remove st-repeater-remove" style="padding: 5px 10px; background: #dc3232; color: white; border: none; cursor: pointer;">Delete</button>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <button type="button" class="st-btn-add" id="add-custom-event" style="margin-top: 10px; padding: 8px 15px; background: #013D29; color: white; border: none; cursor: pointer;">Add More +</button>
                        </div>

                        <div style="margin-top: 30px;">
                            <button type="submit" class="st-btn-primary"><?php echo esc_html__('Save Settings', 'eventrix-pixel'); ?></button>
                        </div>
                    </div>
                    <div class="st-column-sidebar">
                        <div class="st-card">
                            <h2><?php echo esc_html__('Quick Links', 'eventrix-pixel'); ?></h2>
                            <a href="https://recordsync.cam/docs" target="_blank" class="st-sidebar-link">
                                <span class="dashicons dashicons-book" style="margin-right:8px;"></span> <?php echo esc_html__('Documentation', 'eventrix-pixel'); ?>
                            </a>
                            <a href="https://recordsync.cam/tickets" target="_blank" class="st-sidebar-link">
                                <span class="dashicons dashicons-sos" style="margin-right:8px;"></span> <?php echo esc_html__('Contact Support', 'eventrix-pixel'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    // --- FRONTEND SCRIPTS & DATA ---

    public function frontend_scripts() {
        if ($this->is_url_excluded()) {
            return;
        }

        $tracking_id = get_option('eventrix_tracking_id');
        $tracking_url = get_option('eventrix_tracking_url');
        if (!$tracking_id || !$tracking_url) return;

        // Resolve absolute track.js URL from endpoint (replace API paths with JS path)
        $sdk_url = str_replace(array('/api/track-event', '/api/events'), '/js/track.js', $tracking_url);

        // Register and load compatibility wrapper and edge transmitter
        wp_register_script('eventrix-init', '', array(), EVENTRIX_VERSION, false);
        wp_enqueue_script('eventrix-init');

        $inline_script = sprintf(
            "window.ServerTrackEnv = { tracking_id: %s, endpoint: %s }; " .
            "window.st = window.st || function(action, eventName, data, userData) { " .
            "if (action === 'track') { " .
            "if (window.ServerTrack && window.ServerTrack.fire) { " .
            "window.ServerTrack.fire(eventName, data, userData); " .
            "} else { " .
            "window.ServerTrackEnv.queue = window.ServerTrackEnv.queue || []; " .
            "window.ServerTrackEnv.queue.push({ eventName: eventName, data: data, userData: userData }); " .
            "} " .
            "} " .
            "};",
            wp_json_encode($tracking_id),
            wp_json_encode($tracking_url)
        );
        wp_add_inline_script('eventrix-init', $inline_script);

        // Enqueue edge SDK asynchronously
        wp_enqueue_script('eventrix-sdk', $sdk_url, array('eventrix-init'), EVENTRIX_VERSION, false);
        add_filter('script_loader_tag', array($this, 'add_async_attribute'), 10, 2);

        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'eventrix-frontend', 
                plugins_url('assets/js/frontend.js', __FILE__), 
                array('jquery', 'eventrix-init'), 
                EVENTRIX_VERSION, 
                true
            );

            $data_for_js = array(
                'currency' => get_woocommerce_currency(),
                'cartEvents' => $this->get_cart_events_from_session(),
                'addToCartTriggers' => get_option('eventrix_addtocart_triggers', array()),
                'customEvents' => get_option('eventrix_custom_events', array())
            );

            wp_localize_script('eventrix-frontend', 'eventrixData', $data_for_js);

            $this->inject_dynamic_events();
        }
    }

    public function add_async_attribute($tag, $handle) {
        if ('eventrix-sdk' !== $handle) {
            return $tag;
        }
        return str_replace(' src', ' async src', $tag);
    }

    private function get_cart_events_from_session() {
        $cart_events = get_transient('eventrix_cart_events');
        if ($cart_events) {
            delete_transient('eventrix_cart_events');
            return $cart_events;
        }
        return array();
    }

    private function inject_dynamic_events() {
        // A. ViewContent
        if (is_product()) {
            global $post;
            $product = wc_get_product($post->ID);
            if ($product) {
                $event_data = array(
                    'event' => 'view_item',
                    'eventModel' => array(
                        'currency' => get_woocommerce_currency(),
                        'value' => (float) $product->get_price(),
                        'items' => array(
                            array(
                                'item_id' => (string) $product->get_id(),
                                'item_name' => $product->get_name(),
                                'price' => (float) $product->get_price(),
                                'quantity' => 1
                            )
                        )
                    )
                );
                $this->add_inline_event($event_data);
            }
        }

        // B. InitiateCheckout
        if (is_checkout() && !is_order_received_page()) {
            $cart = WC()->cart;
            if ($cart) {
                $items = array();
                foreach ($cart->get_cart() as $cart_item) {
                    $product = $cart_item['data'];
                    if (!$product) continue;
                    $items[] = array(
                        'item_id' => (string) $product->get_id(),
                        'item_name' => $product->get_name(),
                        'price' => (float) $product->get_price(),
                        'quantity' => (int) $cart_item['quantity']
                    );
                }
                $event_data = array(
                    'event' => 'begin_checkout',
                    'eventModel' => array(
                        'currency' => get_woocommerce_currency(),
                        'value' => (float) $cart->get_total('edit'),
                        'items' => $items
                    )
                );
                $this->add_inline_event($event_data);
            }
        }

        // C. Purchase
        if (is_order_received_page()) {
            global $wp;
            $order_id = isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
            if ($order_id) {
                $order = wc_get_order($order_id);
                if ($order) {
                    $items = array();
                    foreach ($order->get_items() as $item) {
                        $product = $item->get_product();
                        if (!$product) continue;
                        $item_id = $item->get_variation_id() ? (string) $item->get_variation_id() : (string) $item->get_product_id();
                        $items[] = array(
                            'item_id' => $item_id,
                            'item_name' => $item->get_name(),
                            'price' => (float) $order->get_item_total($item),
                            'quantity' => (int) $item->get_quantity()
                        );
                    }

                    $customer_data = array(
                        'first_name' => $order->get_billing_first_name(),
                        'last_name'  => $order->get_billing_last_name(),
                        'email'      => $order->get_billing_email(),
                        'phone'      => $order->get_billing_phone(),
                        'city'       => $order->get_billing_city(),
                        'zip'        => $order->get_billing_postcode(),
                        'country'    => $order->get_billing_country()
                    );

                    $event_data = array(
                        'event' => 'purchase',
                        'eventModel' => array(
                            'transaction_id' => (string) $order->get_id(),
                            'value' => (float) $order->get_total(),
                            'currency' => $order->get_currency(),
                            'tax' => (float) $order->get_total_tax(),
                            'shipping' => (float) $order->get_shipping_total(),
                            'items' => $items,
                            'customer' => array_filter($customer_data)
                        )
                    );
                    $this->add_inline_event($event_data);
                }
            }
        }
    }

    private function add_inline_event($data) {
        $event_name = $this->convert_event_name_to_st($data['event']);
        $event_data = isset($data['eventModel']) ? $data['eventModel'] : array();
        $user_data = isset($data['eventModel']['customer']) ? $data['eventModel']['customer'] : array();
        
        if (empty($user_data)) {
            $user_data = $this->get_available_customer_data();
        }
        
        $st_data = $this->convert_woo_to_st_format($event_data);
        $st_user_data = $this->convert_customer_to_st_format($user_data);
        
        $event_name_js = wp_json_encode($event_name, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $st_data_js = wp_json_encode($st_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $st_user_data_js = wp_json_encode($st_user_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $inline_script = "(function() { if (window.st) { window.st('track', $event_name_js, $st_data_js, $st_user_data_js); } else { setTimeout(function() { if (window.st) window.st('track', $event_name_js, $st_data_js, $st_user_data_js); }, 200); } })();";
        wp_add_inline_script('eventrix-init', $inline_script);
    }

    private function convert_event_name_to_st($woo_event) {
        $mapping = array(
            'view_item' => 'ViewContent',
            'begin_checkout' => 'InitiateCheckout',
            'purchase' => 'Purchase',
            'add_to_cart' => 'AddToCart'
        );
        return isset($mapping[$woo_event]) ? $mapping[$woo_event] : 'PageView';
    }

    private function convert_woo_to_st_format($event_model) {
        $st_data = array();
        
        if (isset($event_model['value'])) {
            $st_data['value'] = $event_model['value'];
        }
        if (isset($event_model['currency'])) {
            $st_data['currency'] = $event_model['currency'];
        }
        if (isset($event_model['transaction_id'])) {
            $st_data['transaction_id'] = $event_model['transaction_id'];
        }
        if (isset($event_model['items'])) {
            $st_data['content_ids'] = array();
            $st_data['content_type'] = 'product';
            foreach ($event_model['items'] as $item) {
                if (isset($item['item_id'])) {
                    $st_data['content_ids'][] = $item['item_id'];
                }
            }
        }
        
        return $st_data;
    }

    private function get_available_customer_data() {
        $customer_data = array();
        
        if (class_exists('WooCommerce')) {
            $customer = WC()->customer;
            if ($customer) {
                $billing_country = $customer->get_billing_country();
                $billing_city = $customer->get_billing_city();
                $billing_state = $customer->get_billing_state();
                $billing_postcode = $customer->get_billing_postcode();
                $billing_email = $customer->get_email();
                $billing_phone = $customer->get_billing_phone();
                $billing_first_name = $customer->get_billing_first_name();
                $billing_last_name = $customer->get_billing_last_name();
                
                if (empty($billing_country)) {
                    $billing_country = $customer->get_shipping_country();
                }
                if (empty($billing_city)) {
                    $billing_city = $customer->get_shipping_city();
                }
                if (empty($billing_state)) {
                    $billing_state = $customer->get_shipping_state();
                }
                if (empty($billing_postcode)) {
                    $billing_postcode = $customer->get_shipping_postcode();
                }
                
                if (!empty($billing_email)) {
                    $customer_data['email'] = $billing_email;
                }
                if (!empty($billing_phone)) {
                    $customer_data['phone'] = $billing_phone;
                }
                if (!empty($billing_first_name)) {
                    $customer_data['first_name'] = $billing_first_name;
                }
                if (!empty($billing_last_name)) {
                    $customer_data['last_name'] = $billing_last_name;
                }
                if (!empty($billing_city)) {
                    $customer_data['city'] = $billing_city;
                }
                if (!empty($billing_state)) {
                    $customer_data['st'] = $billing_state;
                }
                if (!empty($billing_postcode)) {
                    $customer_data['zip'] = $billing_postcode;
                }
                if (!empty($billing_country)) {
                    $customer_data['country'] = $billing_country;
                }
            }
            
            if (is_user_logged_in() && empty($customer_data)) {
                $user_id = get_current_user_id();
                $customer_data['email'] = get_user_meta($user_id, 'billing_email', true) ?: get_userdata($user_id)->user_email;
                $customer_data['phone'] = get_user_meta($user_id, 'billing_phone', true);
                $customer_data['first_name'] = get_user_meta($user_id, 'billing_first_name', true) ?: get_user_meta($user_id, 'first_name', true);
                $customer_data['last_name'] = get_user_meta($user_id, 'billing_last_name', true) ?: get_user_meta($user_id, 'last_name', true);
                $customer_data['city'] = get_user_meta($user_id, 'billing_city', true);
                $customer_data['st'] = get_user_meta($user_id, 'billing_state', true);
                $customer_data['zip'] = get_user_meta($user_id, 'billing_postcode', true);
                $customer_data['country'] = get_user_meta($user_id, 'billing_country', true);
            }
        }
        
        return array_filter($customer_data);
    }

    private function convert_customer_to_st_format($customer) {
        if (empty($customer)) return array();
        
        $st_user_data = array();
        if (isset($customer['email'])) {
            $st_user_data['em'] = $customer['email'];
        }
        if (isset($customer['phone'])) {
            $st_user_data['ph'] = $customer['phone'];
        }
        if (isset($customer['first_name'])) {
            $st_user_data['fn'] = $customer['first_name'];
        }
        if (isset($customer['last_name'])) {
            $st_user_data['ln'] = $customer['last_name'];
        }
        if (isset($customer['city'])) {
            $st_user_data['ct'] = $customer['city'];
        }
        if (isset($customer['st'])) {
            $st_user_data['st'] = $customer['st'];
        }
        if (isset($customer['zip'])) {
            $st_user_data['zp'] = $customer['zip'];
        }
        if (isset($customer['country'])) {
            $st_user_data['country'] = $customer['country'];
        }
        
        return $st_user_data;
    }
}

new Eventrix_Plugin();
