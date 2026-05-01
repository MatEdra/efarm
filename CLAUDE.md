# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Smart Farming Philippines (eFarm)** is a web-based agricultural management platform with two separate portals: one for administrators and one for farmers. It is a **traditional PHP application** — no framework (no Laravel/Symfony), no Composer, no npm, no build step.

- **Stack**: PHP 8.2.12 · MySQL/MariaDB 10.4.32 · Bootstrap 5.3 (CDN) · Vanilla JS
- **Server**: XAMPP (Apache)
- **Database**: `smart_farming_ph`

## Development Setup

1. Place project in `/xampp/htdocs/efarm`
2. Import `smart_farming_ph.sql` into MySQL
3. Access at `http://localhost/efarm/`
4. Default admin login: phone `09171234567` / password `admin123`

No build process — PHP files are served directly by Apache. Changes take effect on page refresh.

All front-end libraries (Bootstrap 5.3, Font Awesome 6.4, SweetAlert2, Chart.js) are loaded from CDN — internet access is required for the UI to render correctly.

## Architecture

The app has two completely separate portals sharing a common database connection:

```
/include/conn.php          ← single DB connection file (mysqli, shared by all)
/function/                 ← login/logout/register handlers (legacy, no auth guard)
/admin/                    ← admin portal (pages + APIs + components)
/user/                     ← farmer portal (pages + APIs + components)
/uploads/materials/        ← uploaded PDFs/videos served to farmers
```

### Dual-Portal Pattern

Both `/admin/` and `/user/` follow the same internal structure:

- **Pages** (`index.php`, `farmers.php`, etc.) include `include/auth.php` at the top for session validation, then include `sidebar.php` and `top_nav.php` as layout components
- **APIs** live in `function/` subdirectories (e.g., `/admin/function/farmers_api.php`) and return JSON
- **Styling** is one CSS file per portal (`style.css`)
- **JS** is one file per portal (`javascript.js`)

Pages load data dynamically via `fetch()`/AJAX to the JSON APIs — they don't render data server-side. The one exception is `user/index.php`, which reads session variables directly for the welcome message.

### Session Authentication

- Admin pages check `$_SESSION['user']['type'] === 'admin'` (via `/admin/include/auth.php`)
- Farmer pages check `$_SESSION['farmer_id']` (via `/user/include/auth.php`)
- Both redirect to login if session is missing

**Admin session shape** (set by `function/admin-login.php`):
```php
$_SESSION['user'] = ['id', 'name', 'email', 'phone_number', 'role', 'type' => 'admin']
```

**Farmer session shape** (set by `function/farmer-login.php`):
```php
$_SESSION['farmer_id'], $_SESSION['farmer_name'], $_SESSION['farmer_email'],
$_SESSION['farmer_phone'], $_SESSION['farm_name'], $_SESSION['farm_location'],
$_SESSION['farm_size'], $_SESSION['experience_years'], $_SESSION['user_type'] = 'farmer'
```

Some API endpoints (e.g., `admin/function/materials_api.php`) include `auth.php` directly for their own access guard. Most API endpoints under `admin/function/` do **not** include auth — they assume the calling page already checked the session.

### API Pattern

All API endpoints follow this structure:

```php
include_once '../../include/conn.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => ''];
// switch on GET/POST/PUT/DELETE
// functions use: global $conn, $response;
echo json_encode($response);
```

Response shape: `{ "success": bool, "message": string, "data": mixed }`

## Database Schema (Key Tables)

| Table | Purpose |
|---|---|
| `farmers` | Farmer accounts (phone_number used as username) |
| `admins` | Admin accounts with `role` (super_admin / content_manager) |
| `crops` | Crop reference data with `season_id` FK and agronomy fields |
| `seasons` | Planting season definitions |
| `farmer_crops` | `(farmer_id, crop_type)` — `crop_type` is **free text**, not a FK to `crops` |
| `learning_materials` | Uploaded educational files; `uploaded_by` FK to `admins.id` |
| `material_categories` | Tags/categories for materials |
| `weather_data` | Historical weather by location |

`farmer_crops.crop_type` is a free-text string populated from the farmer registration form. It is not constrained to the `crops` table.

## Security Considerations

The codebase has a **split security model**:

- **Newer API files** (`admin/function/farmers_api.php`, etc.): use `password_hash()` / `password_verify()` and prepared statements — this is the correct pattern to follow
- **Legacy login handlers** (`function/admin-login.php`, `function/farmer-login.php`): use `real_escape_string()` + plaintext password comparison (`$password === $row['password']`). The seed data in `smart_farming_ph.sql` stores plaintext passwords — this is intentional for the dev seed but the login handlers themselves need to be updated before production use

When writing any new or modified code:
- Use **prepared statements** (`$stmt = $conn->prepare(...)`) — not `real_escape_string()`
- Use `password_hash()` / `password_verify()` for passwords
- Database credentials are hardcoded in `/include/conn.php` (no `.env`)

## Adding a New Feature

1. Add/alter tables in MySQL (update `smart_farming_ph.sql` if schema changes)
2. Create API endpoint in the appropriate `/admin/function/` or `/user/function/`
3. Create or update the page in `/admin/` or `/user/`
4. Update `sidebar.php` if adding navigation
5. Add styles to `style.css` and JS to `javascript.js` in the portal

## Conventions

- PHP variables: `snake_case`
- JS variables: `camelCase`
- CSS classes: `kebab-case`
- DB tables: plural `snake_case`; columns: `snake_case`
- File names: `snake_case` (e.g., `farmers_api.php`, `get_weather.php`)
