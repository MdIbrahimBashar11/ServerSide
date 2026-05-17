/**
 * ServerTrack - First-Party Edge Ingestion Engine
 * Load this script asynchronously on all pages.
 */
(function() {
    if (window.ServerTrackInitialized) return;
    window.ServerTrackInitialized = true;

    const env = window.ServerTrackEnv || {};
    const trackingId = env.tracking_id;
    const endpoint = env.endpoint || '/api/track-event';
    
    if (!trackingId) {
        console.warn('ServerTrack: Mission critical Tracking ID missing. Payload halted.');
        return;
    }

    // Advanced Cookie Decoder
    function getCustomCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    // URL Parameter Decoder
    function getQueryParam(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }

    // Dynamic Payload Compiler
    window.ServerTrack = {
        fire: function(eventName, customParams = {}, extraUserData = {}) {
            
            // Reconstruct Universal Identifiers
            const fbp = getCustomCookie('_fbp');
            const fbc = getCustomCookie('_fbc') || getQueryParam('fbclid');

            const payload = {
                tracking_id: trackingId,
                event_name: eventName,
                event_id: 'evt_' + Date.now() + '_' + Math.random().toString(36).substring(2, 10),
                timestamp: Math.floor(Date.now() / 1000),
                source_url: window.location.href,
                user_data: Object.assign({
                    client_user_agent: navigator.userAgent,
                    fbp: fbp,
                    fbc: fbc ? `fb.1.${Date.now()}.${fbc}` : null,
                    utm_source: getQueryParam('utm_source'),
                    utm_medium: getQueryParam('utm_medium'),
                    utm_campaign: getQueryParam('utm_campaign')
                }, extraUserData),
                custom_data: customParams
            };

            // Edge Transmitter
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Tracking-Id': trackingId
                },
                body: JSON.stringify(payload),
                keepalive: true // Ensure execution despite immediate page unload
            }).catch(err => {
                console.error("ServerTrack Edge fault:", err);
            });
        }
    };

    // Auto-fire standard PageView upon initialization
    if (env.autoTrack !== false) {
        window.ServerTrack.fire('PageView');
    }

    // Process queued events
    if (env.queue && Array.isArray(env.queue)) {
        env.queue.forEach(item => {
            window.ServerTrack.fire(item.eventName, item.data, item.userData);
        });
        env.queue = [];
    }

})();
