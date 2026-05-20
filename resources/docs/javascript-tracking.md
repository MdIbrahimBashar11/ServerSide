# JavaScript Tracking Guide for RecordSync

This guide documents the JavaScript methods for implementing client-side tracking using the `X-Tracking-Id` header to point directly to your customized server.

## Installation

Add the following base script to your page to initialize tracking:

```javascript
(function() {
    window.trackEvent = function(eventName, customData) {
        var payload = {
            event_name: eventName,
            event_id: 'evt_' + Math.random().toString(36).substr(2, 9),
            timestamp: Math.floor(Date.now() / 1000),
            user_data: {
                client_user_agent: navigator.userAgent,
                page_url: window.location.href,
                referrer: document.referrer
            },
            custom_data: customData || {}
        };

        fetch('https://trk.recordsync.cam/api/track-event', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Tracking-Id': 'YOUR_TRACKING_ID'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => console.log('Event Tracked Successfully:', data))
        .catch(error => console.error('Tracking Error:', error));
    };
})();
```

---

## Supported Events

### 1. PageView
Triggers when someone visits a page. This is usually fired automatically on every page load.
```javascript
trackEvent('PageView');
```

### 2. ViewContent
Triggers when a specific product or content is viewed.
```javascript
trackEvent('ViewContent', {
    content_name: 'Premium Hijab',
    content_ids: ['product_101'],
    content_type: 'product',
    value: 49.99,
    currency: 'USD'
});
```

### 3. Search
Triggers when a search is performed on your website.
```javascript
trackEvent('Search', {
    search_string: 'Black Abaya'
});
```

### 4. AddToCart
Triggers when a visitor adds a product to their shopping cart.
```javascript
trackEvent('AddToCart', {
    content_name: 'Premium Hijab',
    content_ids: ['product_101'],
    content_type: 'product',
    value: 49.99,
    currency: 'USD'
});
```

### 5. AddToWishlist
Triggers when a visitor adds an item to their wishlist.
```javascript
trackEvent('AddToWishlist', {
    content_name: 'Premium Hijab',
    content_ids: ['product_101'],
    content_type: 'product',
    value: 49.99,
    currency: 'USD'
});
```

### 6. InitiateCheckout
Triggers when a visitor enters the checkout flow.
```javascript
trackEvent('InitiateCheckout', {
    value: 120.50,
    currency: 'USD',
    content_ids: ['product_101', 'product_102'],
    content_type: 'product'
});
```

### 7. AddPaymentInfo
Triggers when a visitor adds their billing/payment information.
```javascript
trackEvent('AddPaymentInfo', {
    value: 120.50,
    currency: 'USD',
    payment_method: 'Credit Card'
});
```

### 8. Purchase
Triggers when an order/purchase is completed successfully.
```javascript
trackEvent('Purchase', {
    value: 120.50,
    currency: 'USD',
    content_ids: ['product_101', 'product_102'],
    content_type: 'product',
    num_items: 2
});
```
