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

Order notifications contain the product name, price, customer name, phone number, location, and uploaded payment receipt (JPG, PNG, WEBP, or PDF up to 5 MB). Receipt files are sent to Telegram and are not stored by the application. Never request or send account passwords or two-factor authentication codes. Keep `.env` private and do not commit it.

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
