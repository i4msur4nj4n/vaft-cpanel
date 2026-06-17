<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$base = dirname(__DIR__);
require "$base/vendor/autoload.php";
$app = require "$base/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$db = Illuminate\Support\Facades\DB::connection()->getPdo();

// Clear existing data first
$db->exec("SET FOREIGN_KEY_CHECKS=0");
$db->exec("TRUNCATE transactions");
$db->exec("TRUNCATE invoices");
$db->exec("TRUNCATE projects");
$db->exec("TRUNCATE categories");
$db->exec("TRUNCATE users");
$db->exec("SET FOREIGN_KEY_CHECKS=1");

// Import users
$db->exec("INSERT INTO users (id, name, email, password, role, remember_token, created_at, updated_at) VALUES 
(1,'Admin Rahman','admin@agro.com','\$2y\$12\$.MxKCZMT0Q1pN8R6swdRe.LnqYqVdIFzs.z.6evmwyhsWukvgKrWS','admin',NULL,'2026-06-16 02:55:11','2026-06-16 02:55:11'),
(2,'Sajib Ahmed','user@agro.com','\$2y\$12\$7DEZMGa/NEsRU/kj9OmT1OCk/r3s35GhhSo6nNE4edyQpyREjuZMy','user',NULL,'2026-06-16 02:55:11','2026-06-16 02:55:11')");
echo "✓ Users imported\n";

// Categories
$db->exec("INSERT INTO categories (id, name_en, name_bn, description, icon, created_at, updated_at) VALUES 
(1,'Salary','বেতন','Monthly salary and wages','💰','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(2,'Business','ব্যবসা','Business income and expenses','💼','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(3,'Investments','বিনিয়োগ','Investment returns','📈','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(4,'Food','খাবার','Food and dining','🍚','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(5,'Housing','বাসস্থান','Rent and housing','🏠','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(6,'Utilities','ইউটিলিটি','Electricity, water, gas','⚡','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(7,'Transport','যাতায়াত','Transportation costs','🚗','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(8,'Entertainment','বিনোদন','Entertainment and leisure','🎬','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(9,'Medical','চিকিৎসা','Healthcare expenses','🏥','2026-06-16 02:55:11','2026-06-16 02:55:11'),
(10,'Education','শিক্ষা','Education and training','📚','2026-06-16 02:55:12','2026-06-16 02:55:12')");
echo "✓ Categories imported\n";

// Projects
$db->exec("INSERT INTO projects (id, name, description, status, capital, `returns`, created_at, updated_at) VALUES 
(1,'Poultry Farm Expansion','Expanding chicken farm capacity by 500 birds','active',150000.00,45000.00,'2026-06-16 02:55:12','2026-06-16 02:55:12'),
(2,'Fish Cultivation','Tilapia and Rui fish farming in 2 ponds','active',80000.00,25000.00,'2026-06-16 02:55:12','2026-06-16 02:55:12')");
echo "✓ Projects imported\n";

// Invoices
$db->exec("INSERT INTO invoices (id, invoice_number, client_name, client_email, issue_date, due_date, amount, status, created_at, updated_at) VALUES 
(1,'INV-2026-001','Rahman Poultry Ltd','rahman@poultry.com','2026-06-01','2026-06-30',25000.00,'paid','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(2,'INV-2026-002','Green Valley Farms','info@greenvalley.com','2026-06-05','2026-07-05',18500.00,'unpaid','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(3,'INV-2026-003','Dhaka Fish Market','orders@dhakafish.com','2026-06-10','2026-07-10',12000.00,'unpaid','2026-06-16 02:55:12','2026-06-16 02:55:12')");
echo "✓ Invoices imported\n";

// Transactions
$db->exec("INSERT INTO transactions (id, user_id, category_id, project_id, type, amount, date, notes, created_at, updated_at) VALUES 
(1,1,1,NULL,'income',45000.00,'2026-06-01','Monthly salary deposit June 2026','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(2,1,2,NULL,'income',8000.00,'2026-06-06','Freelance UI design service contract','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(3,1,4,NULL,'expense',1200.00,'2026-06-07','Family weekend dinner at local diner','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(4,1,9,NULL,'expense',4800.00,'2026-06-06','Doctor consultation & medicine buying','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(5,1,6,NULL,'expense',2500.00,'2026-06-05','Electricity billing monthly payment','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(6,1,7,NULL,'expense',1500.00,'2026-06-04','Uber & Rickshaw commutes','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(7,1,5,NULL,'expense',12000.00,'2026-06-02','Monthly house rent payment','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(8,1,4,NULL,'expense',3200.00,'2026-06-03','Weekly grocery shopping','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(9,2,1,NULL,'income',35000.00,'2026-06-01','Monthly salary deposit','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(10,2,8,NULL,'expense',800.00,'2026-06-05','Netflix & Spotify subscription','2026-06-16 02:55:12','2026-06-16 02:55:12'),
(11,1,1,1,'income',200.00,'2026-06-16','Test data','2026-06-16 03:15:02','2026-06-16 03:15:02')");
echo "✓ Transactions imported\n";

echo "\nAll data migrated successfully!";
unlink(__FILE__);
