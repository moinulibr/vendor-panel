# Current State

## Done
- products, variations tables (with new columns from v3 migration)
- Sales module migrations (12 tables) — NOT yet wired to models and others
- Purchase module migrations (16 tables) — NOT yet wired to models and others
- ProductSeeder (200 products)

## In Progress
- Models not yet created for sales/purchase

## TODO (Priority Order)
2. Create Eloquent models for all sales tables
3. SaleService (create, batch allocate, recalc)
4. Cart → Sale flow (API) (Sale create, edit, update, status change and more. Get cart related data in the cart repository - getSingleCartByCartAndUserId($cartId, $userId) methid )
5. Create Eloquent models for all purchase tables
6. 
7. PurchaseService (receive, create bill, batch create)
8. Image service (public → S3 ready)

## Not Started
- Accounting ledger
- Reports (93+)
- Vendor payouts