# ServiceHub-BD

ServiceHub-BD is a premium, high-fidelity local service marketplace designed and built to connect customers with skilled service providers (electricians, plumbers, AC technicians, tutors, painters, carpenters, etc.) in Bangladesh. 

Featuring a modern dark-mode glassmorphism user interface, the application delivers smooth layouts, micro-animations, and complete functional workflows for bookings, payments, ratings, and administration.

---

## 🚀 Key Features

### 1. Search & Browse System
* Autocomplete suggestions for **City** and **Service Skill** using matching query filters.
* Filter results dynamically by Service Category, City, and Rating.
* Public provider detail profile pages showing bio, rates, availability schedule, and customer reviews.

### 2. Provider Availability & Profile Management
* Interactive weekly availability slot editor (e.g., Saturday 09:00 AM - 05:00 PM).
* Dynamic service skill mapping (e.g., Electrician, Plumber, AC Tech).
* Profile photo uploads and description updates.
* Registration approval queue (unapproved providers are locked until admin reviews them).

### 3. Smart Booking Engine
* Validation checks preventing bookings outside the provider's active days/hours.
* Double-booking guard preventing duplicate bookings for overlapping time slots.
* Cancellation flow with user-provided cancellation reasons.
* Automatic price calculator (hourly rate × duration).

### 4. SSLCommerz Payment Gateway Integration
* Secure checkout redirection to the official SSLCommerz sandbox gateway.
* Supports Cards (Visa, MasterCard, AMEX, Nexus), Mobile Banking (bKash, Nagad, Rocket, Upay), and Net Banking.
* Auto-calculates platform fees (15% platform deduction, 85% provider payout) and generates unique transaction tracking codes (`SSLC_...`).

### 5. Provider Wallet & Withdrawal Center
* Real-time wallet tracking: Total Earnings, Withdrawable Balance, Pending Payouts, and Total Withdrawn.
* Pop-up payout requests (minimum ৳100 BDT) supporting bKash, Nagad, Rocket, and Bank transfers.
* Detailed withdrawal logs tracking status (`pending`, `approved`, `rejected`).

### 6. Interactive Customer Reviews & Replies
* Post-service review prompt (1–5 star selection and comment box) once bookings are completed.
* Strict unique constraints preventing duplicate reviews.
* Automatic dynamic updates of provider rating metrics.
* Inline provider replies to reviews, visible on the provider's public profile page.

### 7. Administrative Workspace
* Glassmorphism statistics summary cards.
* Tabbed control tabs to approve/reject pending providers and withdrawal requests.
* Database registry of All Bookings (trans ID details) and All Users (customer, provider, admin).

### 8. Breeze Authentication & Security
* Secure registration, login, email verification, and password reset flows powered by Laravel Breeze.
* Prevent Back History middleware: custom HTTP cache-control filters applied to all authenticated routes to block access to sensitive dashboards via browser back navigation after logout.

### 9. AI-Powered Service Assistant
* Smart chatbot assistant on the landing page powered by the Groq API (using the high-performance `llama-3.3-70b-versatile` model).
* Real-time conversational analysis of household/repair problems supporting English and Bengali queries.
* Automated extraction and classification of required service categories (e.g. matching descriptions to "Electrician").

---

## 🛠️ Technology Stack
* **Framework**: Laravel 12
* **Language**: PHP 8.2+
* **Database**: SQLite / MySQL
* **Styling**: Vanilla CSS + Tailwind CSS (via CDN)
* **Testing**: PHPUnit Feature Integration Tests

---

## ⚙️ Installation & Setup

1. **Clone & Navigate**:
   ```bash
   cd final_project
   ```

2. **Install Backend Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   Duplicate `.env.example` as `.env`, configure your database, add your SSLCommerz sandbox keys and Groq API key:
   ```env
   SSLCOMMERZ_STORE_ID=testbox
   SSLCOMMERZ_STORE_PASSWORD=qwerty
   SSLCOMMERZ_IS_SANDBOX=true

   GROQ_API_KEY=your_groq_api_key_here
   ```
   Then generate the application key:
   ```bash
   php artisan key:generate
   ```

4. **Initialize Database & Seed Demo Data**:
   ```bash
   php artisan migrate:refresh --seed
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
   Access the app at `http://127.0.0.1:8000`.

---

## 🔑 Demo Login Credentials

The seeder initializes the database with pre-configured accounts:

| Role | Email | Password | Details |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@servicehub.com` | `password` | Dashboard with approvals and database registry. |
| **Customer 1** | `customer@test.com` | `password` | Rahim Ahmed (Dhaka). Has bookings & review history. |
| **Customer 2** | `customer2@test.com` | `password` | Fahim Chowdhury (Chittagong). |
| **Provider 1** | `provider@test.com` | `password` | Babul Electrician (Dhaka). Approved. Has earnings & wallet. |
| **Provider 2** | `kamal@test.com` | `password` | Kamal AC Tech (Chittagong). Approved. |
| **Provider 3** | `pending@test.com` | `password` | Pending Repairer (Dhaka). Needs admin approval. |

---

## 🧪 Running Automated Tests
Run the integration test suite to verify all workflows:
```bash
php artisan test
```
*(All 61 feature tests covering auth, profiles, bookings, SSLCommerz gateway integration, withdrawals, reviews, and admin features are verified green).*
