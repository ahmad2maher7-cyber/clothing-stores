<div align="center">

جامعـــة الأزهـــر – غـــزة
Al Azhar University - Gaza

![شعار الجامعة](../images/university-logo.jpeg)

<br><br>

**FACULTY OF ENGINEERING AND INFORMATION TECHNOLOGY**
**DEPARTMENT OF INFORMATION TECHNOLOGY**

<br><br>

**FINAL REPORT [ FYP II ]**

<br>

**متجر الملابس الإلكتروني (Multi-Vendor Clothing E-Commerce Platform)**

<br><br>

**FIRST STUDENT NAME:** أحمد ماهر الطلاع (20224159)
**SECOND STUDENT NAME:** محمد رأفت شاهين (20221331)
**THIRD STUDENT NAME:** مرام أيمن المطري (20221026)
**FOURTH STUDENT NAME:** نور مصطفى عناية (20220268)

<br><br>

**SUPERVISED BY**
**Dr. Khaled Ismail**

<br><br>

**OCTOBER 2026**
**SEMESTER I 2026/2027**

</div>

<div style="page-break-after: always;"></div>

<div align="center">
<h2>ABSTRACT</h2>
</div>

يعرض هذا الجزء الثاني من المشروع مرحلة **التنفيذ (Implementation)** والاختبار (Testing) لمنصة التجارة الإلكترونية متعددة البائعين. تم بناء النظام باستخدام Laravel 12 وMySQL 8، مع واجهات مستخدم تفاعلية تعتمد على Tailwind CSS وAlpine.js. يوثق هذا الجزء هيكل المشروع، وأهم الأكواد البرمجية (مثل خدمة الطلبات ونظام الإشعارات)، وتفاصيل النشر على منصة Render السحابية.

كما يقدم الجزء الثاني استراتيجية اختبار شاملة تغطي 25 حالة اختبار، واختبارات الأمان (OWASP Top 10)، واختبارات الأداء. تم توثيق 8 أخطاء برمجية تم اكتشافها وإصلاحها أثناء التطوير (بما فيها خطأ 500 عند تغيير كلمة المرور، ومشاكل Race Condition). وأخيراً، يعرض الفصل السادس الخاتمة، التحديات التي واجهت الفريق، وخطة العمل المستقبلية (مثل إضافة الدفع الإلكتروني وتطبيق الموبايل).

<div style="page-break-after: always;"></div>

<div align="center">
<h2>TABLE OF CONTENTS</h2>
</div>

| CHAPTER | TITLE | PAGE |
| :--- | :--- | :--- |
| | **ABSTRACT** | i |
| | **TABLE OF CONTENTS** | ii |
| | **LIST OF TABLES** | iv |
| | **LIST OF FIGURES** | v |
| **4** | **SYSTEM IMPLEMENTATION** | **1** |
| 4.1 | Technology Stack | 1 |
| 4.2 | Development Environment | 2 |
| 4.3 | Project Structure | 3 |
| 4.4 | Main Implementations | 5 |
| 4.5 | Deployment | 10 |
| **5** | **SYSTEM TESTING** | **12** |
| 5.1 | Testing Strategy | 12 |
| 5.2 | Test Cases | 14 |
| 5.3 | Security Testing | 18 |
| 5.4 | Performance Testing | 20 |
| 5.5 | Bug Tracking | 21 |
| **6** | **CONCLUSION AND FUTURE WORK** | **23** |
| 6.1 | Conclusion | 23 |
| 6.2 | Challenges | 24 |
| 6.3 | Future Work | 25 |
| | **APPENDICES** | **26** |

<div style="page-break-after: always;"></div>

<div align="center">
<h2>LIST OF TABLES</h2>
</div>

| TABLE NO. | TITLE | PAGE |
| :--- | :--- | :--- |
| 4.1 | Technology Stack | 1 |
| 4.2 | Environment Variables | 3 |
| 4.3 | Technical Challenges | 9 |
| 5.1 | Test Cases Summary | 14 |
| 5.2 | Bug Tracking | 21 |

<div style="page-break-after: always;"></div>

<div align="center">
<h2>LIST OF FIGURES</h2>
</div>

| FIGURE NO. | TITLE | PAGE |
| :--- | :--- | :--- |
| 4.1 | Project Structure | 4 |
| 4.2 | Deployment Architecture | 11 |
| 5.1 | Testing Pyramid | 12 |

<div style="page-break-after: always;"></div>

# CHAPTER 4: SYSTEM IMPLEMENTATION

## 4.1 Technology Stack

تم اختيار التقنيات التالية لضمان الأداء العالي وسهولة الصيانة:

| الفئة | التقنية | الغرض |
| :--- | :--- | :--- |
| **Backend** | Laravel 12 (PHP 8.2) | إدارة منطق الأعمال (MVC) |
| **Database** | MySQL 8.0 | تخزين البيانات العلائقية |
| **Frontend CSS** | Tailwind CSS v4 | التصميم المتجاوب (Responsive) |
| **Frontend JS** | Alpine.js | التفاعلات الديناميكية (مثل السلة) |
| **Build Tool** | Vite 6 | تجميع الأصول (Bundling) |
| **Authentication** | Laravel Breeze | نظام تسجيل الدخول والصلاحيات |
| **Email** | Brevo SMTP | إرسال الإشعارات البريدية |
| **Hosting** | Render (Docker) | النشر السحابي |

**مبررات الاختيار:** يوفر Laravel بيئة متكاملة (MVC) وحماية مدمجة ضد الثغرات (CSRF, SQL Injection)، بينما توفر Tailwind وAlpine.js واجهة مستخدم سريعة وخفيفة دون تعقيد React SPA.

## 4.2 Development Environment

تم تطوير النظام محلياً باستخدام بيئة **XAMPP** (Apache + MySQL)، ومحرر الأكواد **VS Code**، وإدارة الإصدارات عبر **Git/GitHub**. 

أهم متغيرات البيئة (`.env`):
```env
APP_ENV=production
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
QUEUE_CONNECTION=sync   # مهم جداً لتجنب أخطاء Queue Worker
SESSION_DRIVER=database


