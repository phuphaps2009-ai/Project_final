<?php
// เชื่อมต่อฐานข้อมูล XAMPP
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my_app";

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ระบบ Select Filter: รับค่าการคัดกรองบทบาท
$selected_role = isset($_GET['role_filter']) ? $_GET['role_filter'] : 'all';

// สร้าง SQL Query ตามเงื่อนไขการ Select (เรียงจากเก่าไปใหม่ ORDER BY id ASC เพื่อให้คนที่เพิ่มใหม่ไปอยู่ต่อท้าย)
if ($selected_role != 'all' && !empty($selected_role)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = ? ORDER BY id ASC");
    $stmt->bind_param("s", $selected_role);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM users ORDER BY id ASC");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการผู้ใช้งาน</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter & Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100/70 text-slate-800 min-h-screen p-4 sm:p-8">

    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-lg border border-slate-800">
            <div>
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-blue-600/20 text-blue-400 rounded-xl border border-blue-500/30">
                        <i class="fa-solid fa-users-gear text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">ระบบจัดการผู้ใช้งาน</h1>
                        <p class="text-sm text-slate-400 mt-0.5">แผงควบคุมและจัดการข้อมูลผู้ใช้</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 1: Form เพิ่มผู้ใช้งานใหม่ -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 transition-all duration-200 hover:shadow-md">
            <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                <i class="fa-solid fa-user-plus text-blue-600 text-lg"></i>
                <h2 class="text-lg font-bold text-slate-800">เพิ่มผู้ใช้งานใหม่</h2>
            </div>
            
            <form action="add_user.php" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">ชื่อ-นามสกุล</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-sm"></i>
                        </div>
                        <input type="text" id="name" name="name" required placeholder="กรอกชื่อ-นามสกุล" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition outline-none">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">อีเมล</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </div>
                        <input type="email" id="email" name="email" required placeholder="อีเมลของคุณ" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition outline-none">
                    </div>
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">สิทธิ์การใช้งาน</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </div>
                        <select id="role" name="role" required 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition outline-none appearance-none cursor-pointer">
                            <option value="Member">สมาชิก</option>
                            <option value="Editor">ผู้แก้ไข</option>
                            <option value="Admin">ผู้ดูแลระบบ</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full bg-slate-900 hover:bg-slate-800 active:scale-[0.98] text-white font-medium py-2.5 px-4 rounded-xl text-sm shadow-md shadow-slate-900/10 transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Controls & Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 transition-all duration-200 hover:shadow-md">
            
            <!-- Control Toolbar & Dynamic Bulk Action Bar -->
            <div class="bg-slate-50/80 p-4 rounded-2xl mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 border border-slate-200/60">
                
                <!-- Role Filter Dropdown -->
                <form method="GET" action="index.php" class="flex items-center gap-3 w-full sm:w-auto">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap flex items-center gap-1.5">
                        <i class="fa-solid fa-filter text-slate-400"></i> กรองตามสิทธิ์:
                    </span>
                    <div class="relative w-full sm:w-48">
                        <select name="role_filter" id="role_filter" onchange="this.form.submit()" 
                                class="w-full bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 pl-3.5 pr-8 py-2 shadow-sm transition outline-none cursor-pointer appearance-none">
                            <option value="all" <?= $selected_role == 'all' ? 'selected' : '' ?>>แสดงทั้งหมด</option>
                            <option value="Admin" <?= $selected_role == 'Admin' ? 'selected' : '' ?>>ผู้ดูแลระบบ</option>
                            <option value="Editor" <?= $selected_role == 'Editor' ? 'selected' : '' ?>>ผู้แก้ไข</option>
                            <option value="Member" <?= $selected_role == 'Member' ? 'selected' : '' ?>>สมาชิก</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </form>

                <!-- Dynamic Bulk Delete Button & Quick Actions -->
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    
                    <!-- Dynamic 'Delete Selected' Red Button -->
                    <button id="btnDeleteSelected" 
                            onclick="deleteSelected()"
                            type="button"
                            class="hidden bg-red-500 hover:bg-red-600 active:scale-95 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-md shadow-red-950/20 transition-all duration-200 flex items-center gap-2 border border-red-400/30 animate-pulse">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>ลบรายการที่เลือก</span>
                        <span id="selectedCount" class="bg-red-700/80 text-white px-2 py-0.5 rounded-full text-[11px] font-bold shadow-inner">0</span>
                    </button>

                    <!-- Quick Action Buttons -->
                    <div class="flex items-center gap-1.5 text-xs font-semibold">
                        <button type="button" onclick="toggleAllCheckboxes(true)" class="text-blue-600 hover:text-blue-700 px-3 py-2 rounded-xl bg-blue-50/80 hover:bg-blue-100/80 border border-blue-200/50 transition">
                            เลือกทั้งหมด
                        </button>
                        <button type="button" onclick="toggleAllCheckboxes(false)" class="text-slate-600 hover:text-slate-700 px-3 py-2 rounded-xl bg-slate-200/60 hover:bg-slate-200 border border-slate-300/50 transition">
                            ยกเลิกการเลือก
                        </button>
                    </div>

                </div>
            </div>

            <!-- Data Table -->
            <form id="deleteForm" action="delete.php" method="POST">
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80 shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900 text-slate-300 text-xs uppercase font-semibold tracking-wider">
                            <tr>
                                <th class="p-4 w-12 text-center">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 rounded border-slate-700 focus:ring-blue-500/30 focus:ring-offset-slate-900 cursor-pointer">
                                </th>
                                <th class="p-4">ลำดับ</th>
                                <th class="p-4">ชื่อ-นามสกุล</th>
                                <th class="p-4">อีเมล</th>
                                <th class="p-4">สิทธิ์การใช้งาน</th>
                                <th class="p-4 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm bg-white">
                            <?php if ($result->num_rows > 0): ?>
                                <?php $i = 1; ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr class="hover:bg-blue-50/50 transition duration-150 item-row">
                                        <td class="p-4 text-center">
                                            <input type="checkbox" name="ids[]" value="<?= $row['id'] ?>" class="item-checkbox w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500/20 cursor-pointer">
                                        </td>
                                        
                                        <td class="p-4 font-semibold text-slate-400">#<?= $i++ ?></td>
                                        
                                        <td class="p-4 font-medium text-slate-900"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="p-4 text-slate-500"><?= htmlspecialchars($row['email']) ?></td>
                                        
                                        <!-- Colorful Pill Badges -->
                                        <td class="p-4">
                                            <?php if ($row['role'] == 'Admin'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> ผู้ดูแลระบบ
                                                </span>
                                            <?php elseif ($row['role'] == 'Editor'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> ผู้แก้ไข
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> สมาชิก
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- Individual Action -->
                                        <td class="p-4 text-center">
                                            <button type="button" 
                                                    onclick="deleteSingle(<?= $row['id'] ?>)" 
                                                    class="text-slate-400 hover:text-red-600 p-2 rounded-xl hover:bg-red-50 border border-transparent hover:border-red-100 transition-all duration-200"
                                                    title="ลบรายการนี้">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fa-regular fa-folder-open text-3xl text-slate-300"></i>
                                            <p class="font-medium text-slate-500">ไม่พบข้อมูลในระบบ</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const btnDeleteSelected = document.getElementById('btnDeleteSelected');
        const selectedCount = document.getElementById('selectedCount');
        const deleteForm = document.getElementById('deleteForm');

        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const count = checkedBoxes.length;
            
            selectedCount.textContent = count;
            
            if (count > 0) {
                btnDeleteSelected.classList.remove('hidden');
            } else {
                btnDeleteSelected.classList.add('hidden');
            }

            checkboxes.forEach(box => {
                const row = box.closest('tr');
                if (box.checked) {
                    row.classList.add('bg-blue-50');
                } else {
                    row.classList.remove('bg-blue-50');
                }
            });
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(box => box.checked = this.checked);
            updateDeleteButton();
        });

        checkboxes.forEach(box => {
            box.addEventListener('change', function() {
                if (!this.checked) selectAll.checked = false;
                if (document.querySelectorAll('.item-checkbox:checked').length === checkboxes.length) {
                    selectAll.checked = true;
                }
                updateDeleteButton();
            });
        });

        function toggleAllCheckboxes(status) {
            checkboxes.forEach(box => box.checked = status);
            selectAll.checked = status;
            updateDeleteButton();
        }

        function deleteSelected() {
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการที่เลือกทั้งหมด?')) {
                deleteForm.submit();
            }
        }

        function deleteSingle(id) {
            if (confirm(`คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?`)) {
                window.location.href = `delete.php?single_id=${id}`;
            }
        }
    </script>
</body>
</html>