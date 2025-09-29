<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include './../includes/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}


$stmt = $conn->prepare("SELECT ho_ten, chuc_vu, luong FROM nhanvien WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    
    header('Location: index.php');
    exit;
}
$row = $res->fetch_assoc();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $ho_ten = trim($_POST['ho_ten'] ?? '');
    $chuc_vu = trim($_POST['chuc_vu'] ?? '');
    $luong_raw = $_POST['luong'] ?? '';

    if ($ho_ten === '') $errors[] = "Ho ten khong duoc de trong";
    if ($luong_raw === '' && $luong_raw !== '0') $errors[] = "Luong khong duoc de trong";
    $luong = (float) str_replace(',', '.', $luong_raw);

    if (empty($errors)) {
        $u = $conn->prepare("UPDATE nhanvien SET ho_ten = ?, chuc_vu = ?, luong = ? WHERE id = ?");
        $u->bind_param("ssdi", $ho_ten, $chuc_vu, $luong, $id);
        if ($u->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Loi cap nhat" . $u->error;
        }
    }

    $row['ho_ten'] = htmlspecialchars($ho_ten);
    $row['chuc_vu'] = htmlspecialchars($chuc_vu);
    $row['luong']  = htmlspecialchars($luong);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Sửa nhân sự</title>
  <link rel="stylesheet" href="style.css">
  <style>
    
    .errors { background:#ffe6e6; border:1px solid #ffb3b3; padding:10px; color:#a00; margin-bottom:12px; border-radius:6px; }
    .form-row { margin-bottom:12px; }
    label{ display:block; margin-bottom:6px; font-weight:600; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Sửa nhân sự</h2>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <?php foreach ($errors as $e) echo "<div>- " . htmlspecialchars($e) . "</div>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="form-row">
        <label for="ho_ten">Họ tên</label>
        <input id="ho_ten" type="text" name="ho_ten" required value="<?= htmlspecialchars($row['ho_ten']) ?>">
      </div>

      <div class="form-row">
        <label for="chuc_vu">Chức vụ</label>
        <input id="chuc_vu" type="text" name="chuc_vu" value="<?= htmlspecialchars($row['chuc_vu']) ?>">
      </div>

      <div class="form-row">
        <label for="luong">Lương</label>
        <input id="luong" type="number" step="0.01" name="luong" required value="<?= htmlspecialchars($row['luong']) ?>">
      </div>

      <button type="submit" class="btn btn-edit">Cập nhật</button>
      <a href="index.php" class="btn btn-edit" style="margin-left:10px;">Quay lại</a>
    </form>
  </div>
</body>
</html>
