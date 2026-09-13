<?php
// ตั้งค่าเชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my_app";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// กรณีที่ 1: ลบหลายรายการที่เลือกผ่าน Checkbox (วิธี POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    // แปลงค่า ID ให้เป็นตัวเลขทั้งหมดเพื่อความปลอดภัย (intval)
    $ids = array_map('intval', $_POST['ids']); 
    // แปลงอาร์เรย์ตัวเลขให้เป็นข้อความคั่นด้วยเครื่องหมายจุลภาค เช่น "1,2,3"
    $id_list = implode(',', $ids);

    if (!empty($id_list)) {
        // คำสั่งลบหลาย ID พร้อมกันด้วย IN (...)
        $sql = "DELETE FROM users WHERE id IN ($id_list)";
        $conn->query($sql);
    }
}

// กรณีที่ 2: ลบรายการเดียวโดยส่ง ID ผ่าน URL (วิธี GET)
if (isset($_GET['single_id'])) {
    $id = intval($_GET['single_id']); // แปลงค่าเป็นตัวเลข
    // คำสั่งลบข้อมูลตาม ID ที่ระบุ
    $sql = "DELETE FROM users WHERE id = $id";
    $conn->query($sql);
}

// เมื่อประมวลผลการลบเสร็จ ให้สั่งเปลี่ยนหน้ากลับไปยัง index.php
header("Location: index.php");
exit();
?>