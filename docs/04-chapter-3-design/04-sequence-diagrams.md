# الفصل الثالث: التصميم
## 3.4 مخططات التتابع (Sequence Diagrams)

---

## 3.4.1 المقدمة

توثّق **مخططات التتابع** (Sequence Diagrams) تسلسل الرسائل بين الكائنات/المكونات عبر الزمن، لكل عملية مهمة في النظام. تُعتبر أداة أساسية لفهم:

- **تدفق التنفيذ** (Execution Flow)
- **التفاعل** بين الطبقات (Controller → Service → Model → DB)
- **معالجة الاستثناءات** (Error Handling)
- **النقاط الحرجة** في الأداء

يغطي هذا القسم **6 عمليات رئيسية** تمثل المسارات الأكثر استخداماً في النظام.

---

## 3.4.2 SEQ-01: تسجيل الدخول

**يرتبط بـ:** FR-1.2، UC-02
**Actors:** Admin / Merchant / Customer
**الأولوية:** عالية

```mermaid
sequenceDiagram
    autonumber
    actor U as User
    participant B as Browser
    participant R as Route
    participant M as AuthMiddleware
    participant C as LoginController
    participant V as Validator
    participant DB as MySQL
    participant S as Session
    
    U->>B: email + password
    B->>R: POST /login
    R->>M: CSRF Check
    M->>C: Pass
    C->>V: validate(email, password)
    
    alt Validation OK
        V-->>C: Valid
        C->>DB: SELECT * FROM users WHERE email = ?
        DB-->>C: User | null
        
        alt User found
            C->>C: Hash::check(password, hash)
            
            alt Password correct
                C->>S: Create session
                C->>S: Regenerate ID (prevent fixation)
                C->>C: check role
                
                alt role = admin
                    C-->>B: 302 → /admin/dashboard
                else role = merchant
                    C-->>B: 302 → /merchant/dashboard
                else role = customer
                    C-->>B: 302 → /
                end
                B-->>U: Dashboard
            else Password wrong
                C->>C: Rate limit increment
                C-->>B: 302 → /login + error
                B-->>U: "بيانات الدخول غير صحيحة"
            end
        else User not found
            C-->>B: 302 → /login + error (generic)
            B-->>U: "بيانات الدخول غير صحيحة"
        end
    else Validation Failed
        V-->>C: Errors
        C-->>B: 302 → /login + errors
        B-->>U: Show validation errors
    end
```

**نقاط حرجة:**
- 🔐 **Session Regeneration** بعد الدخول (منع Session Fixation)
- 🛡️ **رسالة خطأ عامة** (لا نكشف إن البريد موجود أم لا)
- ⏱️ **Rate Limiting**: 5 محاولات/دقيقة

---

## 3.4.3 SEQ-02: تغيير كلمة المرور (خطأ 500 السابق)

**يرتبط بـ:** FR-1.4، UC-03
**Actors:** أي مستخدم مسجل
**الأولوية:** عالية
**ملاحظة:** هذا هو السيناريو الذي واجه فيه الفريق **خطأ 500** وتم حله.

```mermaid
sequenceDiagram
    autonumber
    actor U as User
    participant B as Browser
    participant C as PasswordController
    participant V as Validator
    participant DB as MySQL
    participant N as Notification
    participant M as Brevo SMTP
    participant L as Log
    
    U->>B: current + new + confirm
    B->>C: PUT /profile/password
    C->>V: validate fields
    V-->>C: OK
    C->>DB: SELECT * FROM users WHERE id = ?
    DB-->>C: User
    C->>C: Hash::check(current, hash)
    
    alt Current password correct
        C->>C: Hash::make(new_password)
        C->>DB: UPDATE users SET password = ?
        DB-->>C: OK
        
        Note over C,M: 🔧 محاولة الإرسال مع try/catch
        C->>N: try { notify(PasswordChangedNotification) }
        N->>M: send email
        
        alt Email sent
            M-->>N: 200 OK
            N-->>C: Success
        else Email failed
            M-->>N: Error
            N->>L: Log::error("Email failed")
            Note over N,L: لا نوقف العملية!
        end
        
        C-->>B: 302 → /profile + success
        B-->>U: "تم التغيير بنجاح" ✅
    else Current password wrong
        C-->>B: 302 → /profile + error
        B-->>U: "كلمة المرور الحالية خاطئة"
    end
```

