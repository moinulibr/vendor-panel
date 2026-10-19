# Project Context

## What
Multi-vendor ecommerce + POS + Dealer mobile app + SR panel. Single DB, multiple frontends.

## Stack
- Laravel 11, PHP 8.3, MySQL 8.0
- Queue: database driver
- Cache: file (dev), redis (prod)
- Storage: local public (dev), AWS S3 (prod)

## Channels (sales.source / sales.channel)
pos, web, mobile_dealer_app, sr

## Customer Types (tier-based pricing)
regular, dealer, special

## Products
- ~2000 existing + growing
- Variants (Color/Size), Combos, Services, Digital
- Batch-tracked (~40%), Expiry-tracked (~24%)
- Per-product VAT config: exclusive/inclusive/exempt
- Per-product discount policy (allow_discount, max_discount_percent, min_sale_price)

## Multi-Vendor
- products.vendor_id, variations.vendor_id = marketplace seller
- sale_items.vendor_id = which vendor supplied the sold unit
- Vendor commission tracked per line item

## Not in scope (yet)
- Accounting ledger module (later phase)
- Payroll / HR
- Manufacturing