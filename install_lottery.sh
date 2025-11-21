#!/bin/bash

echo "=========================================="
echo "نصب و به‌روزرسانی سیستم قرعه کشی"
echo "=========================================="
echo ""

# بررسی وجود فایل baseInfo.php
if [ ! -f "baseInfo.php" ]; then
    echo "❌ خطا: فایل baseInfo.php پیدا نشد!"
    echo "لطفا مطمئن شوید که در پوشه اصلی ربات هستید."
    exit 1
fi

echo "✅ فایل baseInfo.php پیدا شد"
echo ""

# اجرای به‌روزرسانی دیتابیس
echo "در حال ایجاد/به‌روزرسانی جداول دیتابیس..."
php update_lottery.php

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ جداول دیتابیس با موفقیت ایجاد شدند"
else
    echo ""
    echo "❌ خطا در ایجاد جداول دیتابیس"
    exit 1
fi

echo ""
echo "=========================================="
echo "بررسی فایل‌ها..."
echo "=========================================="

# بررسی وجود توابع در config.php
if grep -q "function getLotteryMenuKeys" config.php; then
    echo "✅ توابع قرعه کشی در config.php وجود دارند"
else
    echo "❌ توابع قرعه کشی در config.php پیدا نشدند!"
    echo "لطفا مطمئن شوید که فایل config.php به‌روزرسانی شده است."
    exit 1
fi

# بررسی وجود دکمه در getMainKeys
if grep -q "🎲 قرعه کشی" config.php && grep -q "lotteryMenu" config.php; then
    echo "✅ دکمه قرعه کشی در منوی اصلی وجود دارد"
else
    echo "❌ دکمه قرعه کشی در منوی اصلی پیدا نشد!"
    exit 1
fi

# بررسی وجود دکمه در getAdminKeys
if grep -q "🎲 مدیریت قرعه کشی" config.php && grep -q "adminLotteryMenu" config.php; then
    echo "✅ دکمه مدیریت قرعه کشی در منوی ادمین وجود دارد"
else
    echo "❌ دکمه مدیریت قرعه کشی در منوی ادمین پیدا نشد!"
    exit 1
fi

# بررسی کدهای bot.php
if grep -q "checkAndRunLottery()" bot.php; then
    echo "✅ کدهای قرعه کشی در bot.php وجود دارند"
else
    echo "❌ کدهای قرعه کشی در bot.php پیدا نشدند!"
    exit 1
fi

echo ""
echo "=========================================="
echo "✅ نصب با موفقیت انجام شد!"
echo "=========================================="
echo ""
echo "حالا لطفا:"
echo "1. یک پیام به ربات بفرستید (مثلاً /start)"
echo "2. یا ربات را restart کنید"
echo "3. سپس دکمه '🎲 قرعه کشی' باید در منوی اصلی ظاهر شود"
echo ""

