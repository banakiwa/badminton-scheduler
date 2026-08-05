<?php
header('Content-Type: text/html; charset=UTF-8');

$host = 'db';
$dbname = 'badminton_db';
$user = 'user';
$pass = 'password';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, $options);
} catch (PDOException $e) {
    die("DB接続エラー: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['attendance_date'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO members (name, attendance_date, status) VALUES (:name, :date, :status)");
    $stmt->execute([':name' => $name, ':date' => $date, ':status' => $status]);
    
    header("Location: index.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM members ORDER BY attendance_date ASC, id DESC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>バドミントン出欠管理</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">🏸 バドミントン出欠・スケジュール管理</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">出欠を登録する</h5>
            <form action="index.php" method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">名前</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">参加日</label>
                    <input type="date" name="attendance_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ステータス</label>
                    <select name="status" class="form-select">
                        <option value="参加">参加</option>
                        <option value="不参加">不参加</option>
                        <option value="未定">未定</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">登録</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">登録一覧</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>名前</th>
                        <th>参加日</th>
                        <th>ステータス</th>
                        <th>登録日時</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member['name']) ?></td>
                        <td><?= htmlspecialchars($member['attendance_date']) ?></td>
                        <td>
                            <?php 
                            $badge = 'bg-secondary';
                            if ($member['status'] === '参加') $badge = 'bg-success';
                            if ($member['status'] === '不参加') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($member['status']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($member['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>