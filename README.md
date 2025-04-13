# 📊 Subscription Manager

**Subscription Manager** is a complete web-based application designed to help users keep track of all their recurring subscriptions in one place. Built with PHP, MySQL, HTML, CSS, and JavaScript — it's clean, responsive, and works 100% offline.

---

## 🔧 Features

- ✅ User Signup and Login (with secure password hashing)
- 📅 Add, Edit, and Delete Subscriptions
- 📆 Interactive Calendar View (highlighting due dates and recurring logic)
- 📈 Analytics Dashboard (spending insights, active subs, most/least expensive)
- 📘 About and Contact Pages (with team profiles)
- 📁 Modular Codebase (each page is split for reusability and readability)
- 💻 Works offline (no external fonts or CDNs)

---

## 🖥️ Technologies Used

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Environment:** XAMPP / Apache NetBeans

---

## 📁 Folder Structure


![folderstructure](https://github.com/user-attachments/assets/8a6bb38c-92e1-4f95-949d-3b162da93ad9)

📦 your-project-folder/
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── images/
├── config.php
├── index.php
├── signup.php
├── home.php
├── calendar.php
├── analysis.php
├── about.php
├── contact.php
├── edit_subscription.php
├── logout.php
├── header.php
└── footer.php

---

## 🚀 How to Run This Project Locally

1. **Clone the Repository**

- git clone https://github.com/your-username/subscription-manager.git

2. **Move Project to XAMPP**

- Copy the folder into `C:/xampp/htdocs` (or your local server's root directory)

4. **Start XAMPP**

- Start Apache and MySQL from the XAMPP Control Panel

4. **Create the Database**

- Visit `http://localhost/phpmyadmin`
- Create a new database named `subscription_db`
- Run the following SQL:

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  dob DATE NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  renewal_date DATE NOT NULL,
  recurrence ENUM('Monthly', 'Yearly', 'Weekly') NOT NULL,
  recurrence_count INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

5. **Visit the Project**

- http://localhost/subscription-manager/

---

## ✨ Screenshots
> _Optional: Add screenshots for login, dashboard, calendar, analysis, etc._

---

## 📄 License
This project is built for learning and academic purposes. Feel free to fork and improve!

