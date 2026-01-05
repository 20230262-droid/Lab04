<?php
// 1. Khai báo mảng điểm
$scores = [8.5, 7.0, 9.25, 6.5, 8.0, 5.75];

// 2. Tính điểm trung bình
$total = array_sum($scores);
$count = count($scores);
$avg = $count > 0 ? $total / $count : 0;

// 3. Lọc các điểm >= 8.0
$goodScores = array_filter($scores, function ($score) {
    return $score >= 8.0;
});

// 4. Tìm max, min
$maxScore = max($scores);
$minScore = min($scores);

// 5. Sắp xếp (không làm mất mảng gốc)
$ascScores = $scores;   // copy mảng
$descScores = $scores; // copy mảng

sort($ascScores);   // tăng dần
rsort($descScores); // giảm dần
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 04 - Bài 2</title>
</head>
<body>

<h2>Bài 2: Mảng điểm – Thống kê & Sắp xếp</h2>

<p><strong>Mảng điểm gốc:</strong></p>
<p><?= htmlspecialchars(implode(', ', $scores)) ?></p>

<hr>

<p><strong>Điểm trung bình:</strong>
    <?= number_format($avg, 2) ?>
</p>

<hr>

<p><strong>Số lượng điểm ≥ 8.0:</strong>
    <?= count($goodScores) ?>
</p>

<?php if (count($goodScores) > 0): ?>
    <ul>
        <?php foreach ($goodScores as $s): ?>
            <li><?= htmlspecialchars($s) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<hr>

<p><strong>Điểm cao nhất:</strong> <?= $maxScore ?></p>
<p><strong>Điểm thấp nhất:</strong> <?= $minScore ?></p>

<hr>

<p><strong>Sắp xếp tăng dần:</strong></p>
<p><?= implode(', ', $ascScores) ?></p>

<p><strong>Sắp xếp giảm dần:</strong></p>
<p><?= implode(', ', $descScores) ?></p>

</body>
</html>
