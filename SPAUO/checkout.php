<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຊຳລະເງິນ - Checkout</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Phetsarath OT', sans-serif; }
        body { background: #f4f7f6; padding: 15px; }
        
        .checkout-container { max-width: 500px; margin: auto; background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }

        /* ລາຍການສິນຄ້າ */
        .order-summary { border-bottom: 2px dashed #ddd; padding-bottom: 15px; margin-bottom: 15px; }
        .item-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 8px; }
        .item-row img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .item-info { flex: 1; }
        .item-info h4 { font-size: 14px; }
        .item-price { font-weight: bold; color: #e74c3c; }

        /* ສ່ວນຍອດລວມ ແລະ QR */
        .total-box { text-align: center; margin: 20px 0; background: #fff9f0; padding: 15px; border-radius: 10px; border: 1px solid #ffeaa7; }
        .total-box h3 { color: #d35400; font-size: 22px; }
        .qr-code { width: 180px; margin: 10px auto; display: block; border: 5px solid #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

        /* ຟອມປ້ອນຂໍ້ມູນ */
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-size: 14px; }
        .input-group input, .input-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; }
        .input-group input[readonly] { background: #eee; }

        .btn-confirm { width: 100%; padding: 15px; background: #27ae60; color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-confirm:hover { background: #219150; }

        @media (max-width: 480px) {
            .checkout-container { border-radius: 0; width: 100%; }
        }

        /* ບ່ອນເກັບ Input ອັບໂຫລດ */
.input-upload-container {
    border: 2px dashed #ccc;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    background: #fafafa;
    cursor: pointer;
    transition: all 0.3s ease; /* ໃສ່ Transition ຕາມຮູບທີ່ເຈົ້າຢາກໄດ້ */
    position: relative;
    margin-top: 10px;
}

.input-upload-container:hover {
    border-color: #27ae60;
    background: #f0fdf4;
}

/* Icon ບວກ */
.upload-icon {
    font-size: 30px;
    color: #999;
    margin-bottom: 8px;
    display: block;
}

.input-upload-container p {
    color: #666;
    font-size: 14px;
    margin: 0;
}

/* ຮູບ Preview */
#slipPreview {
    width: 100%;
    max-height: 250px;
    object-fit: contain;
    margin-top: 10px;
    display: none;
    border-radius: 8px;
}
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>ຢືນຢັນການສັ່ງຊື້</h2>

    <div id="orderItems" class="order-summary">
    </div>

    <div class="total-box">
        <p>ຍອດລວມທັງໝົດ:</p>
        <h3 id="displayTotal">0 LAK</h3>
        <img src="images/64251575-ae98-47db-8e35-437a4cef6be0.jpg" alt="Scan to Pay" class="qr-code">
        <p style="font-size: 12px; color: #666;">ສະແກນຄີວອາເພື່ອຊຳລະເງິນ</p>
    </div>

    <form action="save_order.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="items_json" id="itemsJson">
    <input type="hidden" name="total_amount_hidden" id="totalAmountHidden">
        
        <div class="input-group">
            <label>ຊື່ ແລະ ນາມສະກຸນ</label>
            <input type="text" name="customer_name" placeholder="ປ້ອນຊື່ຂອງທ່ານ" required>
        </div>

        <div class="input-group">
            <label>ເບີໂທຕິດຕໍ່</label>
            <input type="tel" name="phone" placeholder="020 XXXX XXXX" required>
        </div>

        <div class="input-group">
            <label>ລາຄາລວມ (LAK)</label>
            <input type="text" name="total_amount" id="inputTotal" readonly>
        </div>

        <div class="input-group">
            <label>ທີ່ຢູ່ຈັດສົ່ງ</label>
            <textarea name="address" rows="3" placeholder="ບ້ານ, ເມືອງ, ແຂວງ..." required></textarea>
        </div>

        <div class="input-group">
    <label>ແນບຮູບສະລິບການໂອນ:</label>
    
    <div class="input-upload-container" onclick="document.getElementById('slipInput').click()">
    <span class="upload-icon">+</span>
    <p id="uploadText">ກົດເພື່ອເລືອກຮູບສະລິບ</p>
    
    <input type="file" name="slip_image" id="slipInput" accept="image/*" hidden onchange="handlePreview(this)">
    
    <img id="slipPreview" src="" alt="Slip Preview" style="display: none; width: 100%; max-height: 250px; object-fit: contain; border-radius: 8px;">
</div>
</div>

        <button type="submit" class="btn-confirm">ຢືນຢັນການຊຳລະ</button>
    </form>
</div>



<script>
   document.addEventListener('DOMContentLoaded', function() {
    // 1. ດຶງຂໍ້ມູນ (ກວດເບິ່ງໜ້າ index ວ່າເຈົ້າໃຊ້ຊື່ 'cart' ຫຼື 'myCart')
    const cart = JSON.parse(localStorage.getItem('cart')) || []; 
    const orderItemsDiv = document.getElementById('orderItems');
    const displayTotal = document.getElementById('displayTotal');
    const inputTotal = document.getElementById('inputTotal');
    const itemsJson = document.getElementById('itemsJson');

    let total = 0;

    if (cart.length > 0) {
        let html = '';
        cart.forEach(item => {
            // ຄຳນວນຍອດລວມ (ລາຄາ x ຈຳນວນ)
            total += Number(item.price) * Number(item.quantity);
            
            html += `
                <div class="item-row" style="display:flex; justify-content:space-between; margin-bottom:10px;">
                    <span>${item.name} (x${item.quantity})</span>
                    <span>${(item.price * item.quantity).toLocaleString()} LAK</span>
                </div>
            `;
        });

        // 2. ສະແດງຜົນໃນໜ້າເວັບ
        orderItemsDiv.innerHTML = html;
        displayTotal.innerText = total.toLocaleString() + " LAK";
        
        // 3. ເອົາຄ່າໃສ່ Input ເພື່ອສົ່ງໄປ PHP
        inputTotal.value = total;
        itemsJson.value = JSON.stringify(cart); 

    } else {
        orderItemsDiv.innerHTML = "<p>ບໍ່ມີສິນຄ້າໃນກະຕ່າ</p>";
    }

    function handlePreview(input) {
    const preview = document.getElementById('slipPreview');
    const uploadText = document.getElementById('uploadText');
    const icon = document.querySelector('.upload-icon');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block'; // ສະແດງຮູບ
            
            // ເຊື່ອງ icon ແລະ ປ່ຽນຂໍ້ຄວາມ
            icon.style.display = 'none';
            uploadText.innerText = "ປ່ຽນຮູບໃໝ່";
            uploadText.style.color = "#27ae60";
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
});
</script>

</body>
</html>