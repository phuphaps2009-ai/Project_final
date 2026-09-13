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

// ตรวจสอบว่ามีข้อมูลถูกส่งมาจากฟอร์มผ่านวิธี POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตัดช่องว่างหน้า-หลังข้อมูลด้วย trim()
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // ตรวจสอบว่าไม่ได้ส่งค่าว่างเข้ามา
    if (!empty($name) && !empty($email) && !empty($role)) {
        // ใช้ Prepared Statement เพื่อเพิ่มข้อมูลลงฐานข้อมูลอย่างปลอดภัย (ป้องกัน SQL Injection)
        $stmt = $conn->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $role); // "sss" หมายถึงส่งค่า String 3 ตัว
        $stmt->execute();
        $stmt->close();
    }
}

// เมื่อประมวลผลเพิ่มข้อมูลเสร็จ ให้สั่งเปลี่ยนหน้ากลับไปยัง index.php
header("Location: index.php");
exit();
?>