### 3.4.3.1 تحليل المشكلة والحل

| المرحلة | الوضع |
|---------|-------|
| **قبل الحل** | `PasswordChangedNotification implements ShouldQueue` → timeout → **500** |
| **السبب** | Render Free لا يدعم Queue Worker مستقر |
| **الحل** | إزالة `ShouldQueue` + `try/catch` + Log |
| **النتيجة** | ✅ لا خطأ 500، ✅ إيميل يصل، ✅ عملية التغيير تنجح دائماً |

---

## 3.4.4 SEQ-03: إضافة منتج

**يرتبط بـ:** FR-4.1، UC-06
**Actors:** Merchant
**الأولوية:** عالية

```mermaid
sequenceDiagram
    autonumber
    actor M as Merchant
    participant B as Browser
    participant R as Route
    participant MW as RoleMiddleware
    participant P as ProductPolicy
    participant C as ProductController
    participant V as FormRequest
    participant FS as Storage
    participant DB as MySQL
    
    M->>B: Fill form + image
    B->>R: POST /merchant/products
    R->>MW: check role = merchant
    MW->>P: authorize(create, Product)
    P-->>MW: OK
    
    MW->>C: Pass
    C->>V: validate (name, price, stock, image)
    V-->>C: Valid data
    
    C->>C: verify store active
    C->>FS: store(image, 'products/')
    FS-->>C: image_path
    
    C->>DB: INSERT INTO products (...)
    DB-->>C: product_id
    
    C-->>B: 302 → /merchant/products
    B-->>M: "تمت الإضافة بنجاح" ✅
```

**نقاط حرجة:**
- 🔐 **Policy check**: التاجر لا يمكنه إضافة منتج لمتجر آخر
- 📁 **Image Storage**: `storage/app/public/products/`
- ✅ **Validation**: name + price > 0 + stock >= 0 + image ≤ 2MB

---

## 3.4.5 SEQ-04: إتمام الطلب (Checkout)

**يرتبط بـ:** FR-6.1، UC-09
**Actors:** Customer
**الأولوية:** **حرجة (Critical)**

```mermaid
sequenceDiagram
    autonumber
    actor C as Customer
    participant B as Browser
    participant O as OrderController
    participant S as OrderService
    participant CS as CouponService
    participant DB as MySQL
    participant N as Notification
    participant M as Merchant
    participant EM as Brevo
    
    C->>B: Checkout
    B->>O: POST /orders
    O->>O: validate (address, phone)
    O->>S: createOrder(user, data)
    
    S->>DB: BEGIN TRANSACTION
    S->>DB: SELECT products FOR UPDATE
    Note over S,DB: 🔒 Lock rows (منع race condition)
    DB-->>S: Products + stock
    
    S->>S: validateStock(products)
    
    alt Stock sufficient
        S->>CS: validate(coupon_code) [optional]
        CS-->>S: Discount
        
        S->>S: calculateTotals()
        S->>DB: INSERT INTO orders
        S->>DB: INSERT INTO order_items (snapshot)
        S->>DB: UPDATE products SET stock = stock - qty
        S->>DB: INSERT INTO order_status_history
        S->>DB: DELETE FROM cart_items
        S->>DB: COMMIT
        Note over S,DB: ✅ Transaction successful
        
        S->>N: notify(merchant, OrderPlaced)
        N->>EM: send email
        EM-->>N: 200 OK
        
        O-->>B: 302 → /orders/{id}
        B-->>C: "شكراً! طلبك #123" ✅
    else Stock insufficient
        S->>DB: ROLLBACK
        S-->>O: InsufficientStockException
        O-->>B: 422 + errors
        B-->>C: "المنتج X غير متوفر"
    end
```

**نقاط حرجة:**

