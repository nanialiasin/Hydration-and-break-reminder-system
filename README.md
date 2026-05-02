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
- **Database**: SQLite (default) or MySQL
- **Development**: PHP 8.4.16, Node.js with npm

## Requirements

- PHP 8.4+
- Composer
- Node.js & npm
- Laravel Herd (recommended) or any local PHP server

## Setup Instructions

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Install Node dependencies

```bash
npm install
```

### 3. Configure environment

Copy the example env file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

> If a `.env` file is already included in the submission, skip this step.

### 4. Set up the database

```bash
php artisan migrate --seed
```

This creates all tables and populates them with initial data.

### 5. Build frontend assets

```bash
npm run build
```

### 6. Serve the application

**With Laravel Herd:** Place the project folder inside your Herd directory and visit `http://HydraPulse.test`.

**Without Herd:**

```bash
php artisan serve
```

Then visit `http://localhost:8000`.

### 7. (Optional) Sensor bridge

The IoT sensor integration requires Python. To run the sensor bridge:

```bash
pip install -r requirements.txt
python scripts/sensor_bridge.py
```

## Clearing Cache

If you encounter stale pages or config issues, run:

```bash
php artisan optimize:clear
```
