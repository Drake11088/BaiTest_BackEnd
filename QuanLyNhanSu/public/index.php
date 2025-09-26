<?php
include_once './../includes/db.php';
$res = $conn->query("SELECT * FROM nhanvien ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý nhân sự</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      padding: 20px;
    }
    h2 {
      color: #333;
    }
    a.btn {
      display: inline-block;
      padding: 8px 12px;
      margin-bottom: 10px;
      background: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-size: 14px;
    }
    a.btn:hover {
      background: #45a049;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #f2f2f2;
    }
    td a {
      padding: 5px 8px;
      margin-right: 4px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 13px;
    }
    td a.edit {
      background: #2196F3;
      color: white;
    }
    td a.edit:hover {
      background: #1976D2;
    }
    td a.delete {
      background: #f44336;
      color: white;
    }
    td a.delete:hover {
      background: #d32f2f;
    }
  </style>
</head>
<body>
  <h2>Danh sách nhân viên</h2>
  <a href="add.php" class="btn">+ Thêm nhân viên</a>
  <table>
    <tr>
      <th>ID</th>
      <th>Họ tên</th>
      <th>Chức vụ</th>
      <th>Lương</th>
      <th>Hành động</th>
    </tr>
    <?php while($r = $res->fetch_assoc()): ?>
    <tr>
      <td><?=$r['id']?></td>
      <td><?=htmlspecialchars($r['ho_ten'])?></td>
      <td><?=htmlspecialchars($r['chuc_vu'])?></td>
      <td><?=number_format($r['luong'], 0, ',', '.') ?> đ</td>
      <td>
        <a href="edit.php?id=<?=$r['id']?>" class="edit">Sửa</a>
        <a href="delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Xóa nhân viên này?')">Xóa</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