| النقطة | الأهمية |
|--------|---------|
| `BEGIN TRANSACTION` | ضمان الذرية (Atomicity) |
| `SELECT FOR UPDATE` | منع Race Condition (منتج واحد لزبونين) |
| `INSERT order_items` | Snapshot لسعر الوحدة |
| `DELETE cart_items` | تفريغ السلة بعد النجاح |
| `COMMIT` | تثبيت كل التغييرات دفعة واحدة |
| `try/catch` للإيميل | لا نوقف الطلب لو البريد فشل |

---

## 3.4.6 SEQ-05: الموافقة على متجر

**يرتبط بـ:** FR-3.2، UC-14
**Actors:** Admin
**الأولوية:** عالية

```mermaid
sequenceDiagram
    autonumber
    actor A as Admin
    participant B as Browser
    participant C as AdminStoreController
    participant DB as MySQL
    participant N as Notification
    participant M as Merchant
    participant EM as Brevo
    
    A->>B: GET /admin/stores/pending
    B->>C: Request
    C->>DB: SELECT * FROM stores WHERE status = 'pending'
    DB-->>C: Stores[]
    C-->>B: List of pending stores
    B-->>A: Display
    
    A->>B: Click "Approve" store #5
    B->>C: PUT /admin/stores/5/approve
    C->>C: authorize(approve, Store)
    C->>DB: UPDATE stores SET status = 'active', approved_at = NOW()
    DB-->>C: OK
    
    C->>N: notify(store.owner, StoreApproved)
    N->>EM: send email
    EM-->>N: 200 OK
    
    C-->>B: 302 → /admin/stores
    B-->>A: "تمت الموافقة" ✅
    Note over M,EM: 🏪 التاجر يستلم بريد الموافقة
```

**الاستثناءات:**

```mermaid
sequenceDiagram
    actor A as Admin
    participant C as AdminStoreController
    participant DB as MySQL
    participant N as Notification
    
    A->>C: Reject store #5
    C->>C: require rejection_reason
    C->>DB: UPDATE stores SET status = 'suspended'
    C->>N: notify(reason)
    N-->>A: Confirmation
```

---

## 3.4.7 SEQ-06: تطبيق كوبون الخصم

**يرتبط بـ:** FR-7.4-7.5
**Actors:** Customer
**الأولوية:** متوسطة

```mermaid
sequenceDiagram
    autonumber
    actor C as Customer
    participant B as Browser
    participant CC as CouponController
    participant CS as CouponService
    participant DB as MySQL
    
    C->>B: Enter coupon code
    B->>CC: POST /coupons/validate
    CC->>CS: validate(code, store, subtotal)
    CS->>DB: SELECT * FROM coupons WHERE code = ?
    DB-->>CS: Coupon | null
    
    alt Coupon exists
        CS->>CS: check is_active
        
        alt is_active = true
            CS->>CS: check expires_at > NOW()
            
            alt not expired
                CS->>CS: check used_count < max_uses
                
                alt uses remaining
                    CS->>CS: check subtotal >= min_order
                    
                    alt min_order met
                        CS->>CS: calculateDiscount(subtotal)
                        CS-->>CC: { discount, new_total }
                        CC-->>B: 200 OK
                        B-->>C: "الخصم: 15₪" ✅
                    else
                        CS-->>CC: InvalidCouponException("الحد الأدنى X")
                        CC-->>B: 422
                        B-->>C: Error message
                    end
                else
                    CS-->>CC: "بلغ الحد الأقصى"
                    CC-->>B: 422
                end
            else
                CS-->>CC: "انتهت الصلاحية"
                CC-->>B: 422
            end
        else
            CS-->>CC: "كوبون غير مفعّل"
            CC-->>B: 422
        end
    else Coupon not found
        CS-->>CC: 404
        CC-->>B: 404
        B-->>C: "كوبون غير صالح"
    end
```

**نقاط حرجة:**

| القيد | السبب |
|-------|-------|
| `is_active` | يمكن للتاجر تعطيل الكوبون |
| `expires_at > NOW()` | منع الاستخدام بعد الانتهاء |
| `used_count < max_uses` | حد الاستخدامات |
| `subtotal >= min_order` | حد أدنى للطلب |

