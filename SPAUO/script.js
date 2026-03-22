// 1. ຂໍ້ມູນສິນຄ້າ
const products = [
    { 
        id: 1, 
        name: 'ESP32 wifi-Bt 30pin', 
        price: 40000, 
        image: 'images/08893_10_kwadrat.jpg' 
    },
    { 
        id: 2, 
        name: 'ໜ້າຈໍໄອໂຟນ ເກຣດດີ', 
        price: 550000, 
        image: 'images/LCDiP__11.webp' 
    },
    { 
        id: 3, 
        name: 'ສາຍສາກໄອໂຟນ ຫົວ Type C', 
        price: 120000, 
        image: 'images/หัวชาร์จและสายชาร์จไอโฟน.jpg' 
    },
    { 
        id: 4, 
        name: 'ສາຍສາກຫຼາຍສາຍ', 
        price: 89000, 
        image: 'images/DSC_01.jpg' 
    },
    {  
        id: 5, 
        name: 'ບີ້ກມືກເຈວສີຟ້າເຂັມ', 
        price: 5000, 
        image: 'images/pid-69420.jpg' 
    }
];

// 2. ຟັງຊັນ Render ສິນຄ້າ
function displayProducts() {
    const grid = document.getElementById('product-grid');
    
    grid.innerHTML = products.map(product => `
        <div class="card">
            <div class="image-box">
                <img src="${product.image}" alt="${product.name}">
            </div>
            <div class="card-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-price">${product.price.toLocaleString()} LAK</p>
                <button class="btn-add" onclick="addToCart(${product.id})">
                    ກົດໃສ່ກະຕ່າ
                </button>
            </div>
        </div>
    `).join('');
}

// 3. ຟັງຊັນກົດໃສ່ກະຕ່າ (ຕົວຢ່າງ)
function addToCart(id) {
    const item = products.find(p => p.id === id);
    alert('ເພີ່ມ ' + item.name + ' ລົງກະຕ່າແລ້ວ!');
}

// ເອີ້ນໃຊ້ຟັງຊັນເມື່ອໂຫຼດໜ້າ
window.onload = displayProducts;

// ເປີດ-ປິດ ເມນູ 3 ຂີດ
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar.style.width === "300px") {
        sidebar.style.width = "0";
    } else {
        sidebar.style.width = "300px";
    }
}

// ເລີ່ມສະແດງຜົນ
displayProducts(products);

let cart = []; // ຕົວແປເກັບຂໍ້ມູນສິນຄ້າທີ່ສັ່ງ

// 1. ຟັງຊັນເພີ່ມສິນຄ້າລົງໃນກະຕ່າ
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    
    // ກວດເບິ່ງວ່າສິນຄ້ານີ້ມີໃນກະຕ່າແລ້ວບໍ່
    const exist = cart.find(item => item.id === productId);
    
    if (exist) {
        exist.quantity += 1;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    
    updateCartUI();
}

// 2. ອັບເດດໜ້າຈໍ (UI) ຂອງກະຕ່າ
function updateCartUI() {
    const cartItemsDiv = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const totalPrice = document.getElementById('total-price');
}  

// ລະບົບຄົ້ນຫາ
function searchProduct() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    const filtered = products.filter(p => p.name.includes(val));
    displayProducts(filtered);
}

    function updateCartUI() {
    // 1. ອັບເດດຕົວເລກ Badge ຢູ່ເທິງ icon ກະຕ່າ
    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.innerText = totalQty;

    // 2. ຖ້າກະຕ່າຫວ່າງ
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = '<p style="text-align:center; color:#888;">ຍັງບໍ່ມີສິນຄ້າໃນກະຕ່າ</p>';
        totalPrice.innerText = "0";
        return;
    }

    // 3. ສະແດງລາຍການສິນຄ້າ ແລະ ຄຳນວນຍອດລວມ
    let total = 0;
    cartItemsDiv.innerHTML = cart.map(item => {
        total += item.price * item.quantity;
        return `
            <div class="cart-item">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <div>
                        <strong>${item.name}</strong><br>
                        <small>${item.price.toLocaleString()} x ${item.quantity}</small>
                    </div>
                    <div>${(item.price * item.quantity).toLocaleString()} LAK</div>
                </div>
            </div>
        `;
    }).join('');

    // ໂຊຍອດລວມ
    totalPrice.innerText = total.toLocaleString();
}

