# الفصل الثالث: التصميم
## 3.3 مخطط الفئات (Class Diagram)

---

## 3.3.1 المقدمة

يوثّق هذا القسم **مخطط الفئات** (Class Diagram) الذي يمثّل البنية الكائنية للنظام، ويصف:

- **الفئات الرئيسية** (Classes) وخصائصها وعملياتها
- **العلاقات** بين الفئات (Associations, Inheritance, Composition)
- **التعددية** (Multiplicity)

يعتمد التصميم على مبادئ **البرمجة الكائنية** (OOP) ومبادئ **SOLID** لضمان القابلية للصيانة والتوسع والاختبار.

---

## 3.3.2 مخطط الفئات العام

```mermaid
classDiagram
    class User {
        +int id
        +string full_name
        +string email
        +string password
        +enum role
        +timestamp email_verified_at
        +store() Store
        +cart() Cart
        +orders() Order[]
        +reviews() Review[]
        +wishlists() Wishlist[]
        +notifications() Notification[]
        +isAdmin() bool
        +isMerchant() bool
        +isCustomer() bool
    }
    
    class Store {
        +int id
        +int user_id
        +string name
        +text description
        +string logo_path
        +enum status
        +timestamp approved_at
        +user() User
        +products() Product[]
        +coupons() Coupon[]
        +isActive() bool
    }
    
    class Category {
        +int id
        +string name
        +string slug
        +int parent_id
        +bool is_active
        +parent() Category
        +children() Category[]
        +products() Product[]
    }
    
    class Product {
        +int id
        +int store_id
        +int category_id
        +string name
        +text description
        +decimal price
        +int stock
        +string image_path
        +decimal avg_rating
        +bool is_active
        +store() Store
        +category() Category
        +cartItems() CartItem[]
        +orderItems() OrderItem[]
        +reviews() Review[]
        +isInStock() bool
        +discountedPrice(coupon) decimal
    }
    
    class Cart {
        +int id
        +int user_id
        +user() User
        +items() CartItem[]
        +total() decimal
        +itemCount() int
        +isEmpty() bool
        +clear() void
    }
    
    class CartItem {
        +int id
        +int cart_id
        +int product_id
        +int quantity
        +cart() Cart
        +product() Product
        +subtotal() decimal
    }
    
    class Order {
        +int id
        +int user_id
        +int coupon_id
        +string order_number
        +decimal subtotal
        +decimal discount
        +decimal total
        +enum status
        +enum payment_method
        +text shipping_address
        +string phone
        +user() User
        +coupon() Coupon
        +items() OrderItem[]
        +statusHistory() OrderStatusHistory[]
        +canBeCancelled() bool
        +markAsConfirmed() void
        +markAsShipped() void
        +markAsDelivered() void
    }
    
    class OrderItem {
        +int id
        +int order_id
        +int product_id
        +int store_id
        +decimal unit_price
        +int quantity
        +decimal subtotal
        +order() Order
        +product() Product
        +store() Store
    }
    
    class Coupon {
        +int id
        +int store_id
        +string code
        +enum type
        +decimal value
        +decimal min_order
        +int max_uses
        +int used_count
        +timestamp expires_at
        +bool is_active
        +store() Store
        +orders() Order[]
        +isValid() bool
        +calculateDiscount(subtotal) decimal
    }
    
    class Review {
        +int id
        +int user_id
        +int product_id
        +int rating
        +text comment
        +bool is_verified_purchase
        +user() User
        +product() Product
    }
    
    class Wishlist {
        +int id
        +int user_id
        +int product_id
        +user() User
        +product() Product
    }
    
    class Notification {
        +string id
        +int user_id
        +string type
        +json data
        +timestamp read_at
        +user() User
        +markAsRead() void
    }
    
    class OrderStatusHistory {
        +int id
        +int order_id
        +enum status
        +text note
        +order() Order
    }
    
    User "1" --> "0..1" Store : owns
    User "1" --> "0..1" Cart : has
    User "1" --> "*" Order : places
    User "1" --> "*" Review : writes
    User "1" --> "*" Wishlist : has
    User "1" --> "*" Notification : receives
    
    Store "1" --> "*" Product : sells
    Store "1" --> "*" Coupon : offers
    
    Category "1" --> "*" Product : classifies
    Category "1" --> "*" Category : parent
    
    Product "1" --> "*" CartItem : in
    Product "1" --> "*" OrderItem : in
    Product "1" --> "*" Review : has
    Product "1" --> "*" Wishlist : in
    
    Cart "1" *-- "*" CartItem : contains
    Order "1" *-- "*" OrderItem : contains
    Order "1" *-- "*" OrderStatusHistory : logs
    Order "*" --> "0..1" Coupon : uses
```

---

## 3.3.3 الفئات بالتفصيل

### 3.3.3.1 Class: `User`

**الوصف:** يمثّل مستخدم النظام (Admin / Merchant / Customer).

| المكوّن | النوع | الوصف |
|---------|------|-------|
| `id` | int | معرّف فريد |
| `full_name` | string | الاسم الكامل |
| `email` | string | البريد (فريد) |
| `password` | string | bcrypt hash |
| `role` | enum | admin/merchant/customer |
| `email_verified_at` | timestamp | تاريخ التحقق |

**العمليات:**
| العملية | الإرجاع |
|---------|---------|
| `store()` | Store (لو merchant) |
| `cart()` | Cart (لو customer) |
| `orders()` | Order[] |
| `isAdmin()` | bool |
| `isMerchant()` | bool |
| `isCustomer()` | bool |

---

### 3.3.3.2 Class: `Store`

**الوصف:** يمثّل متجر تاجر.

