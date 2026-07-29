# 🔌 توسعه قوانین سفارشی (Extending the Validator)

یکی از قدرتمندترین ویژگی‌های این کتابخانه، قابلیت تعریف قوانین اعتبارسنجی جدید توسط شماست. در این مستندات یاد می‌گیرید چگونه یک قانون سفارشی بسازید و به سیستم ثبت کنید.

---

## ۱. اینترفیس `RuleInterface`

تمام قوانین باید اینترفیس `Toolkit\Validator\Contracts\RuleInterface` را پیاده‌سازی کنند. این اینترفیس دو متد دارد:

```php
namespace Toolkit\Validator\Contracts;

interface RuleInterface
{
    /**
     * بررسی اعتبار مقدار
     *
     * @param mixed $value مقدار ورودی
     * @param array $params پارامترهای قانون (اختیاری)
     * @return bool true اگر معتبر بود، false در غیر این صورت
     */
    public function validate($value, array $params = []): bool;

    /**
     * دریافت پیام خطا
     *
     * @param string $field نام فیلد
     * @param array $params پارامترهای قانون
     * @return string پیام خطا
     */
    public function getMessage(string $field, array $params = []): string;
}
```

---

## ۲. مثال عملی: ساخت قانون "کد ملی"

فرض می‌خواهیم قانونی برای بررسی کد ملی ایران بسازیم.

### گام اول: ایجاد فایل کلاس
یک فایل جدید در پوشه `src/Validator/Rules/` بسازید، مثلاً `NationalCodeRule.php`.

```php
<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

class NationalCodeRule implements RuleInterface
{
    /**
     * الگوریتم بررسی صحت کد ملی ایران
     */
    public function validate($value, array $params = []): bool
    {
        if (!is_string($value) || strlen($value) !== 10) {
            return false;
        }

        // تبدیل به آرایه اعداد
        $digits = str_split($value);
        
        // بررسی تکراری نبودن همه ارقام (کدهای مثل 1111111111 معتبر نیستند)
        if (count(array_unique($digits)) === 1) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$digits[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $controlDigit = (int)$digits[9];

        if ($remainder < 2) {
            return $controlDigit === $remainder;
        } else {
            return $controlDigit === (11 - $remainder);
        }
    }

    /**
     * پیام خطای فارسی یا انگلیسی
     */
    public function getMessage(string $field, array $params = []): string
    {
        return "The $field field must be a valid Iranian National Code.";
        // یا برای فارسی: "فیلد $field باید یک کد ملی معتبر باشد."
    }
}
```

### گام دوم: ثبت و استفاده از قانون

حالا می‌توانید از این قانون در اعتبارسنجی استفاده کنید:

```php
<?php
require_once 'src/Bootstrap.php';

use Toolkit\Validator\Validator;
use Toolkit\Validator\Rules\NationalCodeRule;

$data = [
    'national_code' => '1234567891' // یک کد ملی تستی
];

$rules = [
    'national_code' => 'required|national_code'
];

$validator = new Validator();

// ثبت قانون سفارشی با نام 'national_code'
$validator->addRule('national_code', new NationalCodeRule());

$result = $validator->validate($data, $rules);

if ($result->isValid) {
    echo "کد ملی معتبر است.";
} else {
    print_r($result->errors);
}
```

---

## ۳. مثال پیشرفته: قانون با پارامتر

فرض کنید قانونی می‌خواهید که مقدار ورودی باید در یک بازه عددی خاص باشد (مثلاً بین min و max).

```php
<?php

declare(strict_types=1);

namespace Toolkit\Validator\Rules;

use Toolkit\Validator\Contracts\RuleInterface;

class RangeRule implements RuleInterface
{
    public function validate($value, array $params = []): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        // انتظار داریم params شامل [min, max] باشد
        if (count($params) < 2) {
            return false;
        }

        $min = (float)$params[0];
        $max = (float)$params[1];
        $val = (float)$value;

        return ($val >= $min && $val <= $max);
    }

    public function getMessage(string $field, array $params = []): string
    {
        $min = $params[0] ?? 0;
        $max = $params[1] ?? 0;
        return "The $field field must be between $min and $max.";
    }
}
```

**نحوه استفاده:**
```php
$rules = [
    'score' => 'range:0,20' // پارامترها با کاما جدا می‌شوند
];

$validator->addRule('range', new RangeRule());
```

---

## ۴. نکات کلیدی در توسعه

1. **نام‌گذاری:** نام کلاس باید حتماً با `Rule` تمام شود (مثلاً `EmailRule`).
2. **بدون وابستگی:** سعی کنید قوانین شما به کلاس‌های دیگر وابسته نباشند تا قابل حمل باشند.
3. **پارامترها:** اگر قانون شما پارامتر می‌گیرد، حتماً در متد `validate` آرایه `$params` را بررسی کنید.
4. **پیام‌ها:** در متد `getMessage` می‌توانید از `{field}` و `{param}` در رشته پیام استفاده کنید (کلاس اصلی به صورت خودکار جایگزین نمی‌کند، پس بهتر است دستی در متد خودتان انجام دهید یا از فرمت ساده استفاده کنید).

---

## ۵. اشتراک‌گذاری قوانین

اگر قانون مفیدی نوشتید، می‌توانید آن را به مخزن اصلی اضافه کنید یا به عنوان یک پکیج جداگانه منتشر نمایید. کافیست فایل کلاس را در پوشه `Rules` قرار دهید و مطمئن شوید Namespace آن صحیح است.
