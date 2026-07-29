# 📘 راهنمای استفاده کامل (Usage Guide)

این مستندات به طور کامل نحوه استفاده از کلاس `Validator`، تعریف قوانین، شخصی‌سازی پیام‌ها و کار با خروجی‌ها را توضیح می‌دهد.

---

## ۱. شروع سریع

```php
<?php
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
    // داده‌ها معتبر هستند
} else {
    // خطاها را پردازش کنید
}
```

---

## ۲. تعریف قوانین (Rules)

قوانین به صورت رشته‌ای و با جداکننده `|` زنجیره‌ای می‌شوند. برخی قوانین پارامتر می‌پذیرند که با `:` مشخص می‌شوند.

### فرمت کلی
```php
'field_name' => 'rule1|rule2:param1,param2|rule3'
```

### مثال‌های کاربردی

| سناریو | تعریف قانون |
| :--- | :--- |
| **ثبت‌نام ساده** | `'username' => 'required|alpha_num|min_length:4'` |
| **رمز عبور امن** | `'password' => 'required|min_length:8|regex:/[A-Z]/'` |
| **تطبیق فیلدها** | `'pass_conf' => 'required|match:password'` |
| **محدوده عددی** | `'age' => 'numeric|between:18,65'` |
| **لیست مجاز** | `'role' => 'required|in_array:admin,user,editor'` |

---

## ۳. شخصی‌سازی پیام‌های خطا

به صورت پیش‌فرض، پیام‌ها به زبان انگلیسی هستند. شما می‌توانید پیام‌های سفارشی برای هر فیلد و قانون تعریف کنید.

### ساختار آرایه پیام‌ها
کلید آرایه باید به صورت `field.rule` باشد.

```php
$customMessages = [
    'username.required' => 'لطفاً نام کاربری را وارد کنید.',
    'username.min_length' => 'نام کاربری باید حداقل {param} کاراکتر باشد.',
    'email.email' => 'آدرس ایمیل وارد شده معتبر نیست.',
    'password.match' => 'رمز عبور و تکرار آن مطابقت ندارند.'
];

$result = $validator->validate($data, $rules, $customMessages);
```

> **نکته:** در پیام‌های سفارشی می‌توانید از `{field}` (نام فیلد) و `{param}` (مقدار پارامتر قانون) استفاده کنید. کتابخانه به صورت خودکار این مقادیر را جایگزین می‌کند.

---

## ۴. کار با نتیجه اعتبارسنجی (`ValidationResult`)

متد `validate()` یک شیء از کلاس `ValidationResult` برمی‌گرداند که اطلاعات زیر را دارد:

### خواص اصلی
- `bool $isValid`: وضعیت کلی اعتبارسنجی.
- `array $errors`: آرایه‌ای از خطاها به تفکیک فیلد.
- `array $cleanedData`: داده‌های ورودی پاک‌سازی شده (مثلاً Trim شده).

### متدهای کمکی

#### الف) دریافت اولین خطا
مناسب برای نمایش تک‌خطی خطا در بالای فرم.
```php
$firstError = $result->getFirstError();
// خروجی: "The email field must be a valid email address."
```

#### ب) بررسی خطای خاص
بررسی اینکه آیا فیلد خاصی خطا دارد یا خیر.
```php
if ($result->hasError('email')) {
    echo "ایمیل اشتباه است.";
}
```

#### ج) دریافت تمام خطاهای یک فیلد
```php
$emailErrors = $result->getErrorsForField('email');
// خروجی: ["The email field must be a valid email address."]
```

#### د) تبدیل به آرایه
تبدیل کل نتیجه به آرایه انجمنی (مناسب برای بازگشت JSON در API).
```php
$output = $result->toArray();
/*
[
    'isValid' => false,
    'errors' => [...],
    'cleanedData' => [...]
]
*/
```

---

## ۵. استفاده در API (خروجی JSON)

اگر در حال توسعه یک API هستید، می‌توانید نتیجه را مستقیماً به JSON تبدیل کنید:

```php
header('Content-Type: application/json');

$result = $validator->validate($data, $rules);

echo json_encode([
    'success' => $result->isValid,
    'data' => $result->isValid ? $result->cleanedData : null,
    'errors' => $result->errors
]);
```

---

## ۶. نکات امنیتی و بهترین روش‌ها

1. **همیشه داده‌ها را پاک‌سازی کنید:** کلاس Validator به صورت خودکار فاصله‌های اضافی رشته‌ها را حذف می‌کند، اما برای جلوگیری از XSS هنوز هم باید خروجی را در HTML Escape کنید.
2. **اعتبارسنجی سمت سرور:** هرگز به اعتبارسنجی سمت کلاینت (JavaScript) اکتفا نکنید.
3. **پیام‌های عمومی:** در محیط تولید، از نمایش دقیق جزئیات خطا که ممکن است ساختار دیتابیس را لو بدهد خودداری کنید.
4. **استفاده از HTTPS:** برای انتقال داده‌های حساس مثل رمز عبور حتماً از پروتکل امن استفاده کنید.

---

## ۷. سوالات متداول (FAQ)

**سوال:** آیا می‌توانم چندین قانون را با هم ترکیب کنم؟
**پاسخ:** بله، با استفاده از کاراکتر `|` می‌توانید بی‌نهایت قانون را زنجیره کنید.

**سوال:** چگونه یک فیلد را اختیاری کنم؟
**پاسخ:** کافیست قانون `required` را حذف کنید. اگر فیلد خالی باشد و `required` نباشد، سایر قوانین روی آن اجرا نمی‌شوند (مگر اینکه مقدار داشته باشد).

**سوال:** آیا ترتیب قوانین مهم است؟
**پاسخ:** خیر، اما پیشنهاد می‌شود ابتدا `required` و سپس سایر قوانین را بنویسید تا خوانایی بهتر شود.

---

برای یادگیری نحوه ساخت قوانین جدید، به مستندات **[توسعه قوانین سفارشی](EXTENDING.md)** مراجعه کنید.
