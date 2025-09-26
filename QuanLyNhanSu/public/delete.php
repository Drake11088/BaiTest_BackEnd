<?php
// delete.php (safe)
include 'db.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

// nếu request GET: show confirm page
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        header('Location: index.php?msg=invalid_id');
        exit;
    }
    // lấy tên để hiển thị
    $stmt = $conn->prepare("SELECT ho_ten FROM nhanvien WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        header('Location: index.php?msg=not_found');
        exit;
    }
    $row = $res->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>Xóa</title><link rel="stylesheet" href="style.css"></head>
    <body>
    <div class="container">
      <h2>Xóa nhân sự</h2>
      <p>Bạn có chắc muốn xóa: <strong><?=htmlspecialchars($row['ho_ten'])?></strong> (ID: <?=$id?>) ?</p>
      <form method="post" action="delete.php">
        <input type="hidden" name="id" value="<?=$id?>">
        <button type="submit" name="confirm" class="btn btn-delete">Xác nhận xóa</button>
        <a href="index.php" class="btn btn-edit">Hủy</a>
      </form>
    </div>
    </body></html>
    <?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) { header('Location: index.php?msg=invalid_id'); exit; }

    $stmt = $conn->prepare("DELETE FROM nhanvien WHERE id = ?");
    if (!$stmt) { die("Prepare failed: " . $conn->error); }
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        
        die("Delete failed: " . $stmt->error);
    }
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        header("Location: index.php?msg=not_found");
        exit;
    }
}
