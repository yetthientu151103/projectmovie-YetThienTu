<?php
include ('connect.php');
try {
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Truy vấn cơ sở dữ liệu để lấy các tùy chọn
$stmt = $conn->query('SELECT `id`, `name` FROM `moviecategory`');

// Thêm các tùy chọn vào thẻ select
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
}
} catch (PDOException $e) {
echo 'Kết nối đến cơ sở dữ liệu thất bại: ' . $e->getMessage();
}
?>