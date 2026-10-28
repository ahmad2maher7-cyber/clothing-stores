## 3.2 Database Design (ERD)

يتكون النظام من 13 جدولاً رئيسياً. العلاقات الأساسية كالتالي:

*   `User` (1) إلى (0..1) `Store`.
*   `Store` (1) إلى (*) `Product`.
*   `Order` (1) إلى (*) `OrderItem`.
*   `Product` (1) إلى (*) `Review`.

**(يُرجى إدراج صورة مخطط ERD هنا من الملف السابق)**

### Data Dictionary Example (Table: users)

| Column | Type | Constraint |
| :--- | :--- | :--- |
| id | BIGINT | PK, AUTO |
| full_name | VARCHAR(255) | NOT NULL |
| email | VARCHAR(255) | UNIQUE |
| role | ENUM | admin/merchant/customer |

---

## 3.3 Class Diagram

```mermaid
classDiagram
    class User {
        +String full_name
        +String email
        +login()
    }
    class Order {
        +String order_number
        +Decimal total
        +checkout()
    }
    User "1" --> "*" Order : places