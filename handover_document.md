# Project Handover: Streicher Industrial Procurement Portal

## 1. Project Overview
A low-compute, event-driven procurement portal for **Streicher GmbH**, built with PHP and a custom flat-file database system (CSV). The application is hosted on **Railway** with **Fastly CDN** for edge caching.

### Key Infrastructure:
- **Database**: Custom `CsvDb` and `CsvPdo` shim (mapping SQL queries to local `.csv` files in `data/db/`).
- **Storage**: Persistent storage must be enabled on Railway for `data/db/` to prevent data loss on redeploy.
- **Caching**: Fastly aggressively caches the root path (`/`). Use query parameters (e.g., `?v=123`) to bypass cache during testing.

---

## 2. Critical Blocker: Admin Authentication
**Status:** Incomplete/Failing.
The user expects to log in with `mgr@streichergmbh.com` / `Americana12`.

### Diagnostic Findings:
- The `users.csv` file on the live server currently contains only `admin@streichergmbh.com`.
- The `mgr@streichergmbh.com` account is missing despite seeding attempts.
- Recent attempts to create the user via a diagnostic route in `index.php` failed to persist, likely due to filesystem write permissions or Railway's ephemeral storage behavior.
- **Root Cause**: The `UserRepository::findByEmail` or `CsvPdo` parsing may be failing on specific SQL syntax or hidden characters in the CSV headers.

### Required Action:
1. Restore the `mgr@streichergmbh.com` account with `password_hash` in `users.csv`.
2. Verify that `password_verify()` works in the `routes_admin.php` login handler.
3. Clean up the `index.php` file (currently contains temporary diagnostic logic at the top).

---

## 3. Pending Features & Enhancements

### A. Bank Account Management
- **Goal**: Allow admins to set and update corporate bank details (IBAN, BIC, Bank Name) via the Admin Panel.
- **Logic**: Store these as keys in the `settings` table (managed via `settings.csv`).
- **Display**: These details should appear on the finalized Quote/Invoice for the customer.

### B. Frontend & Mobile Experience
- **Issue**: The UI is reported as "scattered" on mobile devices.
- **Requirement**: 
    - Implement a fully responsive CSS layout in `index.css`.
    - Fix the mobile navigation menu (currently buggy or incomplete).
    - Ensure product cards and tables scale correctly on small screens.
    - Follow the "Premium Design" guidelines: use gradients, modern typography (Inter/Roboto), and subtle micro-animations.

### C. Invoice to PDF
- **Issue**: The "Print to PDF" button does not correctly download a formatted document.
- **Requirement**: 
    - Implement a server-side PDF generator (or a robust client-side `window.print` workaround).
    - Ensure the PDF contains the Streicher logo, order details, and the newly added bank account information.
    - Trigger an automatic download to the user's device.

---

## 4. Technical Debt & Notes
- **CsvPdo Limitations**: The SQL parser in `app/Services/CsvPdo.php` is a simplified regex-based engine. Complex joins or nested queries are not supported.
- **Permissions**: Ensure `data/db/` and `web/uploads/` are writable by the PHP process on Railway.
- **Environment Variables**: Critical variables are `TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID`, and `APP_ENV`.

## 5. Next Steps for Success
1. **Fix Authentication**: Manually verify `users.csv` state and fix the login logic.
2. **Mobile Audit**: Rewrite the header and product grid CSS for better responsiveness.
3. **PDF Implementation**: Integrate a lightweight PDF library or refine the print stylesheet.