| المكوّن | النوع |
|---------|------|
| `user_id` | int (FK, UNIQUE) |
| `name` | string |
| `status` | enum (pending/active/suspended) |
| `approved_at` | timestamp |

**العمليات:** `products()`, `coupons()`, `isActive()`

---

### 3.3.3.3 Class: `Product`

**الوصف:** يمثّل منتج ملابس.

| المكوّن | النوع | الوصف |
|---------|------|-------|
| `price` | decimal | السعر |
| `stock` | int | المخزون |
| `avg_rating` | decimal | متوسط التقييم (Denorm.) |
| `is_active` | bool | معروض |

**العمليات:** `store()`, `category()`, `reviews()`, `isInStock()`, `discountedPrice(Coupon)`

---

### 3.3.3.4 Class: `Cart` + `CartItem`

**Cart:** سلة الزبون (واحدة فقط).
- `items()`, `total()`, `itemCount()`, `isEmpty()`, `clear()`

**CartItem:** عنصر في السلة.
- `subtotal()` = `quantity * product.price`

**العلاقة:** `Cart *-- CartItem` (**Composition**)

---

### 3.3.3.5 Class: `Order` + `OrderItem`

**Order:** رأس الطلب.
- `canBeCancelled()`, `markAsConfirmed()`, `markAsShipped()`, `markAsDelivered()`

**OrderItem:** عنصر في الطلب (Snapshot تاريخي).

**العلاقة:** `Order *-- OrderItem` (**Composition**)

---

### 3.3.3.6 Class: `Coupon`

**الوصف:** كوبون خصم.

**العمليات:**
- `isValid()` → bool
- `calculateDiscount(subtotal)` → decimal

---

### 3.3.3.7 فئات مساعدة

| Class | الوصف |
|-------|-------|
| `Review` | تقييم منتج (1-5 نجوم) |
| `Wishlist` | منتج في المفضلة |
| `Notification` | إشعار داخلي (UUID) |
| `OrderStatusHistory` | سجل تغيرات حالة الطلب |

---

## 3.3.4 العلاقات بين الفئات

| العلاقة | النوع | Multiplicity | Cascade |
|---------|-------|---------------|---------|
| `User → Store` | Association | 1 : 0..1 | CASCADE |
| `User → Cart` | Association | 1 : 0..1 | CASCADE |
| `User → Order` | Association | 1 : * | CASCADE |
| `Store → Product` | Aggregation | 1 : * | CASCADE |
| `Category → Product` | Association | 1 : * | RESTRICT |
| `Cart → CartItem` | **Composition** | 1 : * | CASCADE |
| `Order → OrderItem` | **Composition** | 1 : * | CASCADE |
| `Order → Coupon` | Association | * : 0..1 | SET NULL |

### الفرق بين الأنواع

| النوع | المعنى | مثال |
|-------|--------|------|
| **Association** | علاقة عامة | User ↔ Order |
| **Aggregation** (◇) | "has-a" ضعيف | Store ◇ Product |
| **Composition** (◆) | "has-a" قوي | Order ◆ OrderItem |

---

## 3.3.5 تطبيق مبادئ SOLID

### Single Responsibility
- `User` → بيانات المستخدم
- `Order` → إدارة الطلب
- `OrderService` → منطق إنشاء الطلبات

### Open/Closed
- إضافة طرق دفع جديدة دون تعديل `Order`

### Liskov Substitution
- `Admin`, `Merchant`, `Customer` جميعهم `User`

### Interface Segregation
- `ProductPolicy`, `OrderPolicy`, `StorePolicy` — كل policy لفئة واحدة

### Dependency Inversion
- Controllers تعتمد على **Services**، لا Models مباشرة

---

## 3.3.6 Class Diagram للـ Services

```mermaid
classDiagram
    class OrderService {
        -CouponService couponService
        +createOrder(User, array) Order
        +updateStatus(Order, string, string) Order
        +cancel(Order) bool
        -validateStock(array) void
        -calculateTotals(array) array
    }
    
    class CartService {
        +addItem(User, Product, int) CartItem
        +updateQuantity(CartItem, int) CartItem
        +removeItem(CartItem) void
        +clearCart(User) void
    }
    
    class CouponService {
        +validate(string, Store, decimal) Coupon
        +calculateDiscount(Coupon, decimal) decimal
    }
    
    OrderService --> CouponService : uses
```

---

## 3.3.7 Class Diagram للـ Notifications

```mermaid
classDiagram
    class Notification {
        <<abstract>>
        +via(object) array
        +toMail(object) MailMessage
        +toArray(object) array
    }
    
    class PasswordChangedNotification {
        +string ipAddress
        +string changedAt
        +via(object) array
        +toMail(object) MailMessage
    }
    
    class OrderPlacedNotification {
        +Order order
        +via(object) array
        +toMail(object) MailMessage
    }
    
    class StoreApprovedNotification {
        +Store store
        +via(object) array
        +toMail(object) MailMessage
    }
    
    Notification <|-- PasswordChangedNotification
    Notification <|-- OrderPlacedNotification
    Notification <|-- StoreApprovedNotification
```

---

## 3.3.8 الخلاصة

مخطط الفئات يعكس:

- ✅ **13 فئة رئيسية** (11 Model + 2 Helper)
- ✅ **3 خدمات** بفصل واضح للمسؤوليات
- ✅ **3 إشعارات** بنمط Inheritance
- ✅ **علاقات متنوعة** (Association, Aggregation, Composition)
- ✅ **تطبيق مبادئ SOLID** كاملة

**التصميم قابل للتنفيذ مباشرة** عبر Laravel Eloquent Models + Service Layer.

---

**نهاية مخطط الفئات**