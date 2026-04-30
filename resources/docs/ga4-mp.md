# Google Analytics 4 Measurement Protocol

The **GA4 Measurement Protocol** enables you to augment your GA4 reports with server-to-server event data. It is particularly useful for tracking offline conversions or events that happen outside the browser.

---

## 🔑 Prerequisites

You will need:
1.  A **GA4 Property ID** (Measurement ID, e.g., `G-XXXXXXX`).
2.  An **API Secret** (Generated in Data Streams settings).

---

## 🛠 Setup Instructions

### 1. Generate an API Secret
1. Open your **GA4 Property**.
2. Go to **Admin** > **Data Streams**.
3. Select your web data stream.
4. Click on **Measurement Protocol API secrets**.
5. Create a new secret and copy the **Secret value**.

### 2. Configure Eventrix
1. Navigate to your **Project Settings** in the Eventrix Dashboard.
2. Under the **Destinations** tab, find **Google Analytics 4**.
3. Paste your **Measurement ID** and **API Secret**.
4. Click **Save Configuration**.

---

## 🚀 Key Features

-   **Cross-Device Tracking**: Use `client_id` and `user_id` to stitch sessions together across devices.
-   **Server-Side Events**: Send `purchase`, `refund`, or custom events directly from Eventrix.
-   **No Cookie Dependency**: Events are sent directly to Google's servers, bypassing ad-blockers and browser restrictions.

---

## ⚠️ Limitations

-   **Real-time Latency**: Events sent via Measurement Protocol may take up to 24-48 hours to fully appear in standard reports, though they often show up in the Real-time view within minutes.
-   **Session Attribution**: Ensure you pass the `session_id` from the browser to maintain accurate attribution.
