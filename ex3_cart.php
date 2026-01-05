<?php
// Hàm hỗ trợ render HTML an toàn
function h($s) {
    return htmlspecialchars($s);
}

// 1. Khai báo mảng sản phẩm
$products = [
    ['name' => 'Bút bi',   'price' => 5000,  'qty' => 10],
    ['name' => 'Vở',      'price' => 12000, 'qty' => 5],
    ['name' => 'Thước',   'price' => 8000,  'qty' => 3],
    ['name' => 'Balo',    'price' => 250000,'qty' => 1],
];

// 2. Thêm cột amount = price * qty
$productsWithAmount = array_map(function ($p) {
    $p['amount'] = $p['price'] * $p['qty'];
    return $p;
}, $products);

// 3. Tính tổng tiền đơn hàng
$totalAmount = array_reduce($productsWithAmount, function ($sum, $p) {
    return $sum + $p['amount'];
}, 0);

// 4. Tìm sản phẩm có amount lớn nhất
$maxProduct = null;
$maxAmount = 0;

foreach ($productsWithAmount as $p) {
    if ($p['amount'] > $maxAmount) {
        $maxAmount = $p['amount'];
        $maxProduct = $p;
    }
}

// 5. Sắp xếp theo price giảm dần (copy mảng trước)
$sortedProducts = $productsWithAmount;
usort($sortedProducts, function ($a, $b) {
    return $b['price'] <=> $a['price'];
});
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 04 - Bài 3</title>
    <style>
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>Bài 3: Giỏ hàng</h2>

<table>
    <tr>
        <th>STT</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Amount</th>
    </tr>

    <?php foreach ($productsWithAmount as $i => $p): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($p['name']) ?></td>
            <td><?= number_format($p['price']) ?></td>
            <td><?= $p['qty'] ?></td>
            <td><?= number_format($p['amount']) ?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <th colspan="4">Tổng tiền</th>
        <th><?= number_format($totalAmount) ?></th>
    </tr>
</table>

<br>

<p>
    <strong>Sản phẩm có amount lớn nhất:</strong><br>
    <?= h($maxProduct['name']) ?> 
    (<?= number_format($maxProduct['amount']) ?>)
</p>

<hr>

<h3>Danh sách sau khi sắp xếp theo giá giảm dần</h3>

<table>
    <tr>
        <th>STT</th>
        <th>Name</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Amount</th>
    </tr>

    <?php foreach ($sortedProducts as $i => $p): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($p['name']) ?></td>
            <td><?= number_format($p['price']) ?></td>
            <td><?= $p['qty'] ?></td>
            <td><?= number_format($p['amount']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
