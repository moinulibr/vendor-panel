# Current State

## Done
- products, variations tables (with new columns from v3 migration)
- Sales module migrations (12 tables) — NOT yet wired to models
- Purchase module migrations (16 tables) — NOT yet wired to models
- ProductSeeder (200 products)

## In Progress
- Migration bugs being fixed
- Models not yet created for sales/purchase

## TODO (Priority Order)
1. Fix migration bugs
2. Create Eloquent models for all sales tables
3. Create Eloquent models for all purchase tables
4. SaleService (create, batch allocate, recalc)
5. PurchaseService (receive, create bill, batch create)
6. Cart → Sale flow (API)
7. Image service (public → S3 ready)

## Not Started
- Accounting ledger
- Reports (93+)
- Vendor payouts