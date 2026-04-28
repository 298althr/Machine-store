---
description: Streicher Portal — Complete Missing Routes, Payment Workflow & Guest Recovery
---

# Implementation Plan & Checklist

**Objective:** Close all missing GET/POST routes, fix the payment workflow so users must click "I have made payment" before uploading proof, wire admin approval/decline routes, enable guest order recovery, and inject dynamic bank details from `settings.csv`.

**Forbidden:** No new CSS files. No seeding admin users. No heavy GitHub writes (defer to later task).

---

## Phase 1 — Public Authentication Routes

### 1.1 POST /register
**File:** `web/includes/routes_public.php`
- Accept `first_name`, `last_name`, `company`, `email`, `password`, `password_confirm`, `terms`
- Validate: passwords match, terms accepted, email unique
- Call `UserRepository::create()` with `role = 'customer'`
- Auto-login: set `$_SESSION['user_id']`, `user_role`, `user_name`
- Redirect to `/account`

### 1.2 POST /logout
**File:** `web/includes/routes_public.php`
- Destroy session, regenerate ID
- Redirect to `/`

### 1.3 GET /account/profile  (stub)
**File:** `web/includes/routes_public.php`
- Require login (`$_SESSION['user_id']`)
- Render `pages/account_profile.php` (or existing template if available)
- **Current state:** sidebar links to it, no route exists.

### 1.4 GET /account/quotes  (stub)
**File:** `web/includes/routes_public.php`
- Require login
- Render `pages/account_quotes.php` (or reuse `pages/quote.php`)
- **Current state:** sidebar links to it, no route exists.

### 1.5 GET /forgot-password  (stub)
**File:** `web/includes/routes_public.php`
- Render `pages/forgot_password.php` (simple "contact support" placeholder is acceptable)
- **Current state:** linked in `pages/login.php:65`, no route or template.

---

## Phase 2 — Guest Order Recovery

### 2.1 GET /order/lookup
**File:** `web/includes/routes_public.php`
- Render `pages/order_lookup.php`
- Form asks for `order_number` + `billing_email`
- **Why:** Guest checkout sets `user_id = null`. Guests cannot use `/account`. They need a way to find their invoice and payment page.

### 2.2 POST /order/lookup
**File:** `web/includes/routes_public.php`
- Query `orders` table: `WHERE order_number = ? AND billing_email = ?`
- If match: redirect to `/order/{id}/invoice`
- If no match: re-render form with error
- **Why:** Enables return-to-transaction without registration.

---

## Phase 3 — Revised Payment Workflow (User Side)

### 3.1 New intermediate step: GET /order/{id}/payment-confirm
**File:** `web/includes/routes_public.php`
- Fetch order by ID
- Verify order status is `awaiting_payment`
- Render `order_payment_confirm.php`
- Page displays:
  - Bank details (injected from `settings.csv` — see Phase 5)
  - Order total + invoice number
  - **Primary button:** "I have made payment"
  - **Secondary link:** "Upload proof of payment" (disabled or hidden until confirmation)

### 3.2 New route: POST /order/{id}/payment-confirm
**File:** `web/includes/routes_public.php`
- Update order status from `awaiting_payment` → `payment_pending_upload` (new transient status)
- Insert a `payment_confirmations` row (or use `payment_uploads` with `type = 'confirmation'`) to log the intent
- Send Telegram notification to admin: "User claims payment made for Order #XXX — awaiting receipt upload"
- Redirect to `/order/{id}/payment` (the existing upload page)

### 3.3 Update GET /order/{id}/payment
**File:** `web/includes/routes_public.php`
- Allow access if status is `payment_pending_upload` or `payment_uploaded`
- If status is still `awaiting_payment`, redirect to `/order/{id}/payment-confirm`
- **Why:** Forces the confirmation step before upload.

### 3.4 Update POST /order/{id}/payment (receipt upload)
**File:** `web/includes/routes_public.php`
- After successful file save and `payment_uploads` INSERT, update status to `payment_uploaded`
- **Add:** Telegram notification to admin: "Payment receipt uploaded for Order #XXX"
- Redirect to `/order/{id}/confirmation` (existing)

