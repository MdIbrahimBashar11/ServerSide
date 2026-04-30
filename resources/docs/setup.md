# System Architecture & Setup Guide

Welcome to the **Eventrix Infrastructure**. This document provides a deep dive into how our server-side tracking engine operates, the benefits of this architecture, and how to optimize your setup for maximum data reclamation.

---

## 🚀 How It Works

Eventrix operates as a high-performance **First-Party Proxy Layer**. Instead of your website sending data directly to third-party domains (like `facebook.com`), it sends data to your own domain (e.g., `track.yourdomain.com`).

### The Data Journey

1.  **Capture**: Our lightweight JS SDK captures a user event (e.g., *Purchase*) on your frontend.
2.  **Dispatch**: The event is sent via an HTTP POST request to your custom subdomain.
3.  **Sanitization**: Our server receives the request, strips unnecessary cookies, and hashes Personal Identifiable Information (PII) using **SHA-256**.
4.  **Forwarding**: The cleaned data is forwarded via the **Facebook Conversion API (CAPI)** and **Google Analytics 4 Measurement Protocol** in the background.

---

## 💎 Key Benefits

### 1. Bypass iOS & AdBlock Restrictions
By using a first-party subdomain, Eventrix data streams are invisible to standard browser blockers. This typically results in a **20-35% increase** in attributed conversions.

### 2. Improved Page Speed
Traditional tracking pixels bloat your page and increase execution time. Eventrix offloads the heavy lifting to our server cluster, keeping your frontend lightning fast.

### 3. Data Sovereignty
You control what data is sent to vendors. Our system acts as a firewall, ensuring you only share what is necessary for marketing optimization.

---

## 🛠 Working with the Setup

### CNAME Configuration
To achieve "First-Party" status, you must point a CNAME record to our edge node:
- **Host**: `track` (or your preferred prefix)
- **Points to**: `relay.eventrix.app`

### Event Deduplication
Eventrix automatically handles deduplication between browser-side pixels and server-side events using the `event_id` parameter. This ensures your dashboard metrics remain accurate without double-counting.

### Real-time Logs
Visit your **Project Dashboard** to see live delivery logs. Every event shows its status (Success/Failed) and the raw response from Meta or Google.

---

## 📈 Optimization Tips

-   **Enable Advanced Matching**: Provide email or phone numbers in the event payload for higher match rates.
-   **Server-Side Only**: For maximum privacy, you can disable browser pixels entirely and rely solely on the Eventrix stream.
-   **Multi-Destination**: One event sent to Eventrix can be split and sent to Meta, GA4, TikTok, and Snap simultaneously.

---

*Need more help? Join our developer Slack or open a support ticket from the dashboard.*
