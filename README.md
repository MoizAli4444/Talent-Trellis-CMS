# Talent Trellis CMS

## Overview  
This is a Laravel-based blog application with authentication and a CMS for managing posts and articles. It includes various features such as role-based access control, server-side data handling, background job processing, and more.  

The blog allows users to sign in and use a CMS to create new posts or articles. Posts and articles are treated as separate entities, each having its own dedicated page. The application includes a landing page, a blog page, and an articles page. Signed-in users can add, edit, and delete posts and articles without requiring any coding. The website's theme is open to creativity, allowing for a unique and engaging user experience.  


## Features  
- **Laravel UI Auth** – User authentication system  
- **Validation Request Files** – Form validation with request classes  
- **Services for Controller Code** – Organized service layer for business logic  
- **Traits/Helpers** – Reusable functions for cleaner code  
- **Server-side Datatables** – Efficient data display and pagination  
- **Middleware** – Access control and security  
- **Flash Messages** – User-friendly notifications  
- **Queue Jobs** – Asynchronous processing  
- **Observers & Event Service Provider** – Model event handling  
- **Error Handling in Blade** – Improved user experience  
- **Accessors & Mutators** – Data transformation in models  
- **Spatie Role & Permission** – Role-based access control  
- **Unit & Feature Tests** – Ensuring code reliability  
- **Dummy Image Handling** – Default image when no upload is provided  
- **and more** 

## Usage  

### Temporary Admin Credentials  
- **Email:** `test@example.com`  
- **Password:** `password`  

⚡ **Setup Instructions:**  
Make sure to run:  
```bash
php artisan migrate --seed
