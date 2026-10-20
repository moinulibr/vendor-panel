# Antigravity Project Rules

You are helping with a Laravel 11 multi-vendor ecommerce + POS system.

## Before Every Task
1. Read `/docs/PROJECT_CONTEXT.md` and `/docs/CONVENTIONS.md`.
2. Confirm you understand the constraint: existing DB has 2000 products. Never suggest `migrate:fresh`.
3. If a task requires modifying a table, always create an ALTER migration, never modify the original.

## Migration Rules (STRICT)
- Every column: `->comment('...')`
- Boolean columns: use `$table->boolean()`, default explicit
- Enum columns: explicit values array
- Add `hasColumn()` guard for every column
- `down()` must only drop newly-added columns
- Never use `->change()` — use raw SQL `ALTER TABLE ... MODIFY`
- Never use `foreignId()->constrained()` — project has no DB-level FK

## Model Rules
- No business logic in models
- Casts: `boolean`, `decimal:2`, `array` for JSON columns
- Accessors prefixed `get*Attribute`

## Service Rules
- All service methods must be wrapped in `DB::transaction`
- Never return arrays — return Models or DTOs
- Logging: use `Log::info` for creation, `Log::warning` for edge cases

## API Rules
- Prefix: `/api/v1/app`
- Response envelope: `{ success: bool, data: mixed, message: string, errors: array }`
- Validation via FormRequests
- Pagination on ALL list endpoints
- Return `Resource` classes, never raw models

## File Scope
When I ask for a task, do NOT modify files outside the task scope.
Ask before touching: routes/api.php, config/, .env, existing migrations.

## Output Format
- Return COMPLETE files, not diffs
- Include `<?php` and all `use` statements
- Include every method — no `// ...` placeholders
- If file > 300 lines, split into logical chunks with clear headers