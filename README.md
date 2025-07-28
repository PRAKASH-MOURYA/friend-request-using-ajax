 👥 Friend Request System using AJAX

This project implements a simple **friend request feature** using **AJAX**, simulating how modern social platforms like Facebook or Instagram handle friend or follow requests without page reloads.

## 🚀 Features

- Send and cancel friend requests without refreshing the page
- Update UI dynamically based on request status
- Backend integration with PHP and MySQL
- Clean and modular codebase using jQuery and vanilla JavaScript

## 🧰 Tech Stack

- **Frontend:** HTML, CSS, JavaScript, jQuery
- **Backend:** PHP
- **Database:** MySQL
- **Communication:** AJAX (Asynchronous JavaScript and XML)

## 📸 Preview

> *(You can add a screenshot here)*

```html
[ Send Request ] → [ Request Sent ] → [ Cancel Request ]
🛠️ How to Run Locally
Clone the repository

bash
Copy
Edit
git clone https://github.com/PRAKASH-MOURYA/friend-request-using-ajax.git
cd friend-request-using-ajax
Setup your database

Create a database named friendsystem

Import db.sql (if available) or manually create required tables:

sql
Copy
Edit
CREATE TABLE friend_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  status ENUM('pending','accepted','cancelled') DEFAULT 'pending'
);
Run a local PHP server

bash
Copy
Edit
php -S localhost:8000
Open in browser:
http://localhost:8000/index.html

Make sure PHP is installed locally and MySQL credentials are configured correctly in your PHP files.

📂 Folder Structure
cpp
Copy
Edit
friend-request-using-ajax/
├── index.html
├── script.js
├── style.css
├── send_request.php
├── cancel_request.php
└── db.php
✨ To Do
Add user authentication

Friend request notifications

Accept/decline functionality

🤝 Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss the feature or fix.

📄 License
This project is licensed under the MIT License.

Made with ❤️ by Prakash Mourya
