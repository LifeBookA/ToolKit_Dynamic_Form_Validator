# 🚀 Dynamic Form Validator Toolkit

یک کتابخانه اعتبارسنجی فرم پویا، سبک و ماژولار نوشته شده با **PHP خالص (8.2+)** بدون هیچ وابستگی خارجی. این ابزار برای ادغام آسان در هر پروژه PHP طراحی شده است.

## 📋 فهرست مطالب

- [ویژگی‌ها](#ویژگی‌ها)
- [نصب و راه‌اندازی](#نصب-و-راه‌اندازی)
- [شروع سریع](#شروع-سریع)
- [قوانین اعتبارسنجی](#قوانین-اعتبارسنجی)
- [مستندات تکمیلی](#مستندات-تکمیلی)
- [مجوز](#مجوز)

---

## ✨ ویژگی‌ها

- ✅ **بدون وابستگی:** فقط PHP خالص، بدون نیاز به Composer (اختیاری).
- ✅ **ماژولار:** ساختار مبتنی بر Interface برای توسعه آسان.
- ✅ **پویا:** پشتیبانی از زنجیره‌ای کردن قوانین با پارامتر (`rule:param`).
- ✅ **پیام‌های خطا:** قابل شخصی‌سازی و چندزبانه.
- ✅ **پاک‌سازی داده‌ها:** حذف خودکار فاصله‌های اضافی (Trim).
- ✅ **خروجی ساختاریافته:** دریافت خطاها و داده‌های پاک‌شده به صورت آرایه.
- ✅ **امن:** استفاده از `strict_types=1` در تمام فایل‌ها.

---

## 📦 نصب و راه‌اندازی

### روش ۱: کپی دستی (پیشنهادی برای شروع)
1. کل پوشه `src` را به پروژه خود کپی کنید.
2. فایل `src/Bootstrap.php` را در نقطه ورودی پروژه خود فراخوانی کنید.

```php
<?php
require_once 'src/Bootstrap.php';
```

### روش ۲: استفاده از Composer
اگر از Composer استفاده می‌کنید، می‌توانید اتولودر را در `composer.json` تنظیم کنید:

```json
{
    "autoload": {
        "psr-4": {
            "Toolkit\\": "src/"
        }
    }
}
```
سپس دستور `composer dump-autoload` را اجرا کنید.

---

## ⚡ شروع سریع

```php
<?php
require_once 'src/Bootstrap.php';

use Toolkit\Validator\Validator;

$data = [
    'username' => '  ali_reza  ',
    'email'    => 'ali@example.com',
    'age'      => '25'
];

$rules = [
    'username' => 'required|min_length:3|max_length:20|alpha_num',
    'email'    => 'required|email',
    'age'      => 'numeric|between:18,99'
];

$validator = new Validator();
$result = $validator->validate($data, $rules);

if ($result->isValid) {
    echo "✅ اعتبارسنجی موفق بود!\n";
    print_r($result->cleanedData); // داده‌های پاک‌شده
} else {
    echo "❌ خطاهای اعتبارسنجی:\n";
    foreach ($result->errors as $field => $messages) {
        foreach ($messages as $msg) {
            echo "- $field: $msg\n";
        }
    }
}
```

---

## 🛡️ قوانین اعتبارسنجی موجود

| نام قانون | توضیح | مثال |
| :--- | :--- | :--- |
| `required` | فیلد باید وجود داشته و خالی نباشد | `required` |
| `email` | باید فرمت ایمیل معتبر باشد | `email` |
| `min_length` | حداقل تعداد کاراکتر | `min_length:5` |
| `max_length` | حداکثر تعداد کاراکتر | `max_length:100` |
| `exact_length` | طول دقیق رشته | `exact_length:11` |
| `numeric` | باید عدد باشد (اعشار مجاز) | `numeric` |
| `integer` | باید عدد صحیح باشد | `integer` |
| `alpha` | فقط حروف الفبا | `alpha` |
| `alpha_num` | فقط حروف و اعداد | `alpha_num` |
| `match` | باید با فیلد دیگر برابر باشد | `match:password_confirm` |
| `in_array` | باید یکی از مقادیر لیست باشد | `in_array:admin,user,guest` |
| `regex` | بررسی با عبارت باقاعده | `regex:/^[A-Z]+$/` |
| `url` | باید لینک معتبر باشد | `url` |
| `ip` | باید آی‌پی معتبر باشد | `ip` |
| `date` | باید تاریخ معتبر باشد | `date:Y-m-d` |
| `between` | مقدار بین دو عدد | `between:10,20` |
| `unique` | یکتا بودن (در فایل یا کالبک) | `unique:users.json` |

---

## 📚 مستندات تکمیلی

برای اطلاعات بیشتر به فایل‌های زیر مراجعه کنید:

- **[راهنمای نصب پیشرفته](docs/INSTALLATION.md)**: جزئیات پیکربندی و عیب‌یابی.
- **[راهنمای استفاده کامل](docs/USAGE.md)**: توضیح تمام متدها، پیام‌های سفارشی و نکات امنیتی.
- **[توسعه قوانین سفارشی](docs/EXTENDING.md)**: آموزش گام‌به‌گام ساخت Rule جدید.

---

## 🧪 اجرای دمو

برای مشاهده نمونه‌های کامل اجرا، فایل زیر را اجرا کنید:
- **مرورگر:** `http://localhost/Toolkit/examples/validator_demo.php`
- **ترمینال:** `php examples/validator_demo.php`

---

## 📄 مجوز

این پروژه تحت مجوز **MIT** منتشر شده است. استفاده از آن در پروژه‌های تجاری و شخصی آزاد است.
