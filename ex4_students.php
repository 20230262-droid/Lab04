<?php
require_once "Student.php";

// Hàm render HTML an toàn
function h($s) {
    return htmlspecialchars($s);
}

// 1. Tạo danh sách sinh viên
$students = [
    new Student("SV01", "Vũ Mạnh Dũng", 3.4),
    new Student("SV02", "Phan Đình Thi", 2.8),
    new Student("SV03", "Lò Thị Tôn", 2.3),
    new Student("SV04", "Vũ Văn Nam", 3.6),
    new Student("SV05", "Hoàng Văn E", 2.6),
];

// 2. Lấy danh sách GPA để tính trung bình
$gpas = array_map(function ($st) {
    return $st->getGpa();
}, $students);

$avgGpa = count($gpas) > 0 ? array_sum($gpas) / count($gpas) : 0;

// 3. Thống kê xếp loại
$rankCount = [
    "Giỏi" => 0,
    "Khá" => 0,
    "Trung bình" => 0
];

foreach ($students as $st) {
    $rank = $st->rank();
    $rankCount[$rank]++;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 04 - Bài 4</title>
    <style>
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>Bài 4: OOP Student</h2>

<table>
    <tr>
        <th>STT</th>
        <th>ID</th>
        <th>Name</th>
        <th>GPA</th>
        <th>Rank</th>
    </tr>

    <?php foreach ($students as $i => $st): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($st->getId()) ?></td>
            <td><?= h($st->getName()) ?></td>
            <td><?= number_format($st->getGpa(), 2) ?></td>
            <td><?= $st->rank() ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>

<p>
    <strong>GPA trung bình lớp:</strong>
    <?= number_format($avgGpa, 2) ?>
</p>

<p><strong>Thống kê xếp loại:</strong></p>
<ul>
    <li>Giỏi: <?= $rankCount["Giỏi"] ?></li>
    <li>Khá: <?= $rankCount["Khá"] ?></li>
    <li>Trung bình: <?= $rankCount["Trung bình"] ?></li>
</ul>

</body>
</html>
