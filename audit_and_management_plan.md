# STREICHER GmbH: Audit and Management Plan (2026)

**Prepared by**: Antigravity (Business Manager)
**Status**: DRAFT / FOR REVIEW
**Subject**: Operational Audit, UX Refactoring, and Scalability Roadmap

---

## 1. Executive Summary
Following my appointment as Manager, I have conducted a deep-dive audit of the Streicher GmbH codebase, database, and user interface. While the core "German Engineering" spirit is present, the digital infrastructure currently suffers from "siloed data" and "manual bottlenecks." This plan outlines the critical lapses and the corrective actions required to transform the store into a world-class procurement portal.

---

## 2. Technical Audit: Lapses & Risks

### A. Database Management (The "Linking" Problem)
The current CSV-based system lacks relational integrity. 
*   **The Issue**: Data in `orders.csv` is disconnected from `products.csv`. If a product is updated or deleted, the order history becomes orphaned.
*   **Risk**: Loss of sales intelligence and customer history.
*   **Action**: Implement a `RelationManager` service in PHP to enforce integrity across flat files.

### B. Missing Core Tables
The business is currently operating with several "blind spots":
1.  **[COMPLETED] Inventory/Stock**: Created `inventory.csv`. Tracking stock, reorder points, and lead times.
2.  **[COMPLETED] Customer CRM**: Created `customers.csv`. Tracking lifetime value and procurement history.
3.  **[COMPLETED] Supplier Management**: Created `suppliers.csv`. Centralized contact data for industrial partners.
4.  **[COMPLETED] Technical Specs**: Created `product_specs.csv`. Addressing the specification discovery lapse.

### C. UX & Conversion Lapses
*   **[COMPLETED] Technical Discovery**: Industrial buyers search by *specifications*. Implemented `SpecificationRepository`, updated catalog routing, and added a dynamic filter sidebar.
*   **[COMPLETED] Mobile Gap**: Mobile responsiveness overhaul complete and verified.
*   **Checkout Friction**: The transition from Cart to Quote request is currently too manual.

---

## 3. Management Implementation Plan (Short-Term)

### Phase 1: Database Hardening (COMPLETED)
*   [x] **Create Inventory Module**: Initialized `data/db/inventory.csv`.
*   [x] **Create CRM Module**: Initialized `data/db/customers.csv`.
*   [x] **Create Supplier Module**: Initialized `data/db/suppliers.csv`.
*   [x] **Schema Standardization**: Normalized headers and removed BOMs from core tables.

### Phase 2: UX Refactoring (COMPLETED)
*   [x] **Specification Sidebars**: Added technical attribute filtering to the `/catalog` page.
*   [x] **Dynamic Pricing**: Integrated real-time `EUR/USD` exchange rates across Cart and Checkout, including logistical costs and tax calculations.
*   [x] **Checkout Friction**: Humanized form vocabulary, implemented real-time stock badges, and integrated inventory decrementing upon order placement.

### Phase 3: Automation (IN PROGRESS)
*   [x] **Auto-Correlator**: Implemented `AutoCorrelator` service and admin route to sync images with SKUs.
*   [x] **Telegram Admin Integration**: Real-time order alerts now dispatched to the owner's Telegram.
*   [ ] **Low Stock Alerts**: Automated notifications when inventory drops below safety thresholds.

---

## 4. Recommendations for the Owner (User)

As the owner, your focus should be on the **Supply Chain** and **Content**:
1.  **Image Procurement**: We have ~61 products currently hidden because they lack photos. Please provide these so we can unlock the full catalog.
2.  **Product Specifications**: Provide a spreadsheet with technical specs (dimensions, materials, tolerances) so I can implement the "Advanced Filter" system.
3.  **Review the Telegram Bot**: Decide which alerts you want to receive (e.g., "Any order > €5,000" or "Every quote request").

---

## 5. Conclusion
Streicher GmbH has the potential to dominate the digital industrial equipment space. By moving from a "Simple Store" to a "Relational Procurement Portal," we will reduce overhead and increase conversion rates.

**Next Step**: I will begin creating the `inventory.csv` and `customers.csv` structures. Please confirm if you approve of this expanded schema.
