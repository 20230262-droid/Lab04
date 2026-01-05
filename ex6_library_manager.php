<?php
require_once "Book.php";

function h($s) {
    return htmlspecialchars($s);
}

$raw = $_POST['raw'] ?? '';
$q = $_POST['q'] ?? '';
$sortQtyDesc = isset($_POST['sort_qty']);

$books = [];
$error = "";

// Khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Parse dữ liệu textarea
    $records = explode(';', $raw);

    foreach ($records as $rec) {
        $rec = trim($rec);
        if ($rec === '') continue;

        // B001-Intro to PHP-2
        $parts = explode('-', $rec);
        if (count($parts) !== 3) continue;

        [$id, $title, $qtyStr] = $parts;

        if (!is_numeric($qtyStr)) continue;

        $books[] = new Book(
            trim($id),
            trim($title),
            intval($qtyStr)
        );
    }

    // 2. Nếu không có dữ liệu hợp lệ
    if (count($books) === 0) {
        $error = "Không có dữ liệu sách hợp lệ.";
    }

    // 3. Tìm theo title (không phân biệt hoa thường)
    if ($q !== '') {
        $books = array_filter($books, function ($b) use ($q) {
            return stripos($b->getTitle(), $q) !== false;
        });
    }

    // 4. Sort theo Qty giảm dần
    if ($sortQtyDesc) {
        usort($books, function ($a, $b) {
            return $b->getQty() <=> $a->getQty();
        });
    }
}

// 5. Thống kê
$totalTitles = count($books);
$totalQty = 0;
$maxBook = null;
$outOfStockCount = 0;

foreach ($books as $b) {
    $totalQty += $b->getQty();

    if ($maxBook === null || $b->getQty() > $maxBook->getQty()) {
        $maxBook = $b;
    }

    if ($b->getQty() == 0) {
        $outOfStockCount++;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 04 - Bài 6A</title>
    <style>
        table { border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #eee; }
        textarea { width: 90%; height: 100px; }
    </style>
</head>
<body>

<h2>Bài 6A: Library Manager</h2>

<form method="post">
    <p>
        <strong>Dữ liệu sách:</strong><br>
        <textarea name="raw"><?= h($raw) ?></textarea>
    </p>

    <p>
        Tìm theo Title:
        <input type="text" name="q" value="<?= h($q) ?>">
    </p>

    <p>
        <label>
            <input type="checkbox" name="sort_qty" <?= $sortQtyDesc ? 'checked' : '' ?>>
            Sort Qty giảm dần
        </label>
    </p>

    <button type="submit">Parse & Show</button>
</form>

<hr>

<?php if ($error): ?>
    <p style="color:red;"><?= h($error) ?></p>
<?php endif; ?>

<?php if (count($books) > 0): ?>

<table>
    <tr>
        <th>STT</th>
        <th>BookID</th>
        <th>Title</th>
        <th>Qty</th>
        <th>Status</th>
    </tr>

    <?php foreach ($books as $i => $b): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($b->getId()) ?></td>
            <td><?= h($b->getTitle()) ?></td>
            <td><?= $b->getQty() ?></td>
            <td><?= $b->status() ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>

<p><strong>Thống kê:</strong></p>
<ul>
    <li>Tổng đầu sách: <?= $totalTitles ?></li>
    <li>Tổng số quyển: <?= $totalQty ?></li>
    <li>Sách có Qty lớn nhất:
        <?= $maxBook ? h($maxBook->getTitle()) . " ({$maxBook->getQty()})" : '' ?>
    </li>
    <li>Số sách Out of stock: <?= $outOfStockCount ?></li>
</ul>

<?php endif; ?>

</body>
</html>
