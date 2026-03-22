<?php
// ເຊື່ອມຕໍ່ຖານຂໍ້ມູນ
$conn = new mysqli("localhost", "root", "", "shop_db");
$conn->set_charset("utf8");

// ດຶງຂໍ້ມູນອໍເດີລ້າສຸດ (ຫຼື ດຶງຕາມເບີໂທ/ID ທີ່ລູກຄ້າສັ່ງ)
$sql = "SELECT * FROM orders ORDER BY id DESC"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ລາຍການກຳລັງຈັດສົ່ງ</title>
    <style>
        .order-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .status-pending { color: orange; font-weight: bold; }
        .slip-img { width: 100px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>🚚 ເບິ່ງລາຍການກຳລັງຈັດສົ່ງ</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="order-card">
                <p><strong>ລະຫັດອໍເດີ:</strong> #<?php echo $row['id']; ?></p>
                <p><strong>ຊື່ລູກຄ້າ:</strong> <?php echo $row['customer_name']; ?></p>
                <p><strong>ລາຍການ:</strong> 
                    <?php 
                        $items = json_decode($row['order_items'], true);
                        foreach($items as $item) {
                            echo "<br>- " . $item['name'] . " (x" . $item['quantity'] . ")";
                        }
                    ?>
                </p>
                <p><strong>ຍອດລວມ:</strong> <?php echo number_format($row['total_amount']); ?> LAK</p>
                <p><strong>ສະຖານະ:</strong> <span class="status-pending">ກຳລັງກວດສອບ/ຈັດສົ່ງ</span></p>
                <p><strong>ຮູບສະລິບ:</strong><br>
                    <img src="<?php echo $row['slip_url']; ?>" class="slip-img" onclick="window.open(this.src)">
                </p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>ບໍ່ມີລາຍການຈັດສົ່ງໃນເວລານີ້.</p>
    <?php endif; ?>

    <a href="index.php">ກັບຄືນໜ້າຫຼັກ</a>
</body>
</html>