---

## 3.4.8 SEQ-07: تقييم منتج (Bonus)

**يرتبط بـ:** FR-8.1-8.5، UC-11
**Actors:** Customer

```mermaid
sequenceDiagram
    autonumber
    actor C as Customer
    participant R as ReviewController
    participant RP as ReviewPolicy
    participant DB as MySQL
    participant P as Product
    
    C->>R: POST /products/5/reviews (rating=5)
    R->>RP: canReview(user, product)
    RP->>DB: SELECT COUNT(*) FROM order_items<br>JOIN orders<br>WHERE user_id=? AND product_id=?<br>AND orders.status='delivered'
    DB-->>RP: count
    
    alt count > 0
        RP-->>R: Authorized
        R->>DB: check user hasn't reviewed before
        DB-->>R: 0 reviews
        
        R->>DB: INSERT INTO reviews
        R->>P: recalculateAvgRating()
        P->>DB: UPDATE products SET avg_rating = ?
        R-->>C: "تم النشر" ✅
    else count = 0
        RP-->>R: 403 Forbidden
        R-->>C: "يمكن التقييم بعد الشراء فقط"
    end
```

---

## 3.4.9 ملخص المخططات

| # | Sequence Diagram | Actor | المرحلة | الأولوية |
|---|------------------|-------|---------|----------|
| **SEQ-01** | تسجيل الدخول | All | Auth | عالية |
| **SEQ-02** | تغيير كلمة المرور | User | Auth | عالية |
| **SEQ-03** | إضافة منتج | Merchant | CRUD | عالية |
| **SEQ-04** | Checkout | Customer | Transaction | **حرجة** |
| **SEQ-05** | الموافقة على متجر | Admin | Admin | عالية |
| **SEQ-06** | تطبيق كوبون | Customer | Discount | متوسطة |
| **SEQ-07** | تقييم منتج | Customer | Review | متوسطة |

---

## 3.4.10 الدروس المستفادة

### 3.4.10.1 نقاط التصميم الحرجة

| النقطة | الدرس |
|--------|-------|
| **Transactions** | Checkout يجب أن يكون `transactional` |
| **Row Locking** | `SELECT FOR UPDATE` يمنع Race Condition |
| **Snapshots** | `order_items.unit_price` ضروري للتاريخ |
| **try/catch للإشعارات** | الإشعار لا يجب أن يُعطّل العملية |
| **Policy + Middleware** | التحقق من الصلاحيات على مستويين |
| **Session Regeneration** | منع Session Fixation بعد الدخول |

### 3.4.10.2 مشكلة خطأ 500 — قصة نجاح

> **القصة الكاملة:** عند اختبار تغيير كلمة المرور، استخدم الفريق `PasswordChangedNotification implements ShouldQueue`. على Render Free، الـ Queue Worker غير مستقر، مما أدى إلى **timeout** ثم **خطأ 500**.
>
> **التشخيص:**
> 1. فحص Logs → ظهور `PasswordChangedNotification` error
> 2. اكتشاف `QUEUE_CONNECTION=database` مع عدم وجود Worker
>
> **الحل:**
> 1. إزالة `implements ShouldQueue` + `Queueable`
> 2. الإرسال **متزامن (sync)** داخل `try/catch`
> 3. تسجيل الفشل في `Log` (لا يُعطّل العملية)
>
> **النتيجة:** ✅ 200 OK، ✅ إيميل يصل، ✅ لا timeout

---

## 3.4.11 الخلاصة

مخططات التتابع السبعة توثّق **المسارات الحرجة** في النظام، مع التركيز على:

- ✅ **Transactions** في العمليات الحساسة (Checkout)
- ✅ **Error Handling** (try/catch + Log)
- ✅ **Security** (Policy + Middleware + Session)
- ✅ **Data Integrity** (Snapshots, Row Locking)

هذه المخططات **مرجع مباشر للتنفيذ** والتوثيق الأكاديمي.

---

**نهاية مخططات التتابع**