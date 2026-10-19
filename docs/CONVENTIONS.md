# Coding Conventions

## Migrations
- NEVER modify existing migration files. Create new ALTER ones.
- NEVER use `migrate:fresh` on existing data. Always `php artisan migrate`.
- Every column MUST have `->comment()`.
- Every destructive migration (drop/rename) MUST use 4-step pattern (add temp → copy → drop → rename).
- Every migration MUST be idempotent: `Schema::hasColumn()`, `Schema::hasTable()`, `hasIndex()`.
- `down()` MUST only drop columns that `up()` added. Never drop pre-existing columns.

## Models
- app/Models — one per table, singular name
- Relationships via `$this->hasMany/hasOne/belongsTo`
- Accessors for derived fields (e.g., `image_url`, `effective_stock`)
- No business logic in models. All logic in Services.

## Services
- app/Services — one per module (`SaleService`, `PurchaseService`, `BatchService`)
- Methods are transactional (`DB::transaction`)
- Return DTOs or Models, never raw arrays

## API
- Routes under `/api/v1/*`
- Controllers in `app/Http/Controllers/Api/V1/`
- API Resources in `app/Http/Resources/`
- Form Requests for validation
- Response format: `{ success, data, message, errors }`
- HTTP status codes: 200 OK, 201 Created, 422 Validation, 500 Error

## Naming
- Tables: singular, snake_case (`sale_items`, not `sales_items`)
- Columns: snake_case, verbose (`unit_price_snapshot` not `up_snap`)
- Enums: lowercase snake_case (`partially_paid`)
- Booleans: `is_*`, `has_*`, `allow_*`, `track_*`

## Forbidden
- `DB::table()` in controllers (use models)
- `->get()` without pagination on list endpoints
- Storing full image URLs in DB (store relative path, resolve in accessor)
- Adding `foreignId()->constrained()` (project design: no DB-level FK)