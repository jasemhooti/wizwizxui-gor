<?php
include "baseInfo.php";
$connection = new mysqli('localhost',$dbUserName,$dbPassword,$dbName);
if($connection->connect_error){
    exit("error " . $connection->connect_error);  
}
$connection->set_charset("utf8mb4");

echo "<h2>بررسی وضعیت قرعه کشی</h2>\n";
echo "<pre>\n";

// بررسی جداول
echo "1. بررسی جداول:\n";
$tables = ['lottery_settings', 'lottery_codes', 'lottery_winners'];
foreach($tables as $table){
    $result = $connection->query("SHOW TABLES LIKE '$table'");
    if($result->num_rows > 0){
        echo "   ✅ جدول $table وجود دارد\n";
    } else {
        echo "   ❌ جدول $table وجود ندارد\n";
    }
}

// بررسی توابع در config.php
echo "\n2. بررسی فایل config.php:\n";
$configContent = file_get_contents('config.php');
$functions = [
    'getLotteryMenuKeys',
    'getAdminLotteryMenuKeys', 
    'getLotterySettings',
    'generateLotteryCode',
    'checkAndRunLottery'
];

foreach($functions as $func){
    if(strpos($configContent, "function $func") !== false){
        echo "   ✅ تابع $func وجود دارد\n";
    } else {
        echo "   ❌ تابع $func وجود ندارد\n";
    }
}

// بررسی دکمه در getMainKeys
echo "\n3. بررسی دکمه قرعه کشی در منوی اصلی:\n";
if(strpos($configContent, "🎲 قرعه کشی") !== false && strpos($configContent, "lotteryMenu") !== false){
    echo "   ✅ دکمه قرعه کشی در getMainKeys وجود دارد\n";
} else {
    echo "   ❌ دکمه قرعه کشی در getMainKeys وجود ندارد\n";
}

// بررسی دکمه در getAdminKeys
echo "\n4. بررسی دکمه مدیریت قرعه کشی در منوی ادمین:\n";
if(strpos($configContent, "🎲 مدیریت قرعه کشی") !== false && strpos($configContent, "adminLotteryMenu") !== false){
    echo "   ✅ دکمه مدیریت قرعه کشی در getAdminKeys وجود دارد\n";
} else {
    echo "   ❌ دکمه مدیریت قرعه کشی در getAdminKeys وجود ندارد\n";
}

// بررسی کدهای قرعه کشی در bot.php
echo "\n5. بررسی کدهای قرعه کشی در bot.php:\n";
$botContent = file_get_contents('bot.php');
$checks = [
    'checkAndRunLottery()' => 'فراخوانی تابع checkAndRunLottery',
    'lotteryMenu' => 'منوی قرعه کشی',
    'buyLotteryCode' => 'خرید کد قرعه کشی',
    'viewMyLotteryCodes' => 'مشاهده کدها',
    'lotteryStatus' => 'وضعیت قرعه کشی',
    'adminLotteryMenu' => 'منوی مدیریت ادمین'
];

foreach($checks as $key => $desc){
    if(strpos($botContent, $key) !== false){
        echo "   ✅ $desc وجود دارد\n";
    } else {
        echo "   ❌ $desc وجود ندارد\n";
    }
}

// بررسی پرداخت در pay/back.php
echo "\n6. بررسی پرداخت قرعه کشی در pay/back.php:\n";
if(file_exists('pay/back.php')){
    $backContent = file_get_contents('pay/back.php');
    if(strpos($backContent, 'LOTTERY_CODE') !== false){
        echo "   ✅ پرداخت قرعه کشی در back.php وجود دارد\n";
    } else {
        echo "   ❌ پرداخت قرعه کشی در back.php وجود ندارد\n";
    }
} else {
    echo "   ❌ فایل pay/back.php پیدا نشد\n";
}

// بررسی تنظیمات قرعه کشی
echo "\n7. بررسی تنظیمات قرعه کشی:\n";
$result = $connection->query("SELECT * FROM `lottery_settings` WHERE `id` = 1");
if($result->num_rows > 0){
    $settings = $result->fetch_assoc();
    echo "   ✅ تنظیمات قرعه کشی:\n";
    echo "      - مبلغ: " . number_format($settings['price']) . " تومان\n";
    if($settings['draw_time']){
        echo "      - زمان قرعه کشی: " . date("Y-m-d H:i:s", $settings['draw_time']) . "\n";
    } else {
        echo "      - زمان قرعه کشی: تعیین نشده\n";
    }
    echo "      - وضعیت: " . ($settings['is_drawn'] ? "انجام شده" : "در انتظار") . "\n";
} else {
    echo "   ❌ تنظیمات قرعه کشی وجود ندارد\n";
}

echo "\n</pre>\n";
echo "<h3>نتیجه:</h3>\n";
echo "<p>اگر همه موارد ✅ هستند، مشکل احتمالاً از کش ربات است. لطفا:</p>\n";
echo "<ol>\n";
echo "<li>یک پیام به ربات بفرستید (مثلاً /start)</li>\n";
echo "<li>یا ربات را restart کنید</li>\n";
echo "<li>یا یک بار /start را دوباره بزنید</li>\n";
echo "</ol>\n";

$connection->close();
?>

