# Installing the Tracking Payload

To begin capturing events using your ServerTrack infrastructure, you need to execute a simple Tracking Script on the front-end of your website (e.g. WooCommerce, Shopify, or Custom Code).

## 1. Locate your credentials

Navigate to the **Integrations** tab in your dashboard. Ensure you have activated your Project. You will require:
- The **Ingestion Endpoint URL**
- Your unique **Tracking ID**

## 2. Implement the Javascript Snippet

Add the following standard snippet into the `<head>` of your website, specifically on the exact page you want to track (e.g. your Order Received "Thank You" page).

```js
// Collect User Identifiers and PII
const userData = {
    email: "customer@example.com",
    phone: "1234567890",
    client_ip_address: "192.0.2.1", // Or retrieve via backend
    client_user_agent: navigator.userAgent
};

// Define Event Metadata
const payload = {
    tracking_id: "YOUR_TRACKING_ID",
    event_id: "evt_" + Date.now() + "_" + Math.random().toString(36).substring(2, 9),
    event_name: "Purchase",
    user_data: userData,
    custom_data: {
        value: 129.99,
        currency: "USD",
        content_ids: ["PROD_1234"]
    }
};

// Transmit to ServerTrack Edge
fetch("YOUR_INGESTION_ENDPOINT_URL", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
});
```

### Critical Requirement: Event ID
Notice the `event_id` property. You must ensure an `event_id` is passed with every ping. Our edge nodes use this identifier as an aggressively cached idempotency key. If an identical event hits the system twice, the duplicate is dropped heavily minimizing your metered billing footprint.
