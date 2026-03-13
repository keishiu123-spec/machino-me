# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**KIDS COMPASS** — a Laravel 12 web app for parents in Setagaya ward to share local knowledge about kids' spots, safety info, Q&A, and ambassador communications from verified lesson providers. The project spec is in `shiyousyo.md` (Japanese).

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
- **Frontend**: Blade views with Tailwind CSS (CDN), Google Maps JS API, Chart.js
- **Maps**: Google Maps JavaScript API with AdvancedMarkerElement, Places, MarkerClusterer

### Key Models & Relationships
- `User` — has `role` field (user/ambassador/admin), `organization_name`, `avatar_url`, `bio`
- `Spot` — locations with lesson metadata. Has many `Review`s, `Comment`s, `AmbassadorPost`s. Has optional `ambassador_user_id` linking to the managing ambassador
- `Review` — structured reviews with satisfaction/skill_growth/parent_burden ratings + vibe_tag
- `Question` — community Q&A posts
- `Comment` — belongs to questions
- `AmbassadorPost` — belongs to User (ambassador) and Spot. Photo + short message + mood_tag

### Controllers
- `SpotController` — map view (home page), spot CRUD, review & comment storage
- `QuestionController` — Q&A timeline
- `AmbassadorPostController` — public timeline (index) + ambassador-only posting (create/store)
- `SearchController` — cross-model search (spots, questions, ambassador posts)

### Routes
- `/` and `/spots` — map/spot listing (home)
- `/questions` — Q&A timeline
- `/ambassador` — public ambassador communications timeline
- `/ambassador/create` — ambassador-only posting form (requires auth + ambassador role)
- `/search` — search page
- `/dev/login-as/{userId}` — dev-only auto-login for testing
- Comments posted via `POST /questions/{question}/comments`

### Authorization
- `EnsureUserIsAmbassador` middleware — checks `role` is `ambassador` or `admin`
- Ambassador routes use `['auth', EnsureUserIsAmbassador]` middleware stack
- Unauthenticated users redirect to `/` (configured in bootstrap/app.php)

### Views
- `resources/views/layouts/app.blade.php` — shared layout with bottom nav (マップ/質問箱/通信/さがす)
- Feature views under `spots/`, `questions/`, `ambassador/`, `search/`

## Notes

- User authentication uses Laravel's built-in session auth; general user_id is hardcoded to `1` for non-auth actions
- Ambassador users (id 4-6) can be logged in via `/dev/login-as/{id}` for testing
- The app is in Japanese; UI text, comments, and the spec document are all in Japanese
- Image uploads stored via `public` disk under `spots/` and `ambassador/` directories
- Google Maps API key stored in `.env` as `GOOGLE_MAPS_API_KEY`, accessed via `config('services.google_maps.api_key')`
- Events feature has been replaced by Ambassador Communications (アンバサダー通信)
