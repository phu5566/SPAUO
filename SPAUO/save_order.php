<?php
// 1. ເຊື່ອມຕໍ່ຖານຂໍ້ມູນ
$conn = new mysqli("localhost", "root", "", "shop_db");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("ເຊື່ອມຕໍ່ຖານຂໍ້ມູນບໍ່ສຳເລັດ: " . $conn->connect_error);
}

// --- ຟັງຊັນສົ່ງ Messaging API (ສົ່ງທັງຂໍ້ຄວາມ ແລະ ຮູບພາບ) ---
function sendLineMultiMessage($text, $imageUrl, $token) {
    $url = 'https://api.line.me/v2/bot/message/broadcast';
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ];
    
    // ສ້າງ Array ຂໍ້ຄວາມ ແລະ ຮູບພາບ
    $messages = [
        [
            'type' => 'text',
            'text' => $text
        ]
    ];

    // ຖ້າມີ URL ຮູບ, ໃຫ້ເພີ່ມເຂົ້າໄປໃນການສົ່ງ
    if ($imageUrl) {
        $messages[] = [
            'type' => 'image',
            'originalContentUrl' => $imageUrl,
            'previewImageUrl' => $imageUrl
        ];
    }

    $data = ['messages' => $messages];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. ຮັບຂໍ້ມູນຈາກ Form
    $name = mysqli_real_escape_string($conn, $_POST['customer_name'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $total = (float)($_POST['total_amount_hidden'] ?? 0);
    
    // ແປງ JSON ລາຍການສິນຄ້າໃຫ້ເປັນຂໍ້ຄວາມທີ່ອ່ານງ່າຍ
    $items_raw = $_POST['items_json'] ?? '[]';
    $items_array = json_decode($items_raw, true);
    $item_list_text = "";
    if (is_array($items_array)) {
        foreach ($items_array as $index => $item) {
            $item_list_text .= ($index + 1) . ". " . $item['name'] . " (x" . $item['quantity'] . ")\n";
        }
    }

    // 3. ຈັດການເລື່ອງການອັບໂຫລດຮູບສະລິບ
    $upload_dir = "uploads/";
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }

    $file_ext = pathinfo($_FILES['slip_image']['name'], PATHINFO_EXTENSION);
    $file_name = time() . "_" . uniqid() . "." . $file_ext;
    $target_file = $upload_dir . $file_name;

    // ສ້າງ URL ເຕັມຂອງຮູບ (ສຳຄັນ: LINE ຕ້ອງໃຊ້ Link ທີ່ເປັນ https ເທົ່ານັ້ນ)
    // ປ່ຽນ 'yourdomain.com' ເປັນ Domain ຂອງເຈົ້າ ຫຼື IP ຂອງເຄື່ອງ
    $full_image_url = "https://yourdomain.com/SPAUO/" . $target_file; 

    if (move_uploaded_file($_FILES['slip_image']['tmp_name'], $target_file)) {
        
        $items_db = mysqli_real_escape_string($conn, $items_raw);
        $sql = "INSERT INTO orders (customer_name, phone, address, total_amount, order_items, slip_url) 
                VALUES ('$name', '$phone', '$address', '$total', '$items_db', '$target_file')";

        if ($conn->query($sql) === TRUE) {
            
            // --- 4. ກຽມຂໍ້ຄວາມສົ່ງຫາ LINE ---
            $channel_access_token = "LAe+UcBfKNwdt2+HDkKFGRbVw6smuObK1uHu9pVWZhlHEFBaOF98JNLzPGCdPzdidIYOu5qDXbikrla7wBDSH7IKoBS+49ICqIdqjiQ6eiPhD91RS9f6L3HYJrIxFo+YTl8GbNWiKpnSvPW3OcHKBAdB04t89/1O/w1cDnyilFU="; 
            
            $msg = "🔔 ມີການສັ່ງຊື້ໃໝ່!\n";
            $msg .= "👤 ລູກຄ້າ: $name\n";
            $msg .= "📞 ເບີໂທ: $phone\n";
            $msg .= "📍 ທີ່ຢູ່: $address\n";
            $msg .= "------------------\n";
            $msg .= "📦 ລາຍການສິນຄ້າ:\n$item_list_text";
            $msg .= "------------------\n";
            $msg .= "💰 ຍອດລວມ: " . number_format($total) . " LAK";

            // ສົ່ງທັງຂໍ້ຄວາມ ແລະ ຮູບພາບ
            sendLineMultiMessage($msg, $full_image_url, $channel_access_token);

            echo "<script>
                    alert('ສັ່ງຊື້ສຳເລັດ!');
                    localStorage.removeItem('myCart');
                    window.location.href = 'index.php';
                  </script>";
        }
    }
}
$conn->close();

$_SESSION['last_order_id'] = $conn->insert_id;
$order_id = $_SESSION['last_order_id'];
$sql = "SELECT * FROM orders WHERE id = '$order_id'";
?>