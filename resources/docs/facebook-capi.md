# Facebook Conversion API (CAPI)

The **Facebook Conversion API** allows you to send web events from your server directly to Facebook. This helps improve the performance and measurement of your Facebook ad campaigns by bypassing browser-side limitations.

---

## 🔑 Prerequisites

Before you begin, you will need:
1.  A **Facebook Business Manager** account.
2.  A **Facebook Pixel** ID.
3.  A **Conversions API Access Token** (Generated in Events Manager settings).

---

## 🛠 Setup Instructions

### 1. Obtain your Access Token
1. Go to **Events Manager** in your Business Manager.
2. Select the Pixel you want to use.
3. Click on the **Settings** tab.
4. Scroll down to the **Conversions API** section.
5. Click **Generate access token** and copy it.

### 2. Configure Eventrix
1. Navigate to your **Project Settings** in the Eventrix Dashboard.
2. Under the **Destinations** tab, find **Facebook CAPI**.
3. Paste your **Pixel ID** and **Access Token**.
4. Click **Save Configuration**.

---

## 🧪 Testing your Integration

To verify that events are being sent correctly:
1. Use the **Test Events** tool in Facebook Events Manager.
2. Copy your **Test Event Code** (e.g., `TEST12345`).
3. Paste this code into the **Test Mode** field in your Eventrix project settings.
4. Trigger an event on your website and check the "Test Events" tab in Facebook.

---

## 💡 Best Practices

-   **Deduplication**: Eventrix automatically sends an `event_id` with every event. Ensure your browser-side pixel also sends the same `event_id` to prevent double-counting.
-   **Advanced Matching**: Provide as much customer information as possible (hashed email, phone number) to increase the Match Quality score.
