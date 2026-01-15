# 🎓 Club Management System

A comprehensive web-based management system for university student clubs and organizations, built with Laravel 12 and modern web technologies.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [User Roles](#user-roles)
- [Security Features](#security-features)
- [License](#license)

## 🎯 Overview

The Club Management System is a thesis project designed to streamline the management of student clubs and organizations in universities. It provides a complete workflow from club registration to member management, with multi-level approval processes and comprehensive administrative controls.

## ✨ Features

### For Club Officers & Members
- **Club Registration**: Submit club registration requests with required documents
- **Member Management**: Add, view, and manage club members
- **Application System**: Students can apply to join clubs
- **Dashboard**: View club statistics, members, and activities
- **Document Management**: Upload and manage club documents (Constitution, Budget Proposal, etc.)
- **Club Renewals**: Submit renewal applications with supporting documents

### For Administrators
- **Multi-Level Approval Workflow**:
  - Dean Endorsement (Step 1)
  - PSG Council Approval (Step 2)
  - Director Noting (Step 3)
  - VP for Academics Final Approval (Step 4)
- **Sequential Approval Status**: Visual tracking of approval progress
- **Club Monitoring**: View all registered clubs, their status, and members
- **Decision Support System**: Track violations and manage club suspensions
- **Violation Management**: Record and track club violations
- **Notifications System**: Automated notifications for approval steps
- **Reports Generation**: Generate comprehensive reports for clubs and members

### Security Features
- **Password Validation**: Strong password requirements
- **Session Management**: Secure session handling with automatic timeout (30 minutes)
- **CSRF Protection**: All forms protected with CSRF tokens
- **Role-Based Access Control**: Separate access levels for different user types
- **File Upload Security**: Validated file types and sizes

## 🛠️ Tech Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **Frontend**: Tailwind CSS 3.x, Alpine.js
- **Build Tool**: Vite
- **PDF Generation**: barryvdh/laravel-dompdf

## 💻 System Requirements

- PHP >= 8.2
- Composer >= 2.0
- MySQL >= 8.0
- Node.js >= 18.x
- NPM >= 9.x

## 📦 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/club-management-system.git
cd club-management-system
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clubsystem
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

### 6. Build Assets

```bash
npm run dev    # For development
npm run build  # For production
```

### 7. Start Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## ⚙️ Configuration

### Default Admin Accounts

**⚠️ CHANGE THESE PASSWORDS IMMEDIATELY AFTER FIRST LOGIN!**

| Role | Email | Password |
|------|-------|----------|
| Head of Student Affairs | head.studentaffairs@club.com | admin123 |
| Director | director.studentaffairs@club.com | director123 |
| VP for Academics | vp.academics@club.com | vp123 |
| Dean | dean@club.com | dean123 |

## 👥 User Roles

### Club Users
- **Officers**: Register clubs, manage members, submit applications
- **Members**: View club information, participate in activities

### Administrators
- **Head of Student Affairs**: Full system access
- **Director**: Note registrations (Step 3)
- **VP for Academics**: Final approval (Step 4)
- **Dean**: Endorse registrations (Step 1)
- **PSG Council Adviser**: Approve registrations (Step 2)

## 🔒 Security Features

- Session-based authentication with 30-minute timeout
- CSRF token validation
- Password hashing with Bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection in Blade templates
- File upload validation

### Production Security Checklist

- [ ] Change all default admin passwords
- [ ] Set `APP_DEBUG=false`
- [ ] Use HTTPS
- [ ] Configure rate limiting
- [ ] Enable database backups
- [ ] Monitor logs

## 📁 Key Directories

```
club-management-system/
├── app/Http/Controllers/  # Application controllers
├── app/Models/           # Eloquent models
├── database/migrations/  # Database migrations
├── resources/views/      # Blade templates
├── routes/web.php       # Web routes
└── storage/app/         # File storage
```

## 🚀 Deployment

### Production Steps

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

Set in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

## 🐛 Troubleshooting

**Clear cache issues:**
```bash
php artisan optimize:clear
composer dump-autoload
```

**Permission issues:**
```bash
chmod -R 775 storage bootstrap/cache
```

## 📄 Files Excluded from Git

- `.env` - Environment variables
- `/vendor/` - PHP dependencies
- `/node_modules/` - Node dependencies
- `/storage/*.key` - Encryption keys

**⚠️ NEVER commit sensitive files!**

## 📜 License

This project is developed as a thesis project. All rights reserved.

---

**Note**: This is an educational project. Ensure proper security measures for production use.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
