### 2.2.2 Use Case Details

**UC-04: Checkout (إتمام الطلب)**

*   **Actor:** Customer
*   **Preconditions:** السلة غير فارغة.
*   **Main Flow:**
    1. الزبون يدخل عنوان الشحن.
    2. النظام يتحقق من المخزون.
    3. النظام ينشئ الطلب ويخصم من المخزون.
    4. النظام يرسل إشعاراً للتاجر.
*   **Exceptions:** إذا نفد المنتج، يظهر خطأ ويتم التراجع (Rollback).

---

## 2.3 Feasibility Study

*   **Technical:** المشروع قابل للتنفيذ باستخدام Laravel 12 + MySQL (بيئة مجانية ومستقرة).
*   **Economic:** التكاليف شبه معدومة (Open Source). الاستضافة على Render مجانية.
*   **Operational:** الواجهة مألوفة وتدعم العربية، مما يسهل على التجار والزبائن استخدامها.
*   **Schedule:** المدة الإجمالية 12 أسبوعاً، وهي كافية جداً لتنفيذ المراحل.

<div style="page-break-after: always;"></div>

# CHAPTER 3: SYSTEM DESIGN

## 3.1 System Architecture

يتبع النظام نمط **MVC + Service Layer**، مما يفصل بين واجهة المستخدم ومنطق الأعمال وقاعدة البيانات.

```mermaid
graph TB
    B[Browser] --> R[Laravel Routes]
    R --> C[Controllers]
    C --> S[Services]
    S --> M[Models]
    M --> DB[(MySQL)]