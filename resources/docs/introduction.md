# Welcome to RecordSync Docs

RecordSync is a premium First-Party Server-Side Tracking framework designed to effortlessly bypass iOS Safari privacy restrictions and browser AdBlockers without writing complex backend server rules.

## Why Server-Side Tracking?

Traditionally, when a user purchases an item on your e-commerce store, a client-side JavaScript snippet (like the Meta Pixel) loads in their browser and sends an HTTP POST request to `facebook.com`.

However, privacy-focused browsers immediately block outgoing network requests to third parties. Thus, you lose up to 30% of your valid conversion data. 

**RecordSync circumvents this.**

By setting up a CNAME DNS record bridging your domain to our servers, you send tracking data from your frontend directly to your own subdomain:
```http
POST https://trk.yourstore.com/api/track-event
```

Because the target domain matches the store domain, there is no cross-origin policy block. 

We securely receive the payload, aggressively sanitize it using SHA-256 constraints (protecting user PII), and forward it securely via robust background queue workers directly to Facebook's Conversion API and Google Analytics Measurement Protocol.

---

## Next Steps

1. Configure your custom domains to generate the Tracking ID.
2. Read the [Installation Guide](/docs/installation) to install the JavaScript snippet payload.
3. Configure your API Tokens for [Facebook CAPI](/docs/facebook-capi) and [GA4](/docs/ga4-mp).
