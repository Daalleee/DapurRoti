# Stock Management Implementation TODO

## Migration
- [x] Create migration to add 'stock' column to products table (integer, default 0, nullable false)

## Model Updates
- [x] Update Product model to include 'stock' in fillable array (already included)

## Controller Updates
- [x] Update ProductController to handle stock in store and update methods
- [x] Update CartController to validate quantity <= product stock on update
- [x] Update OrderController to decrement stock on order creation

## View Updates
- [x] Add stock input field to resources/views/admin/products/create.blade.php
- [x] Add stock input field to resources/views/admin/products/edit.blade.php
- [x] Update resources/views/cart/index.blade.php to set max quantity to product stock
- [x] Add stock column to resources/views/admin/products/index.blade.php (if exists)

## Database
- [x] Run php artisan migrate (stock column already exists)

## Testing
- [x] Test adding product with stock (products already seeded)
- [x] Test cart quantity limits (CartItemPolicy created)
- [ ] Test stock reduction on order placement
