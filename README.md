# Avenue Hub - Business Listing & Service Finder
[cite_start][cite: 7, 217, 239]

[cite_start]A web-based directory and discovery platform designed to connect local businesses with customers[cite: 241]. [cite_start]This project provides a simple, reliable, and visually elegant platform for business owners to list their services and for users to find and contact them quickly[cite: 28, 242].

[cite_start]This project was submitted as a partial requirement for the Bachelor of Computer Application (Semester V)[cite: 203, 204].

## 🖼️ Screenshots

| Home Page | About Us Page |
| :---: | :---: |
| <img src="https_github_com_ZackDevd_AvenueHub/blob/main/screenshots/home.png?raw=true" alt="Home Page Screenshot" width="400"/> | <img src="https_github_com_ZackDevd_AvenueHub/blob/main/screenshots/about.png?raw=true" alt="About Page Screenshot" width="400"/> |
| [cite_start]**Login Page** [cite: 122] | [cite_start]**Registration Page** [cite: 158] |
| <img src="https_github_com_ZackDevd_AvenueHub/blob/main/screenshots/login.png?raw=true" alt="Login Page Screenshot" width="400"/> | <img src="https_github_com_ZackDevd_AvenueHub/blob/main/screenshots/register.png?raw=true" alt="Registration Page Screenshot" width="400"/> |

[cite_start]*(**Note:** You will need to create a folder named `screenshots` in your repository and upload your UI images there for them to display correctly. I have used placeholder paths above based on the screenshots in your presentation[cite: 103, 122, 138, 158].)*

## ✨ Key Features

* [cite_start]**User Module** [cite: 29][cite_start]: Secure registration and login for users and admins[cite: 258].
* [cite_start]**Business Module** [cite: 29][cite_start]: Authenticated users can submit their business details, including name, category, address, phone, description, and image[cite: 259].
* [cite_start]**Admin Module**[cite: 29]: A dashboard for administrators to:
    * [cite_start]Approve or delete pending business submissions[cite: 34, 266, 290].
    * [cite_start]Add or delete business categories[cite: 266].
    * [cite_start]View and manage user messages[cite: 266].
* [cite_start]**Search & Filter**: Users can search for businesses by keyword, city, and category[cite: 32, 261, 291].
* [cite_start]**Contact System** [cite: 29][cite_start]: A contact form for users to send messages, which are stored in the database for admin review[cite: 264, 265, 292].
* [cite_start]**Responsive Design**: Built with Bootstrap 5 for a clean, elegant, and cross-device compatible UI[cite: 267, 358].

## 🛠️ Technology Stack

* [cite_start]**Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5 [cite: 346, 356, 357, 358]
* [cite_start]**Backend**: PHP (Procedural) [cite: 343, 367]
* [cite_start]**Database**: MySQL [cite: 337, 368]
* [cite_start]**Server**: Apache (via XAMPP/WAMP) [cite: 303, 323, 371]
* **Security**:
    * [cite_start]Password Hashing using `password_hash()`[cite: 295, 374].
    * [cite_start]Prepared Statements to prevent SQL injection[cite: 375].
    * [cite_start]Form validation[cite: 376].

## 🔧 Installation & Setup

[cite_start]This project is designed to run on a local server environment like XAMPP or WAMP[cite: 303, 323].

1.  **Clone the repository:**
    ```sh
    git clone [https://github.com/ZackDevd/AvenueHub.git](https://github.com/ZackDevd/AvenueHub.git)
    ```
2.  **Place in `htdocs`:**
    Move the cloned `AvenueHub` folder into the `htdocs` directory of your XAMPP/WAMP installation.

3.  **Start Services:**
    Open your XAMPP/WAMP control panel and start the **Apache** and **MySQL** services.

4.  **Database Setup:**
    * Open `phpMyAdmin` (usually at `http://localhost/phpmyadmin`).
    * Create a new database.
    * Import the `.sql` file provided in the repository to set up the tables and data.
    * Ensure your database connection settings in the PHP files (e.g., `db_connect.php`) match your database name, username, and password.

5.  **Run the Application:**
    Open your web browser and navigate to `http://localhost/AvenueHub`.

## 📈 Future Enhancements

[cite_start]The current scope has several limitations [cite: 173, 174, 175] which open possibilities for future enhancements:

* [cite_start][ ] **Rating & Review System**[cite: 174, 178]: Allow users to rate businesses and leave feedback.
* [cite_start][ ] **Payment Integration**[cite: 173, 177]: Introduce premium listings or subscription models for businesses.
* [cite_start][ ] **Mobile App Version**[cite: 177]: Develop a native mobile app for Android and/or iOS.
* [cite_start][ ] **Advanced Search Filters**[cite: 175]: Add more detailed search options.

## 👥 Project Team

* [cite_start]**Chintan Kukadiya** - (3553) [cite: 9, 212]
* [cite_start]**Korat Satish** - (3551) [cite: 10, 213]
* [cite_start]**Jaat Shree Ram** - (3539) [cite: 11, 214]

### 🧑‍🏫 Project Guide
* **Mrs. [cite_start]Arti Agrawal** [cite: 13, 210]

---
[cite_start]*This project documentation was generated based on the SASCMA English Medium Commerce College (BCA) Semester V project report.* [cite: 4, 196, 204]
