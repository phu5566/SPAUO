const express = require('express');
const mysql = require('mysql2');
const multer = require('multer');
const path = require('path');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());
app.use('/uploads', express.static('uploads')); // ສ້າງໂຟນເດີ uploads ໄວ້ເກັບຮູບສະລິບ

// 1. ເຊື່ອມຕໍ່ MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '', // ລະຫັດຜ່ານ MySQL ຂອງເຈົ້າ
    database: 'shop_db'
});

db.connect(err => {
    if (err) console.log('Database Connection Error:', err);
    else console.log('Connected to MySQL Database!');
});

// 2. ຕັ້ງຄ່າການ Upload ຮູບສະລິບ
const storage = multer.diskStorage({
    destination: './uploads/',
    filename: (req, file, cb) => {
        cb(null, 'SLIP-' + Date.now() + path.extname(file.originalname));
    }
});
const upload = multer({ storage: storage });

// 3. API ຮັບຂໍ້ມູນການສັ່ງຊື້
app.post('/api/orders', upload.single('slip'), (req, res) => {
    const { customer_name, phone, address, product_list, total_price } = req.body;
    const slip_image = req.file ? req.file.filename : null;

    const sql = `INSERT INTO orders (customer_name, phone, address, product_list, total_price, slip_image) 
                 VALUES (?, ?, ?, ?, ?, ?)`;
    
    db.query(sql, [customer_name, phone, address, product_list, total_price, slip_image], (err, result) => {
        if (err) {
            console.error(err);
            return res.status(500).send({ message: "Error saving order" });
        }
        res.status(200).send({ message: "Order saved successfully!", orderId: result.insertId });
    });
});

app.listen(3000, () => console.log('Server running on port 3000'));

app.listen(3000, () => console.log('Server runs on port 3000'));

async function submitOrder() {
    // ... ດຶງຄ່າຈາກ Form ຄືເກົ່າ ...

   const formData = new FormData();
    formData.append('customer_name', name);
    formData.append('phone', phone);
    formData.append('address', address);
    formData.append('product_list', productNames);
    formData.append('total_price', totalAmount);
    formData.append('slip', slipFile);
  try {
        const response = await fetch('save_order.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert("ສັ່ງຊື້ສຳເລັດ! ຂໍ້ມູນຖືກບັນທຶກລົງ MySQL ແລ້ວ");
            
            // 1. ອັບເດດຕົວເລກແຈ້ງເຕືອນ
            deliveryCount++;
            const badge = document.getElementById('delivery-count-badge');
            if(badge) {
                badge.innerText = deliveryCount;
                badge.style.display = 'inline-block';
            }

            // 2. ລ້າງຂໍ້ມູນກະຕ່າ ແລະ ປິດ Modal
            cart = [];
            updateCartUI();
            closeCheckout();
            
            // 3. (ທາງເລືອກ) ສະແດງລາຍການໃນແຖບກຳລັງຈັດສົ່ງ
            // renderDeliveryList(); 
        } else {
            alert("ເກີດຂໍ້ຜິດພາດ: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("ບໍ່ສາມາດເຊື່ອມຕໍ່ຫາ Server ໄດ້");
    }
}
let deliveryCount = 0; // ຕົວແປເກັບຈຳນວນລາຍການທີ່ກຳລັງຈັດສົ່ງ

async function submitOrder() {
    const name = document.getElementById('custName').value;
    const phone = document.getElementById('custPhone').value;
    const address = document.getElementById('custAddress').value;
    const slipFile = document.getElementById('slipInput').files[0];

    if (!name || !phone || !address || !slipFile) {
        alert("ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບ ແລະ ແນບຮູບສະລິບ");
        return;
    }

    // 1. ກຽມຂໍ້ມູນ
    const productNames = cart.map(i => `${i.name} (x${i.quantity})`).join(", ");
    const totalAmount = document.getElementById('total-price').innerText;

    const formData = new FormData();
    formData.append('customer_name', name);
    formData.append('phone', phone);
    formData.append('address', address);
    formData.append('product_list', productNames);
    formData.append('total_price', totalAmount);
    formData.append('slip', slipFile);

    try {
        // 2. ສົ່ງໄປຫາ PHP
        const response = await fetch('save_order.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert("ສັ່ງຊື້ສຳເລັດ!");

            // 3. ອັບເດດຕົວເລກແຈ້ງເຕືອນຢູ່ເມນູ "ກຳລັງຈັດສົ່ງ"
            deliveryCount++; 
            updateDeliveryBadge();

            // 4. ລ້າງຂໍ້ມູນໃນກະຕ່າ ແລະ ປິດ Modal
            cart = [];
            updateCartUI();
            closeCheckout();
            
            // 5. ປ່ຽນໄປໜ້າ "ກຳລັງຈັດສົ່ງ" ເພື່ອເບິ່ງລາຍການ
            showTab('delivery'); 
        } else {
            alert("ເກີດຂໍ້ຜິດພາດ: " + result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("ເຊື່ອມຕໍ່ Server ບໍ່ໄດ້");
    }
}

// ຟັງຊັນອັບເດດຕົວເລກແຈ້ງເຕືອນ (Badge)
function updateDeliveryBadge() {
    const badge = document.getElementById('delivery-badge');
    if (deliveryCount > 0) {
        badge.innerText = deliveryCount;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}