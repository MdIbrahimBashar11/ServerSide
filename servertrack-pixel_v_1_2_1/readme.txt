=== RecordSync - Conversion API & Pixel ===
Contributors: recordsync
Tags: facebook, pixel, woocommerce, tracking, analytics
Requires at least: 5.2
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://recordsync.cam

Plug & Play Server-Side Tracking for WooCommerce. Auto-generates DataLayer events & connects RecordSync CAPI instantly. No GTM required.

== Description ==

RecordSync is a powerful Server-Side Tracking solution designed to fix signal loss caused by iOS14, Ad Blockers, and ITP (Intelligent Tracking Prevention). By moving your tracking from the browser to the server, you ensure 99% data accuracy and improve your ROAS.

**Why use this plugin?**
Most tracking solutions require complex setups involving Google Tag Manager (GTM), DataLayer plugins, and expensive monthly subscriptions. 

**RecordSync is different.** This plugin handles everything:
1.  **Generates the DataLayer:** It automatically detects WooCommerce events (ViewContent, AddToCart, Checkout, Purchase) and creates the necessary data code. You do NOT need "GTM4WP" or any other plugin.
2.  **Connects to CAPI:** It loads the RecordSync SDK to send events directly to your server and Facebook Conversion API.
3.  **Handles Deduplication:** Automatically manages Event IDs to ensure Facebook doesn't double-count events from your Browser Pixel and Server.

**Key Features:**
* **Zero-Code Setup:** Just paste your Tracking ID and select "Full Plug-and-Play".
* **Custom Domain Support:** Use your own subdomain (e.g., `track.yourstore.com`) to set first-party cookies and bypass ad blockers completely.
* **Automatic WooCommerce Tracking:** Tracks Product Views, Cart Additions, Checkouts, and Purchases out of the box.
* **Advanced Matching:** Automatically captures customer data (Email, Phone, Name, City) from WooCommerce orders to improve Facebook Match Quality scores.

== Installation ==

1.  Upload the `recordsync` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Go to **Settings > RecordSync**.
4.  Enter your **Tracking ID** (Found in your RecordSync Dashboard).
5.  Select **"Full Plug-and-Play (Recommended)"** as the tracking mode.
6.  (Optional) Enter your **Custom Tracking URL** if you are using a custom subdomain (e.g., `https://track.mystore.com/api/track-event`).
7.  Click **Save Changes**.

== Frequently Asked Questions ==

= Do I need Google Tag Manager (GTM)? =
No. This plugin includes a built-in "DataLayer Generator" specifically for WooCommerce. It creates the tracking data automatically.

= Can I use my own Custom Domain? =
Yes! In the settings, you can replace the default `recordsync.cam` with your own custom domain (e.g., `track.yoursite.com`). This turns RecordSync into a "First-Party" tracker, which is much harder for browsers to block.

= Where do I find my Tracking ID? =
Log in to your RecordSync Dashboard, click on your project, and look at the project configuration fields to find the Tracking ID.