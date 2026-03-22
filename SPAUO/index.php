<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUPARU SHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stand.css">
</head>
<body>
     <nav class="navbar">
        <div class="nav-container">
            <div class="shop-name">
                <h1>SPU<span> SHOP</span></h1>
            </div>

            <div class="search-box">
                <input type="text" id="searchInput" placeholder="ຄົ້ນຫາສິນຄ້າທີ່ນີ້..." onkeyup="searchProduct()">
                <button><i class="fas fa-search"></i></button>
            </div>

            <div id="productContainer" class="product-list-container">
    </div>

            <div id="product-grid" class="product-grid"></div>
            
            <div class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
                <span id="cart-count" class="badge">0</span>
            </div>
        </div>

    </nav>


<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>ລາຍການທີ່ສັ່ງ</h3>
        <span onclick="toggleSidebar()" style="cursor:pointer; font-size: 25px;">&times;</span>
    </div>
    <div class="sidebar-content">
        <div id="cart-items">
            <p style="text-align:center; color:#888;">ຍັງບໍ່ມີສິນຄ້າໃນກະຕ່າ</p>
        </div>
        <hr>
        <div class="cart-summary">
            <h4>ລວມທັງໝົດ: <span id="total-price">0</span> ກີບ</h4>
            <button class="btn-checkout" onclick="processCheckout()">ຊຳລະເງິນ</button>
            <div id="checkoutModal" class="modal">
    <div class="modal-content checkout-card">
        <span class="close" onclick="closeCheckout()">&times;</span>
        
        <div id="render-checkout-area"></div>
    </div>
</div>
    </div>
</div>
      <div style="text-align: center; margin-top: 20px;">
    <a href="track_order.php" class="btn-track">
        <i class="fas fa-truck-moving"></i> ເບິ່ງລາຍການກຳລັງຈັດສົ່ງ
    </a>
</div>
            </div>

        </div>
</div>
    </div>
</div>

    <main class="container">
        <div class="product-grid" id="productDisplay">
            </div>
    </main>

    <script src="script.js"></script>
</body>
</html>