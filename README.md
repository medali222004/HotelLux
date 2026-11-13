<div align="center">

# 🏨 HôtelLux — Online Hotel Reservation System

A hotel website where guests can browse rooms, create an account, log in and book a stay, while an administrator manages all reservations from a dedicated dashboard.

</div>

---

## About the project

**HôtelLux** is a web application for an hotel, built with native PHP, native JavaScript and MySQL (no framework). It covers the full booking flow: a visitor discovers the rooms, registers, logs in, picks dates, and sends a reservation. The hotel staff then confirms or removes it from the admin dashboard.

The interface is in French and prices are displayed in Tunisian dinars (TND).

## Features

### 👤 Visitors & guests
- Home page with a hero section and **3 featured rooms**
- Full **rooms catalogue** with price, capacity and type (simple, double, suite)
- **Live search** by room name or type (AJAX, no page reload)
- **Filter** rooms by type (All / Simple / Double / Suite)
- **Registration** with validation (required fields, password confirmation, minimum 6 characters)
- **Real-time email check** during registration (tells you if the email is already used)
- **Login / Logout** with PHP sessions

### Authenticated users
- **Book a room** by choosing check-in and check-out dates
- **Automatic total price** calculation (live in the browser, then recomputed on the server)
- Date validation (check-out must be after check-in)
- **"My bookings"** page showing every reservation with its status (`pending`, `confirmed`, `cancelled`)

### Admin account
- Access to a protected **dashboard** listing all reservations (client, room, dates, total, status)
- **Confirm** a reservation (AJAX, no page reload)
- **Delete** a reservation (with confirmation prompt)
- The "Admin" link only appears in the menu for admin users, and the admin pages and endpoints are protected server-side

## Tech stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3 (custom dark/gold theme), JavaScript ES6 (Fetch API) |
| Backend | PHP 8.x (native, PDO) |
| Database | MySQL / MariaDB (via XAMPP) |
| Security | Prepared statements (PDO), `password_hash` / `password_verify`, `htmlspecialchars`, session-based role checks |

## Project structure

```
hotel-project/
├── index.php            # Home page (hero + featured rooms)
├── rooms.php            # All rooms, search and filters
├── auth.php             # Login / Registration (tabs)
├── booking.php          # Booking form + "My bookings"
├── dashboard.php        # Admin dashboard (admin only)
├── logout.php           # Destroys the session
├── back/                # Server-side logic
│   ├── config.php       # PDO database connection
│   ├── login.php        # Login handler
│   ├── register.php     # Registration handler
│   ├── book.php         # Create a booking
│   ├── search.php       # Room search (JSON)
│   ├── check_email.php  # Email availability (JSON)
│   ├── update_booking.php  # Change booking status (admin, JSON)
│   └── delete_booking.php  # Delete a booking (admin, JSON)
├── css/
│   └── style.css        # Global styles
├── js/
│   └── main.js          # Tabs, filters, AJAX, price calculation, admin actions
├── database/
│   └── hotel.sql        # Database schema + sample data
└── images/              # Room pictures and hero image
```

##  Database

The database is called `hotel_db` and contains three tables:

| Table | Description |
|---|---|
| `users` | `id`, `name`, `email` (unique), `password` (hashed), `role` (`user` / `admin`), `created_at` |
| `rooms` | `id`, `name`, `type` (`simple` / `double` / `suite`), `price_per_night`, `capacity`, `description`, `image`, `available` |
| `bookings` | `id`, `user_id` → users, `room_id` → rooms, `check_in`, `check_out`, `total_price`, `status` (`pending` / `confirmed` / `cancelled`), `created_at` |

The SQL file also inserts **6 sample rooms**, **1 admin**, **2 sample users** and **2 sample bookings**.

## Installation

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.x) or any equivalent stack (WAMP, MAMP, Laragon…)

### Steps

1. **Clone the repository** into the XAMPP web folder:
   ```bash
   cd C:/xampp/htdocs        # or /opt/lampp/htdocs on Linux
   git clone https://github.com/<your-username>/hotel-project.git
   ```

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Import the database**:
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Go to the **Import** tab and select `database/hotel.sql`
   - Click **Go** (the script creates `hotel_db` automatically)

4. **Check the database settings** in `back/config.php` (defaults match a fresh XAMPP install):
   ```php
   $host   = 'localhost';
   $dbname = 'hotel_db';
   $user   = 'root';
   $pass   = '';
   ```

5. **Open the site** in your browser:
   ```
   http://localhost/hotel-project/
   ```

## Demo accounts

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@hotel.com` | `Admin123` |
| User | `jean@example.com` | `password` |
| User | `marie@example.com` | `password` |

> ⚠️ These accounts are for local testing only.

## 🔄 How it works

```
Visitor ──► Browse rooms ──► Register / Login ──► Choose dates ──► Booking created (pending)
                                                                          │
                                       Admin dashboard ◄──────────────────┘
                                       Confirm ✅  or  Delete 🗑️
```

## Possible improvements

- Prevent overlapping bookings on the same room
- Let users cancel their own bookings
- Admin CRUD for rooms (add / edit / delete)
- CSRF protection on forms
- Show server-side flash messages (`$_SESSION['error']` / `['success']`) on the pages
- Email notifications when a booking is confirmed
- Online payment integration

## Authors

- **Lamloum Mohamed Ali**

## 📄 License

This project was created for educational purposes.
