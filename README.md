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

### 4. Mock bKash Payment Integration
* Authentic pink checkout gateway interface validating 11-digit wallet numbers and 4-digit PIN codes.
* 15% platform fee escrow deduction; 85% provider payout ledger credit.
* Unique transaction ID generator (`TRX...`) to track checkouts.

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
   Duplicate `.env.example` as `.env`, configure your database, and run:
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
| **Provider 1** | `provider@test.com` | `password` | Karim Electrician (Dhaka). Approved. Has earnings & wallet. |
| **Provider 2** | `kamal@test.com` | `password` | Kamal AC Tech (Chittagong). Approved. |
| **Provider 3** | `pending@test.com` | `password` | Pending Repairer (Dhaka). Needs admin approval. |

---

## 🧪 Running Automated Tests
Run the integration test suite to verify all workflows:
```bash
php artisan test
```
*(All 31 assertions covering bookings, validation, payouts, reviews, and admin dashboard are verified green).*
