<?php
$conn = new mysqli("localhost", "root", "", "shop_db");
$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>