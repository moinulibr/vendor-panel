# Project Context

## What
Multi-vendor ecommerce + POS + Dealer mobile app + SR panel. Single DB, multiple frontends.

## Stack
- Laravel 12, PHP 8.3, MySQL 8.0
- Queue: database driver
- Cache: file (dev), redis (prod)
- Storage: local public (dev)

## Channels (sales.source / sales.channel)
admin_pos, web_ecommerce, mobile_dealer_app, sr

## Customer Types (tier-based pricing)
regular, dealer, special, ecommerce

## Products
- ~2000 existing + growing
- Variants (Color/Size/material/origin..-dynamic), Combos, Services, Digital
- Batch-tracked (~40% -dynamic), Expiry-tracked (~24% -dynamic)
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