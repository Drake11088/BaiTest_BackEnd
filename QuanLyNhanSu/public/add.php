<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include './../includes/db.php';
$errors = [];
$old = ['ho_ten'=>'', 'chuc_vu'=>'', 'luong'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['ho_ten'] = trim($_POST['ho_ten'] ?? '');
    $old['chuc_vu'] = trim($_POST['chuc_vu'] ?? '');
    $old['luong'] = $_POST['luong'] ?? '';

    
    if ($old['ho_ten'] === '') $errors[] = "Họ tên không được để trống.";
    if ($old['luong'] === '' && $old['luong'] !== '0') $errors[] = "Lương không được để trống.";
    
    if ($old['luong'] !== '') {
        
        $luong_val = (float) str_replace(',', '.', $old['luong']);
        if (!is_numeric($luong_val) || $luong_val < 0) $errors[] = "Lương không hợp lệ.";
    } else {
        $luong_val = 0.0;
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO nhanvien (ho_ten, chuc_vu, luong) VALUES (?, ?, ?)");
        if (!$stmt) {
            $errors[] = "Prepare failed: " . $conn->error;
        } else {
           $stmt->bind_param("ssi", $old['ho_ten'], $old['chuc_vu'], $luong_val);
            if ($stmt->execute()) {
                header("Location: index.php?msg=added");
                exit;
            } else {
                $errors[] = "Execute failed: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Thêm nhân sự</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
  <div class="container">
    <h2>Thêm nhân sự</h2>

    <?php if (!empty($errors)): ?>
      <div style="background:#ffe6e6;border:1px solid #ffb3b3;padding:12px;border-radius:6px;margin-bottom:12px;color:#a00;">
        <?php foreach ($errors as $e) echo "<div>- ".htmlspecialchars($e)."</div>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="add.php">
      <input type="text" name="ho_ten" placeholder="Họ tên" required value="<?= htmlspecialchars($old['ho_ten']) ?>">
      <input type="text" name="chuc_vu" placeholder="Chức vụ" required value="<?= htmlspecialchars($old['chuc_vu']) ?>">
      <input type="number" step="0.01" name="luong" placeholder="Lương" required value="<?= htmlspecialchars($old['luong']) ?>">
      <button type="submit" class="btn btn-add">Thêm</button>
    </form>

    <br>
    <a href="index.php" class="btn btn-edit">⬅ Quay lại</a>
  </div>
</body>
</html>
