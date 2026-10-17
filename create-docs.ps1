# create-docs.ps1
# الاستخدام:  .\create-docs.ps1

Write-Host "📝 جاري إنشاء محتوى الملفات..." -ForegroundColor Cyan

# ============================================================
# README.md
# ============================================================
$readme = @'
# 👕 متجر الملابس الإلكتروني

منصة تجارة إلكترونية متكاملة لبيع الملابس، مبنية بـ **Laravel 12** و **Tailwind CSS v4** و **Alpine.js**.

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![Tailwind](https://img.shields.io/badge/Tailwind-4-cyan)
![Alpine](https://img.shields.io/badge/Alpine.js-3-purple)

---

## 📖 نظرة عامة

متجر ملابس إلكتروني احترافي يدعم **4 أدوار**:

| الدور | الصلاحيات |
|-------|-----------|
| 🛡️ **المشرف** | يدير المنصة والمستخدمين والمتاجر |
| 🏪 **التاجر** | يدير متجره ومنتجاته وطلباته |
| 👤 **الزبون** | يتسوق ويشتري ويقيّم |
| 👀 **الزائر** | يتصفح بدون تسجيل |

---

## ✨ المميزات

### 👤 للزبائن
- 🔍 تصفح المنتجات مع فلاتر متقدمة
- 🛒 سلة تسوق ديناميكية
- 💳 إتمام طلب آمن
- 🎟️ كوبونات خصم
- ❤️ قائمة المفضلة
- ⭐ تقييمات ومراجعات
- 📦 تتبع الطلبات

### 🏪 للتجار
- 📊 لوحة تحكم شاملة
- 📦 إدارة المنتجات والمخزون
- 🧾 إدارة الطلبات
- 🎟️ كوبونات وعروض
- ⚙️ إعدادات المتجر

### 🛡️ للمشرف
- 👥 إدارة المستخدمين والمتاجر
- ✅ الموافقة على المتاجر الجديدة
- 📈 إحصائيات شاملة

---

## 🛠️ التقنيات

| الطبقة | التقنية |
|--------|---------|
| **Backend** | Laravel 12 (PHP 8.1+) |
| **Database** | MySQL 8.0+ |
| **Frontend** | Tailwind CSS v4 + Alpine.js |
| **Build** | Vite 6 |
| **Auth** | Laravel Breeze |

---

## 📋 المتطلبات

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js >= 18
- npm >= 9

---

## 🚀 التنصيب

```bash
git clone https://github.com/ahmad2maher7-cyber/clothing-stores.git
cd clothing-stores
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
