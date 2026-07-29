# 🚀 Toolkit: Dynamic Form Validator

یک کتابخانه اعتبارسنجی فرم پویا، سبک و ماژولار نوشته شده با PHP خالص (نسخه 8.2+). این ابزار بدون هیچ وابستگی خارجی (No Dependency) طراحی شده و به راحتی در هر پروژه PHP قابل ادغام است.

## 📋 ویژگی‌های کلیدی

- ✅ **بدون وابستگی:** نیاز به Composer یا کتابخانه‌های جانبی ندارد.
- ✅ **ماژولار:** ساختار مبتنی بر کلاس‌ها و اینترفیس‌ها برای توسعه آسان.
- ✅ **قوانین متنوع:** شامل 15+ قانون اعتبارسنجی آماده (Required, Email, Numeric, Regex, و...).
- ✅ **پیام‌های خطای سفارشی:** پشتیبانی از پیام‌های پیش‌فرض و قابل شخصی‌سازی.
- ✅ **پاک‌سازی داده‌ها:** sanitization خودکار ورودی‌ها (مانند trim کردن رشته‌ها).
- ✅ **خروجی ساختاریافته:** دریافت نتایج به صورت آبجکت شامل وضعیت، خطاها و داده‌های پاک‌شده.

## 📂 ساختار پروژه

```text
Toolkit/
├── src/                  # کدهای اصلی کتابخانه
│   ├── Autoloader.php    # بارگذار خودکار کلاس‌ها
│   ├── Bootstrap.php     # راه‌انداز اولیه
│   └── Validator/        # هسته اصلی اعتبارسنجی
│       ├── Contracts/    # اینترفیس‌ها
│       ├── Rules/        # قوانین اعتبارسنجی
│       ├── Config/       # پیکربندی و پیام‌ها
│       ├── Helpers/      # توابع کمکی
│       ├── Result/       # کلاس نتیجه
│       ├── Exceptions/   # کلاس‌های استثنا
│       └── Validator.php # کلاس اصلی اعتبارسنج
├── docs/                 # مستندات کامل
│   ├── INSTALLATION.md   # راهنمای نصب
│   ├── USAGE.md          # راهنمای استفاده
│   └── EXTENDING.md      # راهنمای توسعه قوانین جدید
├── examples/             # مثال‌های کاربردی
│   └── validator_demo.php
└── README.md             # همین فایل
```

## 📚 مستندات کامل

برای مشاهده راهنمای نصب، نحوه استفاده، لیست کامل قوانین و آموزش ایجاد قوانین سفارشی، لطفاً به پوشه مستندات مراجعه کنید:

- 📘 [راهنمای نصب و راه‌اندازی](docs/INSTALLATION.md)
- 📗 [راهنمای استفاده و مثال‌ها](docs/USAGE.md)
- 📙 [توسعه قوانین سفارشی](docs/EXTENDING.md)
- 📕 [مستندات فنی Validator](docs/validator.md)

## ⚡ شروع سریع

```php
<?php
// بارگذاری اولیه
require_once 'src/Bootstrap.php';

use Toolkit\Validator\Validator;

$data = [
    'username' => 'ali',
    'email'    => 'ali@example.com'
];

$rules = [
    'username' => 'required|min_length:3',
    'email'    => 'required|email'
];

$validator = new Validator();
$result = $validator->validate($data, $rules);

if ($result->isValid) {
    echo "اعتبارسنجی موفق بود!";
} else {
    foreach ($result->errors as $field => $messages) {
        echo "$field: " . implode(", ", $messages);
    }
}
```

## 🧪 اجرای دمو

برای مشاهده تمام قابلیت‌ها، فایل دمو را اجرا کنید:

**در مرورگر:**
```
http://localhost/Toolkit/examples/validator_demo.php
```

**در خط فرمان (CLI):**
```bash
php examples/validator_demo.php
```

## 🤝 مشارکت

از ایده‌ها و مشارکت‌های شما استقبال می‌شود. لطفاً برای گزارش باگ یا پیشنهاد ویژگی جدید، یک Issue در گیت‌هاب ثبت کنید.

## 📄 مجوز

این پروژه تحت مجوز **MIT** منتشر شده است. استفاده از آن در پروژه‌های تجاری و شخصی آزاد است.

---
**مخزن گیت‌هاب:** [LifeBookA/ToolKit_Dynamic_Form_Validator](https://github.com/LifeBookA/ToolKit_Dynamic_Form_Validator)
