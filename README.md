# ECOMMERCE

Welcome to the **OUR** Web Application!  
This platform allows users to buy and sell gaming accounts easily and securely.

---

## 🌟 Features

- ✅ Google Login Integration
- 🛒 Add to Cart and Checkout System
- 📦 Dynamic Product Listings
- 📱 Fully Responsive Design
- 🔔 Admin Notifications via Telegram
- 📧 Email Notifications via SMTP
- 🇰🇭 KHQR Top-up Payment Integration

---

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/net-account-seller.git

   
## Move into the project folder:
cd net-account-seller

## Configure your environment:
Database connection (config.php)
Google OAuth credentials
Telegram Bot Token
Email SMTP settings

### Telegram order notifications

Set the following values in the project-root `.env` file. The bot must be started by the recipient account before it can send that account a message.

```dotenv
Telegram_bot_token=YOUR_BOT_TOKEN
Telegram_chat_id=YOUR_CHAT_ID
```

Order notifications contain the product name, price, customer name, phone number, location, and uploaded payment receipt (JPG, PNG, WEBP, GIF, HEIC, HEIF, or PDF up to 5 MB). Receipts are forwarded to Telegram and retained outside the public document root for authenticated admin review. Set `ECOMMERCE_PRIVATE_RECEIPT_DIR` to choose another private writable directory; the application rejects paths inside the public document root. Never request or send account passwords or two-factor authentication codes. Keep `.env` private and do not commit it.

### Product inventory

Products have a non-negative stock amount managed from the admin dashboard. Inventory is reserved during checkout to prevent overselling and restored if Telegram rejects the notification or an admin cancels the order. Products with zero stock are hidden from the storefront and cannot be ordered. Each product has an editable low-stock threshold, and the admin inventory page records stock changes, reasons, and the responsible admin. The stock and threshold columns are added automatically when the site is opened; existing products start with a threshold of 5.

### Admin sales and activity reports

The admin dashboard requires a username and password from the `admins` table. Online orders are initially Pending, and an uploaded receipt never automatically marks an order Paid. An admin must independently verify the transaction ID and exact amount in the order review before the payment becomes verified. Rejections and refunds are recorded in the order history; refunds are records of transfers completed outside this app. The operations page provides order search and history, customer search/notes and phone-based checkout blocking, inventory management, daily/weekly/monthly reports, and Excel-compatible CSV exports. Existing orders without a recorded payment state remain Pending verification.

Set each product's cost price in the dashboard to calculate estimated profit. Cost is optional when unknown; sales without a recorded cost are excluded from known-cost profit totals rather than being treated as zero-cost profit. Analytics, order history, customer profile, stock audit tables, and sales metadata columns are created automatically on first use; they are also included in `databases/ecommerce.sql`. The SQL file is an initialization dump and contains destructive `DROP TABLE` statements; do not import it over a live database as a migration.

## Start local server (example using XAMPP or similar):
Place in htdocs
Access via http://localhost/net-account-seller

🛠 Tech Stack
Frontend: HTML, CSS, JavaScript
-Backend: PHP 8+
-Database: MySQL
-Auth: Google OAuth 2.0
-Notifications: Telegram API + PHPMailer

🤝 Contributing
Contributions are welcome!
Please fork the repo and submit a pull request.
Make sure to follow best practices and comment your code.

📜 License
This project is licensed under the MIT License.

📧 Contact
For inquiries or support, contact us via Telegram or email.


Would you like the ASCII art “NET” in a different style, or should I help you generate a large banner-style title using other characters like `#`, `=`, or Unicode art?
