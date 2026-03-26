# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**みっけ（MIKKE）** — a Laravel 12 web app for parents in Setagaya ward to discover local kids' spots on a map. v1 focuses on spot registration, map browsing, and lightweight experience reports. The project spec is in `shiyousyo.md` (Japanese).

## Common Commands

- **Setup**: `composer setup` (installs deps, generates key, runs migrations, builds frontend)
- **Dev server**: `composer dev` (starts Laravel server, queue worker, Pail logs, and Vite concurrently)
- **Run tests**: `composer test` or `php artisan test`
- **Run single test**: `php artisan test --filter=TestName`
- **Lint/format PHP**: `./vendor/bin/pint`
- **Migrations**: `php artisan migrate`

## Architecture

- **Framework**: Laravel 12 (PHP 8.2+), Blade templates (no Inertia/Vue despite spec mentioning it)
- **Database**: SQLite (default via `.env.example`), session/cache/queue all use `database` driver
- **Frontend**: Blade views with Tailwind CSS (CDN), Google Maps JS API
- **Maps**: Google Maps JavaScript API with AdvancedMarkerElement, Places, MarkerClusterer

### Key Models & Relationships
- `User` — has `role` field (user/ambassador/admin), `organization_name`, `avatar_url`, `bio`
- `Spot` — locations with lesson metadata. Has many `Review`s, `Comment`s, `AmbassadorPost`s
- `Review` — v1: vibe_tag + monthly_fee (price range) + body (comment). Legacy 5-star columns are nullable.
- `Question` — community Q&A posts (v2)
- `Comment` — belongs to questions (v2)
- `AmbassadorPost` — belongs to User (ambassador) and Spot (v2)

### Controllers
- `SpotController` — map view (home page), spot CRUD, review storage
- `QuestionController` — Q&A timeline (v2, routes commented out)
- `AmbassadorPostController` — ambassador features (v2, routes commented out)
- `SearchController` — cross-model search (v2, routes commented out)

### Routes (v1 active)
- `/` and `/spots` — map/spot listing (home)
- `/spots/create` — spot registration (auth required)
- `/spots/{spot}/reviews` — experience report submission (auth required)
- `/mypage` — user profile, favorites, my school setting
- `/auth/line` — LINE login
- `/dev/login-as/{userId}` — dev-only auto-login for testing

### Routes (v2, commented out)
- `/questions` — Q&A timeline
- `/ambassador` — public ambassador communications
- `/search` — cross-model search
- All ambassador-specific routes

### Authorization
- `EnsureUserIsAmbassador` middleware — checks `role` is `ambassador` or `admin` (v2)
- Unauthenticated users redirect to `/` (configured in bootstrap/app.php)

### Views
- `resources/views/layouts/app.blade.php` — shared layout with bottom nav (マップ / FAB / マイページ)
- Feature views under `spots/`, `mypage/`
- v2 views preserved but not active: `questions/`, `ambassador/`, `search/`

## Notes

- User authentication uses Laravel's built-in session auth with LINE login
- The app is in Japanese; UI text, comments, and the spec document are all in Japanese
- Image uploads stored via `public` disk under `spots/` and `ambassador/` directories
- Google Maps API key stored in `.env` as `GOOGLE_MAPS_API_KEY`, accessed via `config('services.google_maps.api_key')`
- v2 features (Q&A, ambassador, search, compare, radar chart) are commented out with `{{-- v2: ... --}}` or `/* v2: ... */` markers — do NOT delete these
- Design tokens (indigo/ink, cream, coral, sage, gold) are maintained from the original design
