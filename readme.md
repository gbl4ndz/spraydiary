# Spray Diary

A greenhouse spray management application built with Laravel 10 and Filament 3. Track spray sessions, manage chemicals, assign tasks to operators, and monitor timekeeping records.

## Requirements

- PHP 8.1+
- Composer
- SQLite (default) or MySQL/PostgreSQL

## Setup

```bash
git clone <repo-url>
cd spraydiary

composer install

cp .env.example .env
php artisan key:generate

# SQLite (default — no extra setup needed)
touch database/database.sqlite
php artisan migrate

php artisan serve
```

Visit **http://127.0.0.1:8000/admin** and log in.

### MySQL / PostgreSQL

Update `.env` with your database credentials before running migrations:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spraydiary
DB_USERNAME=root
DB_PASSWORD=secret
```

## Demo Data

To seed demo users, roles, chemicals, blocks, sheds, tasks and time records:

```bash
php artisan db:seed --class=DemoSeeder
```

Or create an admin manually via Tinker:

```bash
php artisan tinker --execute="
\$role = \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
\$user = \App\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'password']);
\$user->assignRole('admin');
"
```

## Default Credentials

| Email | Password |
|---|---|
| admin@demo.com | password |

## Features

### Spray Management
- **Chemicals** — full CRUD with chemical type, components, rates, withhold period and pest/disease target
- **Tasks** — assign spray tasks to operators
- **Timekeeping** — start/stop timer per session; records block, shed(s), chemical, tank capacity and liquid used; live elapsed-time counter

### Greenhouse
- **Blocks** and **Sheds** — manage your greenhouse structure

### Administration
- **Users** — create and manage operator accounts
- **Roles & Permissions** — powered by [spatie/laravel-permission](https://github.com/spatie/laravel-permission)

### Dashboard
- **Active session widget** — live running timer shown on the dashboard when a spray session is in progress
- **Recent sessions widget** — paginated table of the last completed spray sessions across all operators

## Tech Stack

| Layer | Package |
|---|---|
| Framework | Laravel 10 |
| Admin UI | Filament 3 |
| Roles & Permissions | spatie/laravel-permission 5 |
| Image processing | intervention/image 3 |
| Database abstraction | doctrine/dbal 3 |

## Project Structure

```
app/
├── Filament/
│   ├── Pages/
│   │   └── Timekeeping.php       # Live timer page
│   ├── Resources/
│   │   ├── ChemicalResource.php
│   │   ├── BlockResource.php
│   │   ├── ShedResource.php
│   │   ├── TaskResource.php
│   │   ├── TimeResource.php
│   │   ├── UserResource.php
│   │   ├── RoleResource.php
│   │   └── PermissionResource.php
│   └── Widgets/
│       ├── ActiveTimerWidget.php  # Dashboard live timer
│       └── RecentTimesWidget.php  # Dashboard recent sessions
├── Providers/
│   └── Filament/
│       └── AdminPanelProvider.php
├── Block.php
├── Chemical.php
├── ChemType.php
├── Shed.php
├── Task.php
├── Time.php
└── User.php
```