// 3. ປ່ຽນປຸ່ມໃນ grid ສິນຄ້າໃຫ້ກົດສັ່ງໄດ້
function displayProducts(data) {
    const container = document.getElementById('productDisplay');
    container.innerHTML = data.map(item => `
        <div class="card">
            <h4>${item.name}</h4>
            <p style="color: #ff4757;">${item.price.toLocaleString()} LAK</p>
            <button onclick="addToCart(${item.id})" style="margin-top:10px; padding:8px 15px; cursor:pointer; background:#333; color:white; border-radius:5px;">ສັ່ງສິນຄ້າ</button>
        </div>
    `).join('');
}

// ສະແດງສິນຄ້າໜ້າຮ້ານ
function displayProducts(data) {
    const container = document.getElementById('productDisplay');
    container.innerHTML = data.map(item => `
        <div class="card">
            <img src="${item.image}" alt="${item.name}">
            <h4>${item.name}</h4>
            <p class="desc">${item.desc}</p>
            <p style="color: #ff4757; font-weight: bold;">${item.price.toLocaleString()} LAK</p>
            <button onclick="addToCart(${item.id})" style="margin-top:10px; width:100%; padding:10px; cursor:pointer; background:#333; color:white; border:none; border-radius:5px;">ສັ່ງສິນຄ້າ</button>
        </div>
    `).join('');
}

// ຟັງຊັນປ່ຽນຈຳນວນໃນກະຕ່າ
function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.quantity += delta;
        if (item.quantity <= 0) {
            cart = cart.filter(i => i.id !== id);
        }
        updateCartUI();
    }
}

// ອັບເດດ UI ຂອງກະຕ່າ (Sidebar)
function updateCartUI() {
    const cartItemsDiv = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const totalPrice = document.getElementById('total-price');
    
    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.innerText = totalQty;

    if (cart.length === 0) {
        cartItemsDiv.innerHTML = '<p style="text-align:center; color:#888; padding:20px;">ຍັງບໍ່ມີສິນຄ້າ</p>';
        totalPrice.innerText = "0";
        return;
    }

    let total = 0;
    cartItemsDiv.innerHTML = cart.map(item => {
        total += item.price * item.quantity;
        return `
            <div class="cart-item">
                <div style="flex:1">
                    <strong>${item.name}</strong><br>
                    <small>${item.price.toLocaleString()} LAK</small>
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <button class="qty-btn" onclick="changeQty(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
                </div>
            </div>
        `;
    }).join('');

    totalPrice.innerText = total.toLocaleString();
}

function processCheckout() {
    // ກວດສອບເງື່ອນໄຂ (ຕ້ອງມີສິນຄ້າໃນ cart ຢ່າງໜ້ອຍ 1 ລາຍການ)
    if (cart.length > 0) {
        // ເກັບຂໍ້ມູນລົງ localStorage ກ່ອນໄປໜ້າໃໝ່ ເພື່ອໃຫ້ checkout.php ດຶງໄປໃຊ້ໄດ້
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // ໄປໜ້າຊຳລະເງິນ
        window.location.href = 'checkout.php';
    } else {
        alert("ກະລຸນາເລືອກສິນຄ້າລົງກະຕ່າກ່ອນ!");
    }
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

// ດຶງຂໍ້ມູນສິນຄ້າຈາກ API
async function loadProducts() {
    try {
        const response = await fetch('api/get_products.php');
        const products = await response.json();
        renderProducts(products); // ສົ່ງໄປໂຊຢູ່ໜ້າເວັບ
    } catch (error) {
        console.error("Error loading products:", error);
    }
}

// ສົ່ງຂໍ້ມູນສັ່ງຊື້ໄປ API
async function submitOrder(orderData) {
    const response = await fetch('api/save_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    });
    const result = await response.json();
    if(result.status === 'success') alert("ສັ່ງຊື້ສຳເລັດ!");
}