### 3.5 Update existing templates
**Files:**
- `templates/proforma_invoice.php` — change "Upload Payment Protocol" link to point to `/order/{id}/payment-confirm` instead of `/order/{id}/payment`
- `templates/order_payment.php` — add back-link to confirmation page, display dynamic bank details from settings
- `templates/order_confirmation.php` — show roadmap correctly now that statuses include `payment_pending_upload`

---

## Phase 4 — Admin Payment Verification Routes

### 4.1 POST /admin/orders/{id}/confirm-payment
**File:** `web/includes/routes_admin.php`
- Require admin
- Fetch order
- Call `OrderRepository::confirmPayment($orderId, $_SESSION['user_id'])`
- Update status to `payment_confirmed`
- Send Telegram notification: "Payment CONFIRMED for Order #XXX — ready to ship"
- Set `$_SESSION['success_message']`
- Redirect to `/admin/orders/{id}`
- **Current state:** button exists in `admin/order_detail.php:30-31` and `admin/orders.php:81`. Route missing.

### 4.2 POST /admin/orders/{id}/decline-payment
**File:** `web/includes/routes_admin.php`
- Require admin
- Accept `decline_reason`, `decline_notes`
- Update order status to `payment_declined`
- Store `decline_reason` and `decline_notes` in `orders` table (columns may need adding if not present)
- Send Telegram notification with reason
- Redirect to `/admin/orders/{id}`
- **Current state:** modal form exists in `admin/order_detail.php:38-65`. Route missing.

### 4.3 POST /admin/orders/{id}/revert-to-awaiting
**File:** `web/includes/routes_admin.php`
- Require admin
- Update order status back to `awaiting_payment`
- Clear `payment_confirmed_at`, `payment_confirmed_by`, `decline_reason`, `decline_notes`
- Redirect to `/admin/orders/{id}`
- **Current state:** button exists in `admin/order_detail.php:90-92`. Route missing.

### 4.4 POST /admin/orders/{id}/create-shipment
**File:** `web/includes/routes_admin.php`
- Require admin
- Accept tracking number, carrier, shipping method, etc.
- Insert into `shipments` table with `order_id = {id}`
- Update order status to `shipped`
- Redirect to `/admin/shipments`
- **Current state:** `order_detail.php:73` links to `#ship` but no form or route exists. Add inline create form on order detail or a dedicated route.

---

## Phase 5 — Dynamic Bank Details Injection

### 5.1 Read settings in checkout and invoice routes
**Files:**
- `web/includes/routes_public.php` — checkout GET/POST, invoice GET, payment GET
- Pass `$bankDetails` array to templates.

### 5.2 Replace hardcoded bank details in templates
**Files:**
- `templates/checkout.php` — replace hardcoded Commerzbank block with `$settings['bank_name']`, `bank_account`, `iban`, `bic`
- `templates/proforma_invoice.php` — replace hardcoded block in payment-section with dynamic values
- `templates/order_payment.php` — replace hardcoded block with dynamic values
- **Note:** `PdfService.php` must also be updated (Phase 6).

### 5.3 Ensure `settings.csv` has the keys
**File:** `data/db/settings.csv`
- Verify keys exist: `bank_name`, `bank_account`, `iban`, `bic`, `bank_beneficiary`
- If missing, admin settings page (`/admin/settings`) POST route already allows saving them.

---

## Phase 6 — PDF Invoice Service

### 6.1 Update `app/Services/PdfService.php`
**File:** `app/Services/PdfService.php`
- `generateInvoice()` must accept `$settings` array or read from DB
- Replace hardcoded bank name, IBAN, BIC with `$settings['bank_name']` etc.
- **Current state:** bank details are hardcoded strings inside the FPDF generation logic.

---

## Phase 7 — Telegram Notifications (Payment Lifecycle)

### 7.1 Add `TelegramService::notifyPaymentConfirmed()`
**File:** `app/Services/TelegramService.php`
- Message: "Order #XXX payment CONFIRMED by {admin_name}. Ready to ship."

### 7.2 Add `TelegramService::notifyPaymentDeclined()`
**File:** `app/Services/TelegramService.php`
- Message: "Order #XXX payment DECLINED. Reason: {reason}."

### 7.3 Add `TelegramService::notifyPaymentUploaded()`
**File:** `app/Services/TelegramService.php`
- Message: "Payment receipt UPLOADED for Order #XXX. Review in admin panel."

