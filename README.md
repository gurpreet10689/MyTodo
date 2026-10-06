# PHP Task Tracker

A complete beginner-friendly Task Tracker built with PHP, MySQL and Bootstrap.

## Features
- User registration and secure login
- Session-based authentication
- Add, edit, view and delete tasks
- Pending / In Progress / Completed status
- Low / Medium / High priority
- Due dates
- Search and filtering
- Dashboard task statistics
- Profile management
- Responsive Bootstrap UI

## Requirements
- XAMPP/WAMP/LAMP
- PHP 8+
- MySQL/MariaDB
- Web browser

## Installation in XAMPP
1. Copy the `task_tracker` folder to:
   `C:/xampp/htdocs/`
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open phpMyAdmin:
   `http://localhost/phpmyadmin`
4. Import:
   `database/task_tracker.sql`
5. Check `config/database.php`. Default XAMPP settings are:
   host = localhost
   user = root
   password = empty
6. Open:
   `http://localhost/task_tracker/`
7. Register a new account and start adding tasks.

## Demo login
Email: demo@example.com
Password: password

## Suggested student extensions
- Admin dashboard
- Task assignment to other users
- Categories/tags
- File attachments
- Task comments
- Notifications
- Calendar view
- AJAX status updates
- REST API
- Charts using Chart.js
- Email reminders
