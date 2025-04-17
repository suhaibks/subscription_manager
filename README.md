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

1. **Signup**
![signup](https://github.com/user-attachments/assets/8d289685-42b5-47ab-becb-c1392c3fd4e0)

2. **Login**
![login](https://github.com/user-attachments/assets/f15b979f-7596-425d-a3d8-7dca4885efce)

3. **Home**
![home](https://github.com/user-attachments/assets/ea7f8f8e-687b-4b17-9f13-ad30418b316b)
![home2](https://github.com/user-attachments/assets/2b7df758-9933-4fa9-9933-9f48998630af)
![home3](https://github.com/user-attachments/assets/55bbfa8e-02e5-4d01-be62-8725b874836d)
![home4](https://github.com/user-attachments/assets/c0b9be2b-7338-421c-bc39-4d91dcc2ccfb)
![home5](https://github.com/user-attachments/assets/a98e95e7-6fd4-49a4-af24-3c37fbe6740f)


5. **Calendar**
![calendar](https://github.com/user-attachments/assets/7fb61f60-1101-4446-a024-f34ec8918eca)

6. **Analysis**
![analysis](https://github.com/user-attachments/assets/eaf8d3b5-e07c-42f6-b9ad-ae96d2e9f680)

7. **About**
![about](https://github.com/user-attachments/assets/d90c6719-14da-43e1-8b7e-b325ad948729)


---

## 📄 License
This project is built for learning and academic purposes. Feel free to fork and improve!

