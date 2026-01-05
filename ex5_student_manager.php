<?php
require_once "Student.php";

function h($s) {
    return htmlspecialchars($s);
}

$raw = $_POST['raw'] ?? '';
$threshold = $_POST['threshold'] ?? '';
$sortDesc = isset($_POST['sort_desc']);

$students = [];
$error = "";

// Khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Parse chuỗi textarea
    $records = explode(';', $raw);

    foreach ($records as $rec) {
        $rec = trim($rec);
        if ($rec === '') continue;

        // SV001-An-3.2
        $parts = explode('-', $rec);
        if (count($parts) !== 3) continue;

        [$id, $name, $gpaStr] = $parts;

        if (!is_numeric($gpaStr)) continue;

        $students[] = new Student(
            trim($id),
            trim($name),
            floatval($gpaStr)
        );
    }

    // 2. Nếu không parse được sinh viên nào
    if (count($students) === 0) {
        $error = "Dữ liệu không hợp lệ hoặc không parse được sinh viên nào.";
    }

    // 3. Filter theo threshold
    if ($threshold !== '' && is_numeric($threshold)) {
        $students = array_filter($students, function ($st) use ($threshold) {
            return $st->getGpa() >= floatval($threshold);
        });
    }

    // 4. Sort GPA giảm dần
    if ($sortDesc) {
        usort($students, function ($a, $b) {
            return $b->getGpa() <=> $a->getGpa();
        });
    }
}

// Thống kê
$gpas = array_map(fn($st) => $st->getGpa(), $students);
$avg = count($gpas) ? array_sum($gpas) / count($gpas) : 0;
$max = count($gpas) ? max($gpas) : 0;
$min = count($gpas) ? min($gpas) : 0;

$rankCount = [
    "Giỏi" => 0,
    "Khá" => 0,
    "Trung bình" => 0
];

foreach ($students as $st) {
    $rankCount[$st->rank()]++;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 04 - Bài 5</title>
    <style>
        table { border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #eee; }
        textarea { width: 90%; height: 100px; }
    </style>
</head>
<body>

<h2>Bài 5: Student Manager</h2>

<form method="post">
    <p>
        <strong>Dữ liệu sinh viên:</strong><br>
        <textarea name="raw"><?= h($raw) ?></textarea>
    </p>

    <p>
        Lọc GPA ≥ 
        <input type="text" name="threshold" value="<?= h($threshold) ?>">
    </p>

    <p>
        <label>
            <input type="checkbox" name="sort_desc" <?= $sortDesc ? 'checked' : '' ?>>
            Sắp xếp GPA giảm dần
        </label>
    </p>

    <button type="submit">Parse & Show</button>
</form>

<hr>

<?php if ($error): ?>
    <p style="color:red;"><?= h($error) ?></p>
<?php endif; ?>

<?php if (count($students) > 0): ?>

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

<p><strong>Thống kê:</strong></p>
<ul>
    <li>GPA trung bình: <?= number_format($avg, 2) ?></li>
    <li>GPA cao nhất: <?= number_format($max, 2) ?></li>
    <li>GPA thấp nhất: <?= number_format($min, 2) ?></li>
    <li>Giỏi: <?= $rankCount["Giỏi"] ?></li>
    <li>Khá: <?= $rankCount["Khá"] ?></li>
    <li>Trung bình: <?= $rankCount["Trung bình"] ?></li>
</ul>

<?php endif; ?>

</body>
</html>
