<?php
// Bước 1: Lấy dữ liệu từ GET
$raw = $_GET['names'] ?? '';

// Bước 2: Mảng chứa tên hợp lệ
$names = [];

// Bước 3: Nếu chuỗi không rỗng thì xử lý
if (trim($raw) !== '') {

    // Bước 4: Tách chuỗi bằng dấu phẩy
    $parts = explode(',', $raw);

    // Bước 5: Trim từng phần tử
    $parts = array_map('trim', $parts);

    // Bước 6: Loại phần tử rỗng
    $names = array_filter($parts, function ($item) {
        return $item !== '';
    });
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 04 - Bài 1</title>
</head>
<body>

<h2>Bài 1: Chuỗi → Danh sách tên (GET)</h2>

<p>
    <strong>Chuỗi gốc:</strong>
    <?= htmlspecialchars($raw) ?>
</p>

<?php if (count($names) > 0): ?>

    <p>
        <strong>Số lượng tên hợp lệ:</strong>
        <?= count($names) ?>
    </p>

    <ol>
        <?php foreach ($names as $name): ?>
            <li><?= htmlspecialchars($name) ?></li>
        <?php endforeach; ?>
    </ol>

<?php else: ?>

    <p style="color:red;">Chưa có dữ liệu hợp lệ</p>

<?php endif; ?>

</body>
</html>
