<?php
header("Content-Type: application/json");
include 'get_orders.php';

// ຮັບຂໍ້ມູນທີ່ສົ່ງມາແບບ JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $customer_name = $conn->real_escape_string($data['name']);
    $phone = $conn->real_escape_string($data['phone']);
    $total = $data['total'];
    $items = json_encode($data['items'], JSON_UNESCAPED_UNICODE);

    $sql = "INSERT INTO orders (customer_name, phone, total_amount, order_items) 
            VALUES ('$customer_name', '$phone', '$total', '$items')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "ບັນທຶກສຳເລັດ"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>