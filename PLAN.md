# Inventory Management System — Implementation Plan

## Features Covered

- Products
- Categories
- Suppliers
- Purchase Orders
- Sales
- Barcode Generation
- Stock History
- Low Inventory Alerts

---

## 1. Project Setup

| Step | Action |
|------|--------|
| 1.1 | Create a new Laravel 11 project: `composer create-project laravel/laravel .` |
| 1.2 | Configure `.env` (database, app name) |
| 1.3 | Install Breeze + Blade for auth scaffolding (login/dashboard shell) |
| 1.4 | Install `picqer/php-barcode-generator` for barcode generation |
| 1.5 | Run `php artisan migrate:fresh` after all migrations are in place |

---

## 2. Database Schema (8 Migrations)

### `categories`
- `id`, `name` (string, unique), `slug`, `description` (nullable text), `is_active` (boolean), timestamps

### `suppliers`
- `id`, `name`, `contact_person`, `email`, `phone`, `address` (text), `is_active` (boolean), timestamps

### `products`
- `id`, `name`, `slug`, `sku` (unique), `barcode` (unique, nullable)
- `category_id` (FK → categories, nullable), `supplier_id` (FK → suppliers, nullable)
- `cost_price` (decimal 10,2), `selling_price` (decimal 10,2)
- `stock_quantity` (integer, default 0), `low_stock_threshold` (integer, default 10)
- `unit` (string), `is_active` (boolean), timestamps + soft deletes

### `purchase_orders`
- `id`, `order_number` (unique, auto: PO-YYYYMMDD-NNN)
- `supplier_id` (FK → suppliers), `order_date`, `received_date` (nullable)
- `status` (enum: pending, partial, received, cancelled)
- `total_amount`, `notes`, timestamps

### `purchase_order_items`
- `id`, `purchase_order_id` (FK, cascade), `product_id` (FK)
- `quantity_ordered`, `quantity_received`, `unit_cost`, `subtotal`

### `sales`
- `id`, `sale_number` (unique, auto: SAL-YYYYMMDD-NNN)
- `sale_date`, `total_amount`, `notes`, timestamps

### `sale_items`
- `id`, `sale_id` (FK, cascade), `product_id` (FK)
- `quantity`, `unit_price`, `subtotal`

### `stock_histories`
- `id`, `product_id` (FK), `type` (purchase/sale/adjustment/return)
- `quantity_change`, `reference_type`, `reference_id`, `notes`
- Indexed on `type` and `(reference_type, reference_id)`

---

## 3. Models & Relationships

| Model | Relationships |
|-------|--------------|
| **Category** | hasMany(Product) |
| **Supplier** | hasMany(Product), hasMany(PurchaseOrder) |
| **Product** | belongsTo(Category/Supplier), hasMany(StockHistory/PurchaseOrderItem/SaleItem) |
| **PurchaseOrder** | belongsTo(Supplier), hasMany(PurchaseOrderItem) |
| **PurchaseOrderItem** | belongsTo(PurchaseOrder/Product) |
| **Sale** | hasMany(SaleItem) |
| **SaleItem** | belongsTo(Sale/Product) |
| **StockHistory** | belongsTo(Product) |

### Observers
- **PurchaseOrderItemObserver** — on `saved`: updates `Product.stock_quantity`, writes `stock_histories` row
- **SaleItemObserver** — on `created`: decrements `Product.stock_quantity`, writes `stock_histories` row

---

## 4. Controllers (8)

| Controller | Actions |
|------------|---------|
| **DashboardController** | index — stats, low-stock alerts, recent activity |
| **CategoryController** | Resource CRUD |
| **SupplierController** | Resource CRUD |
| **ProductController** | Resource CRUD + `showBarcode($id)` |
| **PurchaseOrderController** | index, create, store, show, edit, update, receive, destroy |
| **SaleController** | index, create, store, show, destroy |
| **StockHistoryController** | index (filterable by product, type, date range) |
| **ReportController** | stock, sales, purchases reports with filters |

---

## 5. Routes (40+)

All grouped under `middleware: ['auth', 'verified']`:

```
GET  /                           → Dashboard
GET  /categories                 → CategoryController@index
POST /categories                 → CategoryController@store
...  (full CRUD per resource)

GET  /products/{product}/barcode → ProductController@showBarcode
POST /purchase-orders/{po}/receive → PurchaseOrderController@receive

GET  /stock-history              → StockHistoryController@index
GET  /reports/stock              → ReportController@stock
GET  /reports/sales              → ReportController@sales
GET  /reports/purchases          → ReportController@purchases
```

---

## 6. Form Request Validation (9 classes)

- `StoreCategoryRequest`, `UpdateCategoryRequest`
- `StoreSupplierRequest`, `UpdateSupplierRequest`
- `StoreProductRequest`, `UpdateProductRequest`
- `StorePurchaseOrderRequest`, `ReceivePurchaseOrderRequest`
- `StoreSaleRequest`

---

## 7. Barcode Generation

- Library: `picqer/php-barcode-generator` (Code 128, PNG output)
- Auto-generated on Product create: `BAR` + 10 random digits
- Route `/products/{product}/barcode` returns the PNG image
- Displayed inline on Product show page

---

## 8. Low Inventory Alerts

- Dashboard card lists products where `stock_quantity <= low_stock_threshold`
- Red highlight (`bg-red-50`) on product index rows that are low stock
- Bold red stock count for low-stock products

---

## 9. Blade Views (24+ templates)

- **Layout:** extends Breeze's `app.blade.php`, updated navigation with inventory links
- **Dashboard:** stats cards, low-stock table, recent stock movements, recent POs, recent sales
- **CRUD per resource:** `index` (table + pagination), `create`, `edit`, `show`
- **Purchase Orders:** dynamic JS add/remove items on create, receive form on show page
- **Sales:** dynamic JS add/remove items with auto-price from product selection
- **Stock History:** filterable by product ID, type, date range
- **Reports:** summary cards + filtered tables for stock, sales, purchases

---

## 10. Test Coverage

| Test File | Tests | Assertions |
|-----------|-------|------------|
| CategoryTest | 6 | 7 |
| SupplierTest | 5 | 6 |
| ProductTest | 8 | 13 |
| PurchaseOrderTest | 5 | 11 |
| SaleTest | 6 | 11 |
| StockHistoryTest | 3 | 7 |
| **Total** | **33** | **55** |

---

## 11. Execution Order (Build Phases)

| Phase | What |
|-------|------|
| **Phase 1** | Project init + Breeze + barcode package + DB config |
| **Phase 2** | Migrations (all 8 tables) |
| **Phase 3** | Models + relationships + observers |
| **Phase 4** | Form request validation classes |
| **Phase 5** | Controllers + routes |
| **Phase 6** | Blade views (dashboard, CRUD per resource) |
| **Phase 7** | Barcode generation + stock history auto-logging |
| **Phase 8** | Reports + low-inventory alerts on dashboard |
| **Phase 9** | Feature tests (33 tests, 55 assertions) |
| **Phase 10** | Full verification (58 tests, 116 assertions) |

---

## 12. Run Script

```bash
./run.sh                    # starts on http://127.0.0.1:8000
./run.sh --port=8080        # custom port
./run.sh --host=0.0.0.0     # listen on all interfaces
```

Auto-runs migrations on start to keep the database up-to-date.