### 7.4 Add `TelegramService::notifyPaymentClaimed()`
**File:** `app/Services/TelegramService.php`
- Message: "Customer claims payment made for Order #XXX. Awaiting receipt upload."

---

## Phase 8 — CSV / DB Schema Safety

### 8.1 Verify `orders` table columns
**File:** `data/db/orders.csv`
- Must support: `status` values: `awaiting_payment`, `payment_pending_upload`, `payment_uploaded`, `payment_confirmed`, `payment_declined`, `shipped`, `delivered`
- Must support columns: `payment_confirmed_at`, `payment_confirmed_by`, `decline_reason`, `decline_notes`, `payment_declined_at`
- If columns missing, `CsvPdo` INSERT with extra columns will likely be rejected or cause misalignment. **Check CSV headers before adding columns.**

### 8.2 Verify `payment_uploads` table
**File:** `data/db/payment_uploads.csv`
- Consider adding `type` column: `confirmation` vs `receipt` if we want to store the "I have paid" click as a record.
- Simpler alternative: do NOT create a separate table for the confirmation click; just update `orders.status` to `payment_pending_upload`.

---

## Phase 9 — Template Files to Create / Modify

### 9.1 Create `templates/pages/order_lookup.php`
- Simple form: order number + email
- Submit to POST /order/lookup

### 9.2 Create `templates/order_payment_confirm.php`
- Display bank details
- Show order summary
- Big CTA button: "I have made payment →"
- Small link: "I need to check my invoice first" → back to invoice

### 9.3 Create `templates/pages/forgot_password.php`
- Placeholder: "Please contact procurement@streicher.de to reset your authorization key."
- **Why:** the login template links here; a 404 on this link looks broken.

### 9.4 Modify `templates/proforma_invoice.php`
- Change upload button URL from `/order/{id}/payment` → `/order/{id}/payment-confirm`
- Inject dynamic bank details

### 9.5 Modify `templates/order_payment.php`
- Inject dynamic bank details
- Show different guidance if status is `payment_pending_upload` vs `payment_uploaded`

---

## Phase 10 — Quick Test Checklist

| # | Test | Expected |
|---|------|----------|
| 1 | Register new account | Account created, logged in, redirected to `/account` |
| 2 | Logout | Session cleared, nav shows Login |
| 3 | Guest checkout | Order placed, invoice shown, `user_id = null` |
| 4 | Guest uses /order/lookup | Finds order by number + email, sees invoice |
| 5 | Click "I have made payment" | Status → `payment_pending_upload`, admin Telegram alert sent |
| 6 | Upload receipt | Status → `payment_uploaded`, second Telegram alert sent |
| 7 | Admin confirms payment | Status → `payment_confirmed`, Telegram alert sent, "Create Shipment" button active |
| 8 | Admin declines payment | Status → `payment_declined`, reason stored, Telegram alert sent |
| 9 | Bank details on invoice | Match values saved in `/admin/settings` |
| 10 | Mobile layout | Only `modern.css` active, no `styles.css` conflicts |

---

## Summary of All Missing Routes

| Method | Route | Status | Action |
|--------|-------|--------|--------|
| POST | `/register` | **MISSING** | Create user + auto-login |
| POST | `/logout` | **MISSING** | Destroy session |
| GET | `/forgot-password` | **MISSING** | Render placeholder page |
| GET | `/account/profile` | **MISSING** | Render profile stub |
| GET | `/account/quotes` | **MISSING** | Render quotes stub |
| GET | `/order/lookup` | **MISSING** | Render lookup form |
| POST | `/order/lookup` | **MISSING** | Find order by num+email |
| GET | `/order/{id}/payment-confirm` | **MISSING** | "I have paid" page |
| POST | `/order/{id}/payment-confirm` | **MISSING** | Update status, notify admin |
| POST | `/admin/orders/{id}/confirm-payment` | **MISSING** | Approve payment |
| POST | `/admin/orders/{id}/decline-payment` | **MISSING** | Reject payment |
| POST | `/admin/orders/{id}/revert-to-awaiting` | **MISSING** | Reset status |
| POST | `/admin/orders/{id}/create-shipment` | **MISSING** | Create shipment for order |

---

**Next Step:** Once this plan is approved, implement Phase by Phase starting with the critical public auth routes (register, logout) and the admin payment routes, then the payment workflow changes.
