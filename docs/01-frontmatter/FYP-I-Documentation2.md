# CHAPTER 5: SYSTEM TESTING

## 5.1 Testing Strategy

تم اتباع استراتيجية اختبار شاملة لضمان جودة النظام، وشملت المستويات التالية:
1. **Unit Testing:** لاختبار المكونات الفردية (مثل Services و Models) باستخدام PHPUnit.
2. **Integration Testing:** لاختبار تفاعل المكونات معاً (مثل اختبار الـ APIs والتحقق من الصلاحيات).
3. **End-to-End (Manual) Testing:** لاختبار سيناريوهات المستخدم الكاملة (تسجيل، شراء، إدارة).

## 5.2 Test Cases Summary

تم تنفيذ أكثر من 25 حالة اختبار تغطي جميع الوظائف. فيما يلي ملخص لأهم الحالات:

| ID | Test Case | Expected Result | Status |
| :--- | :--- | :--- | :--- |
| TC-01 | تسجيل حساب جديد | إنشاء الحساب وتوجيهه للرئيسية | ✅ Pass |
| TC-05 | تغيير كلمة المرور | 200 OK + إرسال إيميل | ✅ Pass |
| TC-13 | إتمام الطلب (Checkout) | خصم المخزون وإنشاء الطلب | ✅ Pass |
| TC-14 | Checkout بمخزون صفر | رفض الطلب (422 Error) | ✅ Pass |
| TC-20 | SQL Injection في البحث | عدم ظهور أي نتائج (محمي) | ✅ Pass |
| TC-25 | دخول صفحة الإدارة كزبون | منع الوصول (403 Forbidden) | ✅ Pass |

## 5.3 Security Testing

تم اختبار النظام ضد ثغرات **OWASP Top 10**، وكانت النتائج كالتالي:
*   **SQL Injection:** محمي بالكامل (باستخدام Eloquent ORM و Prepared Statements).
*   **XSS (Cross-Site Scripting):** محمي (باستخدام Blade Escaping).
*   **CSRF:** محمي (باستخدام Laravel CSRF Tokens في جميع النماذج).
*   **Broken Access Control:** محمي (باستخدام Middleware و Policies).

## 5.4 Performance Testing

تم قياس أداء النظام باستخدام أدوات المتصفح و Laravel Debugbar:
*   **متوسط زمن استجابة الصفحة:** 513ms (أقل من الهدف < 2s).
*   **عدد الاستعلامات في الصفحة الرئيسية:** 4 استعلامات (بفضل Eager Loading).
*   **استهلاك الذاكرة:** أقل من 35MB (في حدود إمكانيات Render Free).

## 5.5 Bug Tracking

خلال مراحل التطوير والاختبار، تم اكتشاف 8 أخطاء (Bugs) وتم إصلاحها بالكامل. أبرزها:

| # | Bug | Root Cause | Solution |
| :--- | :--- | :--- | :--- |
| 1 | **500 Error** عند تغيير كلمة المرور | استخدام `ShouldQueue` بدون Queue Worker | إزالة Queue واستخدام `sync` + `try/catch` |
| 2 | Race Condition في المخزون | عدم قفل الصفوف عند التحقق | استخدام `DB::transaction` و `lockForUpdate` |
| 3 | N+1 Queries | عدم استخدام Eager Loading | إضافة `with('store', 'category')` |
| 4 | الصور لا تظهر بعد الرفع | عدم وجود symlink | تشغيل `php artisan storage:link` |

<div style="page-break-after: always;"></div>

# CHAPTER 6: CONCLUSION AND FUTURE WORK

## 6.1 Conclusion

تم بناء منصة تجارة إلكترونية متعددة البائعين بنجاح، تلبي جميع المتطلبات الوظيفية وغير الوظيفية. النظام يوفر تجربة سلسة للتجار (لإدارة متاجرهم)، والزبائن (للتسوق والشراء)، والمشرفين (للإشراف والرقابة). 

تميز المشروع بتطبيق مبادئ **هندسة البرمجيات** (MVC, SOLID) وتوثيق كامل (SRS, UML, ERD). كما أثبت النظام كفاءته في اختبارات الأمان والأداء، مما يجعله جاهزاً للاستخدام الفعلي كمنصة عربية تدعم اللغة بشكل كامل.

## 6.2 Challenges

واجه الفريق عدة تحديات تقنية، كان أبرزها:
1. **خطأ 500 في الإشعارات:** بسبب عدم توافق بيئة الاستضافة (Render Free) مع `Queue Workers`. تم حله بإرسال الإشعارات بشكل متزامن داخل `try/catch`.
2. **مشكلة Race Condition:** عند شراء آخر قطعة من منتج ما. تم حله باستخدام **Database Transactions** و **Row Locking**.
3. **قيود الذاكرة (RAM):** في خطة الاستضافة المجانية (512MB). تم تجاوزه باستخدام `Pagination` و `Eager Loading`.

## 6.3 Future Work

لضمان استمرارية المشروع وتوسعه، تم اقتراح خارطة طريق مستقبلية تشمل:
1. **الدفع الإلكتروني:** دمج بوابات الدفع (Stripe أو PayPal).
2. **تطبيق موبايل:** بناء تطبيق (iOS/Android) باستخدام React Native.
3. **الإشعارات الفورية:** استخدام WebSockets (Laravel Reverb أو Pusher) بدلاً من التحديث التلقائي.
4. **نظام شحن متكامل:** ربط المنصة بشركات التوصيل لتتبع الشحنات آلياً.

<div style="page-break-after: always;"></div>

# APPENDICES

**Appendix A - Project Gantt Chart**
*(قم بإدراج صورة مخطط جانت (Gantt Chart) هنا من الفصل الأول).*

**Appendix B - Source Codes**
تم رفع الكود المصدري بالكامل على GitHub:
`https://github.com/ahmad2maher7-cyber/clothing-stores`

**Appendix C - System Interfaces**
*(قم بإدراج لقطات شاشة (Screenshots) من النظام هنا)*
*   *صورة 1: الصفحة الرئيسية للمتجر.*
*   *صورة 2: لوحة تحكم التاجر (Merchant Dashboard).*
*   *صورة 3: لوحة الإدارة (Admin Dashboard).*
*   *صورة 4: صفحة إتمام الطلب (Checkout).*

<br><br><br>

<div align="center">
<h2>نهاية الجزء الثاني (FYP II)</h2>
</div>