<?php
include "baseInfo.php";
$connection = new mysqli('localhost',$dbUserName,$dbPassword,$dbName);
if($connection->connect_error){
    exit("error " . $connection->connect_error);  
}
$connection->set_charset("utf8mb4");

echo "شروع به روزرسانی جداول قرعه کشی...\n\n";

// ایجاد جدول lottery_settings
$result = $connection->query("CREATE TABLE IF NOT EXISTS `lottery_settings` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `price` int(255) NOT NULL DEFAULT 0,
  `draw_time` int(255) DEFAULT NULL,
  `is_drawn` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci");

if($result){
    echo "✅ جدول lottery_settings ایجاد شد\n";
} else {
    echo "⚠️ جدول lottery_settings از قبل وجود دارد یا خطا: " . $connection->error . "\n";
}

// بررسی وجود رکورد در lottery_settings
$check = $connection->query("SELECT * FROM `lottery_settings` WHERE `id` = 1");
if($check->num_rows == 0){
    $connection->query("INSERT INTO `lottery_settings` (`id`, `price`, `draw_time`, `is_drawn`) VALUES (1, 0, NULL, 0)");
    echo "✅ رکورد اولیه در lottery_settings اضافه شد\n";
} else {
    echo "✅ رکورد اولیه در lottery_settings از قبل وجود دارد\n";
}

// ایجاد جدول lottery_codes
$result = $connection->query("CREATE TABLE IF NOT EXISTS `lottery_codes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(10) NOT NULL,
  `code` varchar(50) NOT NULL,
  `purchase_date` int(255) NOT NULL,
  `pay_hash_id` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci");

if($result){
    echo "✅ جدول lottery_codes ایجاد شد\n";
} else {
    echo "⚠️ جدول lottery_codes از قبل وجود دارد یا خطا: " . $connection->error . "\n";
}

// ایجاد جدول lottery_winners
$result = $connection->query("CREATE TABLE IF NOT EXISTS `lottery_winners` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(10) NOT NULL,
  `code` varchar(50) NOT NULL,
  `draw_date` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci");

if($result){
    echo "✅ جدول lottery_winners ایجاد شد\n";
} else {
    echo "⚠️ جدول lottery_winners از قبل وجود دارد یا خطا: " . $connection->error . "\n";
}

echo "\n✅ به روزرسانی با موفقیت انجام شد!\n";
echo "حالا می‌توانید ربات را تست کنید.\n";

$connection->close();
?>
