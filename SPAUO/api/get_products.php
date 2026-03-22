<?php
header("Content-Type: application/json");
include 'get_orders.php'; // ໄຟລ໌ເຊື່ອມຕໍ່ຖານຂໍ້ມູນ

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = [
            "id" => (int)$row['id'],
            "name" => $row['name'],
            "price" => (float)$row['price'],
            "image" => $row['image'],
            "description" => $row['description'] // ດຶງ description ມາພ້ອມເພື່ອແກ້ undefined
        ];
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
?>