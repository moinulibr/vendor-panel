# Database Schema Overview

## Master
- users, customers, vendors, warehouses
- brands, categories, units, variant_attributes
- products (denormalized cache), variations (source of truth)
- coupons

## Sales Module
- sales, sale_items, sale_item_batches (FIFO cost tracking)
- sale_payments, sale_payment_summary
- sale_returns, sale_return_items
- sale_refunds, sale_credits
- sale_taxes, coupon_usages
- sale_edit_history, sale_edit_items

## Purchase Module
- purchase_orders, purchase_order_items
- purchase_receipts (GRN), purchase_receipt_items
- purchase_bills, purchase_bill_items, purchase_bill_taxes
- purchase_bill_edit_history, purchase_bill_edit_items
- vendor_payments, vendor_payment_allocations
- purchase_returns, purchase_return_items
- vendor_debit_notes

## Inventory
- product_batches (live stock per batch)
- batch_summaries (analytics cache)

## Cross-cutting
- payment_ledger (running balance for customer + vendor)

## Key Rules
- Price snapshot: unit_price_snapshot locked at transaction time. Never changes.
- Tax snapshot: tax_type_snapshot + tax_rate_snapshot locked at line level.
- Version chain: parent_id + version + is_current (only is_current=1 is "live")
- Immutable: no DELETE on sales/purchases. is_current=0 for history.
- Batch allocation: FIFO from oldest non-expired batch.