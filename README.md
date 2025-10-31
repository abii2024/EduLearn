# 📚 EduLearn - Modern Education Management System# EduLearn - Project Fix Summary



Een professioneel Learning Management System (LMS) gebouwd met PHP MVC architecture, gericht op een veilige en schaalbare oplossing voor educatieve instellingen.## Issues Fixed



[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://php.net)### 1. **Directory Path Issues**

[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)- Fixed incorrect paths in `routing_entry.php` to match the actual `core /` directory (with space)

[![Status](https://img.shields.io/badge/Status-Active-success)](https://github.com/abii2024/EduLearn)- Updated all file includes to use correct relative paths



## 🌟 Features### 2. **Database Model Issues**

- Added missing `initializeDatabase()` methods to all models:

### Core Functionaliteit  - `User::initializeDatabase()`

- 🔐 **Secure Authentication**: Multi-level gebruikerssysteem (Admin, Teacher, Student)  - `SalesModel::initializeDatabase()`

- 👥 **User Management**: Complete CRUD operaties voor gebruikers  - `NewsModel::initializeDatabase()`

- 📰 **News System**: Nieuws publicatie en beheer- Fixed incorrect database connection paths in `BaseModel.php`

- 📊 **Dashboard**: Rol-gebaseerde dashboards

- 🛡️ **Security**: Password hashing, SQL injection preventie, XSS bescherming### 3. **Controller Issues**

- 🎨 **Responsive Design**: Werkt op alle apparaten- Fixed method names in `AuthController.php` to match routes:

- 📁 **MVC Architecture**: Schone, onderhoudbare code structuur  - `showLogin()` and `processLogin()`

  - `showRegister()` and `processRegister()`

## 🏗️ Architecture- Fixed incorrect file paths in controller includes

- Removed references to non-existent models (`Lesson`, `Assignment`)

```

EduLearn/### 4. **View Issues**

├── app/- Added `rinder` security constant check to all view files

│   ├── controllers/    # Application logic- Fixed CSS path in `header.php` to work with XAMPP structure

│   ├── models/         # Database interactions- Corrected view file includes in controllers

│   └── views/          # Presentation layer

├── core/               # Framework core### 5. **Security Improvements**

├── public/             # Web root- Added `define('rinder', true)` to `routing_entry.php`

└── config/             # Configuration- Added security checks to all view files: `if (!defined('rinder')) { die('Direct access not permitted'); }`

```

### 6. **Database Setup**

## 🚀 Quick Start- Created proper database table creation SQL in initialization methods

- Added automatic database initialization on each request

### XAMPP Setup (Simpelst)

## How to Use

1. **Installeer XAMPP** van [apachefriends.org](https://www.apachefriends.org/)

1. Make sure XAMPP is running with Apache and MySQL

2. **Clone Project**2. Create a database named `EduLearn` in phpMyAdmin

   ```bash3. Access the site at: `http://localhost/EduLearn/public/`

   cd C:/xampp/htdocs/  # Windows4. The database tables will be created automatically on first visit

   git clone https://github.com/abii2024/EduLearn.git

   ```## Available Routes



3. **Start Services**- `/` - Homepage

   - Start Apache + MySQL in XAMPP Control Panel- `/login` - Login page

- `/register` - Registration page

4. **Open Browser**- `/dashboard` - User dashboard (requires login)

   ```- `/news` - News page

   http://localhost/EduLearn/public/- `/logout` - Logout

   ```

   Database wordt automatisch aangemaakt!## Sample Data



### PHP Built-in ServerRun `/scripts/sample_data.php` to add sample news and sales data to the database.


```bash
git clone https://github.com/abii2024/EduLearn.git
cd EduLearn/public
php -S localhost:8000
```

## 📖 Routing

```
/              → Homepage
/login         → Login
/register      → Registratie
/dashboard     → Dashboard (auth required)
/news          → Nieuws
```

## 🔒 Security

✅ Password hashing (bcrypt)  
✅ SQL injection preventie  
✅ XSS protection  
✅ CSRF tokens  
✅ Role-based access  

## 📚 Documentatie

Zie `/Documentatie` map voor:
- Database ERD
- Routing systeem
- Security implementatie
- User stories & wireframes

## 👨‍💻 Auteur

**Abdisamad Abdulle**  
GitHub: [@abii2024](https://github.com/abii2024)  
Portfolio: [https://abii2024.github.io/portfolio/](https://abii2024.github.io/portfolio/)

---

**Made with ❤️ for Education** | © 2024-2025
