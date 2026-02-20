# Hydration and Break Reminder System

Design and development of a smart athlete hydration and break reminder system using IoT.

## Project Overview

This is a Laravel-based web application that helps athletes track their hydration levels, manage training sessions, and receive hydration alerts based on environmental conditions (temperature, humidity).

## Features

- **User Authentication**: Login and registration system
- **Session Management**: Create, track, and complete training sessions
- **Hydration Monitoring**: Real-time hydration score tracking
- **Environmental Alerts**: Sweat risk warnings based on temperature and humidity
- **Activity History**: View past sessions and hydration data
- **Responsive Design**: Mobile-first interface design

## Tech Stack

- **Backend**: Laravel 12.52.0
- **Frontend**: Blade templates, Vite, Tailwind CSS
- **Database**: MySQL (configured in .env)
- **Development**: PHP 8.4.16, Node.js with npm

## Installation

1. Clone the repository
2. Install PHP dependencies:
   ```
   composer install
   ```
3. Install Node dependencies:
   ```
   npm install
   ```
4. Copy `.env.example` to `.env` and configure your database
5. Generate application key:
   ```
   php artisan key:generate
   ```
6. Run migrations:
   ```
   php artisan migrate
   ```
7. Build assets:
   ```
   npm run build
   ```
8. Start the development server:
   ```
   php artisan serve
   ```

## Pages

- **Welcome** - Splash screen with auto-redirect
- **Login** - User authentication with forgot password link
- **Register** - New user signup with role selection (Coach/Athlete)
- **Home** - Dashboard with hydration stats and session timer
- **Session Creation** - Create new training session (sport, intensity, duration)
- **Active Session** - Live session tracking with temperature/humidity
- **Session Completed** - Session summary with hydration score
- **History** - View past sessions and activities
- **Password Reset** - Forgot password and set new password flows

## File Structure

```
resources/
├── views/          # Blade templates for all pages
├── css/           # Styling for each page
└── js/            # JavaScript files

routes/
└── web.php        # Application routes

public/
└── images/        # SVG and PNG assets
```

## Development

Run this to rebuild CSS and JS when making changes:
```
npm run build
```

For development mode with hot reload:
```
npm run dev
```


Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> b60d0e0 (Initial Commit : Login/Register, Homepage, History, Sessions Layout)
