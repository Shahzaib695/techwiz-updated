<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        :root {
  --brand-accent: #6c5ce7;
  --text-color: #f1f1f1;
  --accent: #6c5ce7;
        }
        <style>
  :root {
    --brand-accent: #6c5ce7;
    --text-color: #f1f1f1;
    --accent: #6c5ce7;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  html, body {
    overflow-x: hidden;
    background: #111;
    color: var(--text-color);
    font-family: 'Segoe UI', sans-serif;
    line-height: 1.6;
  }

  /* ===== NAVBAR ===== */
   /* ===== NAVBAR ===== */
    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 999;
      padding: 20px 40px;
      background-color: transparent;
      transition: background-color 0.4s ease, box-shadow 0.4s ease, backdrop-filter 0.4s ease;
    }

  .nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .logo {
    font-size: 26px;
    font-weight: bold;
    color: white;
    text-shadow: 0 0 6px white;
  }

  .logo span {
    color: var(--brand-accent);
    text-shadow: 0 0 6px var(--accent);
  }

  .nav-center {
    flex: 1;
    display: flex;
    justify-content: center;
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 22px;
    flex-wrap: wrap;
  }

  .nav-links a {
    text-decoration: none;
    color: white;
    text-shadow: 0 0 6px white;
    font-size: 20px;
    position: relative;
    transition: 0.3s;
  }

  .nav-links a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 0%;
    height: 2px;
    background-color: var(--brand-accent);
    transition: width 0.3s ease-in-out;
  }

  .nav-links a:hover::after,
  .nav-links a.active::after {
    width: 100%;
  }

  .nav-links a:hover,
  .nav-links a.active {
    color: var(--brand-accent);
  }

  .nav-icons {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .nav-icons i {
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s;
  }

  .nav-icons i:hover {
    color: var(--brand-accent);
  }

  .cart-icon {
    position: relative;
  }

  .cart-badge {
    position: absolute;
    top: -8px;
    right: -10px;
    background: red;
    color: #fff;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
  }

  .hamburger {
    display: none;
    flex-direction: column;
    gap: 4px;
    cursor: pointer;
  }

  .hamburger span {
    height: 2px;
    width: 20px;
    background: white;
    transition: all 0.3s ease;
  }

  .hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }

  .hamburger.active span:nth-child(2) {
    opacity: 0;
  }

  .hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
  }

  @media (max-width: 992px) {
    .nav-links {
      position: absolute;
      top: 70px;
      flex-direction: column;
      background: rgba(0, 0, 0, 0.95);
      padding: 40px 150px;
      border-radius: 10px;
      display: none;
    }

    .nav-links.show {
      display: flex;
    }

    .hamburger {
      display: flex;
    }
  }

    /* ---------------- Sidebars ---------------- */
    .cart-sidebar{position:fixed;top:0;right:-400px;width:360px;height:100vh;background:#fff;box-shadow:-4px 0 20px rgba(0,0,0,.15);padding:24px;transition:right .35s ease;z-index:1000;display:flex;flex-direction:column;} .cart-sidebar.active{right:0;}
    .close-btn{position:absolute;top:12px;right:18px;font-size:22px;color:#aaa;cursor:pointer;transition:.3s;} .close-btn:hover{color:#000;}
    .cart-sidebar h3{margin-bottom:20px;font-size:20px;color:var(--brand-accent);}  
    .cart-item{display:flex;gap:12px;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #eee;} .cart-item img{width:60px;height:60px;border-radius:8px;object-fit:cover;} .cart-item-info h4{font-size:15px;margin:0;} .cart-item-info span{color:var(--brand-accent);font-size:14px;}
    .qty-box button{padding:4px 10px;border:none;background:#eee;margin:0 4px;border-radius:4px;cursor:pointer;} .remove-btn{background:crimson;color:#fff;border:none;border-radius:6px;padding:5px 10px;font-size:14px;cursor:pointer;margin-top:8px;} .add-btn{background:var(--brand-accent);color:#fff;border:none;border-radius:6px;padding:5px 10px;font-size:14px;cursor:pointer;margin-top:8px;margin-left:10px;}
    .cart-total{display:flex;justify-content:space-between;padding:10px 0;font-weight:600;}
    .checkout-btn{background:var(--brand-accent);color:#fff;padding:12px;border:none;border-radius:8px;font-weight:600;cursor:pointer;width:100%;margin-top:10px;} .checkout-btn:hover{background:var(--brand-accent-hover);}  
    /* ---------------- Product grid ---------------- */
    .featured-products{padding:80px 8%;background:#fdfdfd;} .section-title{font-size:42px;text-align:center;font-weight:800;color:var(--text-dark);margin-bottom:50px;position:relative;} .section-title::after{content:'';position:absolute;left:50%;transform:translateX(-50%);bottom:-12px;width:90px;height:4px;background:var(--brand-accent);border-radius:2px;box-shadow:0 0 12px var(--brand-accent);}  
    .product-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:2.5rem;} .product-card{background:#fff;border-radius:22px;box-shadow:0 12px 30px rgba(0,0,0,.08);overflow:hidden;transition:.35s;cursor:pointer;} .product-card:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(0,0,0,.12);}  
    .product-image{position:relative;height:250px;overflow:hidden;} .product-image img{width:100%;height:100%;object-fit:cover;transition:transform .45s ease;} .product-card:hover img{transform:scale(1.08);}  
    .product-badge{position:absolute;top:14px;left:14px;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;color:#fff;background:var(--brand-accent);text-transform:uppercase;box-shadow:0 2px 6px rgba(0,0,0,.2);} .product-badge.hot{background:#ff5c97;} .product-badge.trend{background:#ffb300;} .product-badge.sale{background:#ff7043;} .product-badge.best{background:#26c6da;}  
    .product-info{padding:24px;text-align:center;} .product-info h3{font-size:20px;margin-bottom:8px;color:#222;} .product-price{font-weight:700;color:var(--brand-accent);font-size:17px;margin-bottom:16px;} .buy-btn{background:var(--brand-accent);border:none;padding:10px 24px;border-radius:30px;color:#fff;font-weight:600;font-size:15px;cursor:pointer;transition:.3s;} .buy-btn:hover{background:var(--brand-accent-hover);}  
    /* ---------------- Modal ---------------- */
    .product-modal{display:none;position:fixed;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);justify-content:center;align-items:center;z-index:1001;} .modal-content{background:#fff;padding:30px;border-radius:18px;max-width:500px;width:90%;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,.2);} .modal-content img{width:100%;height:300px;object-fit:cover;border-radius:12px;margin-bottom:20px;} .modal-content h3{font-size:24px;margin-bottom:10px;color:#222;} .modal-content input[type='number']{width:80px;padding:6px 10px;font-size:16px;margin-bottom:20px;border-radius:8px;border:1px solid #ccc;} .modal-content .buy-now{background:var(--brand-accent);color:#fff;border:none;padding:10px 26px;border-radius:30px;font-weight:600;cursor:pointer;}
  </style>
</style>


    </style>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<body>
<!-- ✅ NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <!-- Logo -->
    <div class="logo">FASHION <span>VIBES</span></div>

    <!-- Navigation Links -->
    <div class="nav-center">
      <div class="nav-links" id="navLinks">
        <a href="home.php" class="active">HOME</a>
        <a href="about us.php">ABOUT</a>
        <a href="product.php">SHOP</a>
        <a href="#">PAGES</a>
        <a href="#">BLOG</a>
        <a href="contect.php">CONTACT</a>
      </div>
    </div>

    <!-- Icons -->
    <div class="nav-icons">
      <i class="fas fa-user" onclick="toggleUserSidebar()" title="User Panel"></i>
      <div class="cart-icon" onclick="toggleCart()" title="Your Cart">
        <i class="fas fa-bag-shopping"></i>
        <span class="cart-badge" id="cartCount">0</span>
      </div>
      <div class="hamburger" id="hamburger" onclick="toggleMenu()">
        <span></span><span></span><span></span>
      </div>
    </div>
  </div>
</nav>


<link rel="stylesheet" href="product.css">
  <!-- BANNER -->
  <section class="inner-banner-wrap">
    <div class="inner-banner-box">
      <h1 class="title">PRODUCT</h1>
      <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li>product</li>
      </ul>
    </div>
  </section>
   <!-- ---------- USER SIDEBAR ---------- -->
<div class="cart-sidebar" id="userSidebar">
  <span class="close-btn" onclick="closeSidebars()">&times;</span>
  <h3>User Panel</h3>

  <?php
// start
if (isset($_GET['remove_order']) && is_numeric($_GET['remove_order'])) {
    $orderId = intval($_GET['remove_order']);
    $userEmail = $_SESSION['email'];

    // Delete the order only if it belongs to the logged-in user
    $deleteQuery = "DELETE FROM orders WHERE id = $orderId AND email = '$userEmail'";
    mysqli_query($conn, $deleteQuery);
   echo "<script>alert('Data deleted')
   window.location.href='home.php'</script>";
    exit();
}

// end
  if (isset($_SESSION['email'])) {
      $user_email = $_SESSION['email'];
      echo "<p><strong>Welcome:</strong> $user_email</p>";

      $query = "SELECT * FROM orders WHERE email = '$user_email' ORDER BY id DESC";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
          echo "<h4>Your Orders:</h4>";
          echo "<div style='max-height: 400px; overflow-y: auto;'>";

          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div style='border-bottom: 1px solid rgba(255,255,255,0.1); padding: 10px 0; display: flex; align-items: center; gap: 10px;'>";

              echo "<img src='" . $row['product_image'] . "' style='width: 60px; height: auto; border-radius: 6px;' alt=''>";

              echo "<div style='font-size: 14px; color: #ddd; flex: 1;'>
                      <strong>" . $row['product_name'] . "</strong><br>
                      Qty: " . $row['quantity'] . "<br>
                      Status: <span style='color: #fdd835'>" . $row['status'] . "</span><br>
                      <small style='color: #999;'>Date: " . $row['order_date'] . "</small>
                    </div>";

              echo "<div><a href='?remove_order=" . $row['id'] . "' style='color: red; font-size: 13px;' onclick=\"return confirm('Are you sure you want to delete this order?')\">Remove</a></div>";

              echo "</div>";
          }

          echo "</div>";
      } else {
          echo "<p>No orders found.</p>";
      }
  } else {
      echo "<p>Please login to view your orders.</p>";
  }
  ?>

  <a href="signup.php" class="checkout-btn">Register</a>
  <a href="logout.php" class="checkout-btn">Logout</a>
</div>

<!-- end -->


  <!-- ---------- CART SIDEBAR ---------- -->
  <div class="cart-sidebar" id="cartSidebar">
    <span class="close-btn" onclick="closeSidebars()">&times;</span>
    <h3>Your Bag</h3>
    <div class="cart-items" id="cartItems"></div>
    <div class="cart-footer">
      <div class="cart-total"><span>Total:</span><span id="cartTotal">$0.00</span></div>
    <button class="checkout-btn" onclick="document.getElementById('checkoutModal').style.display = 'flex'">Proceed to Checkout</button>
    </div>
  </div>

  <!-- ---------- PRODUCTS ---------- -->
  <section class="featured-products" id="products">
    <h2 class="section-title">Explore Our Collection</h2>
    <div class="product-grid">
      <div class="product-card">
        <div class="product-image"><img src="images/valvet dress.jpg" alt="Velvet Dress"><span class="product-badge new">New</span></div>
        <div class="product-info"><h3>Velvet Dress</h3><p class="product-price">$120.00</p><button class="buy-btn" onclick="openModal(1)">Buy Now</button></div>
      </div>
      <div class="product-card"><div class="product-image"><img src="images/hand bags.jpg" alt="Classic Handbag"><span class="product-badge hot">Hot</span></div><div class="product-info"><h3>Classic Handbag</h3><p class="product-price">$89.00</p><button class="buy-btn" onclick="openModal(2)">Buy Now</button></div></div>
      <div class="product-card"><div class="product-image"><img src="images/sneaker.jpg" alt="Urban Sneakers"><span class="product-badge trend">Trend</span></div><div class="product-info"><h3>Urban Sneakers</h3><p class="product-price">$99.00</p><button class="buy-btn" onclick="openModal(3)">Buy Now</button></div></div>
      <div class="product-card"><div class="product-image"><img src="images/Denim Jacket.jpg" alt="Denim Jacket"><span class="product-badge sale">Sale</span></div><div class="product-info"><h3>Denim Jacket</h3><p class="product-price">$65.00</p><button class="buy-btn" onclick="openModal(4)">Buy Now</button></div></div>
      <div class="product-card"><div class="product-image"><img src="images/Casual Hoodie.jpg" alt="Casual Hoodie"><span class="product-badge new">New</span></div><div class="product-info"><h3>Casual Hoodie</h3><p class="product-price">$58.00</p><button class="buy-btn" onclick="openModal(5)">Buy Now</button></div></div>
      <div class="product-card"><div class="product-image"><img src="images/Fashion Sunglasses.jpg" alt="Sunglasses"><span class="product-badge best">Best</span></div><div class="product-info"><h3>Fashion Sunglasses</h3><p class="product-price">$39.00</p><button class="buy-btn" onclick="openModal(6)">Buy Now</button></div></div>
    </div>
  </section>

  <!-- ---------- MODALS ---------- -->
  <div id="modal1" class="product-modal"><div class="modal-content"><span class="close" onclick="closeModal(1)">&times;</span><img src="images/valvet dress.jpg"><h3>Velvet Dress</h3><p class="desc">Elegant velvet evening dress with modern tailoring and premium fabric.</p><label>Quantity:</label><input type="number" id="qty1" value="1" min="1"><button class="buy-now" onclick="modalAddToCart(1,'Velvet Dress',120,'images/valvet dress.jpg')">Add to Bag</button></div></div>
  <div id="modal2" class="product-modal"><div class="modal-content"><span class="close" onclick="closeModal(2)">&times;</span><img src="images/hand bags.jpg"><h3>Classic Handbag</h3><p class="desc">Timeless leather handbag with gold hardware.</p><label>Quantity:</label><input type="number" id="qty2" value="1" min="1"><button class="buy-now" onclick="modalAddToCart(2,'Classic Handbag',89,'images/hand bags.jpg')">Add to Bag</button></div></div>
  <div id="modal3" class="product-modal"><div class="modal-content"><span class="close" onclick="closeModal(3)">&times;</span><img src="images/sneaker.jpg"><h3>Urban Sneakers</h3><p class="desc">Comfortable street-style sneakers.</p><label>Quantity:</label><input type="number" id="qty3" value="1" min="1"><button class="buy-now" onclick="modalAddToCart(3,'Urban Sneakers',99,'images/sneaker.jpg')">Add to Bag</button></div></div>
  <div id="modal4" class="product-modal"><div class="modal-content"><span class="close" onclick="closeModal(4)">&times;</span><img src="images/Denim Jacket.jpg"><h3>Denim Jacket</h3><p class="desc">Durable denim jacket for all seasons.</p><label>Quantity:</label><input type="number" id="qty4" value="1" min="1"><button class="buy-now" onclick="modalAddToCart(4,'Denim Jacket',65,'images/Denim Jacket.jpg')">Add to Bag</button></div></div>
  <div id="modal5" class="product-modal"><div class="modal-content"><span class="close" onclick="closeModal(5)">&times;</span><img src="images/Casual Hoodie.jpg"><h3>Casual Hoodie</h3><p class="desc">Soft cotton-blend hoodie.</p><label>Quantity:</label><input type="number" id="qty5" value="1" min="1"><button class="buy-now" onclick="modalAddToCart(5,'Casual Hoodie',58,'images/Casual Hoodie.jpg')">Add to Bag</button></div></div>
  <div id="modal6" class="product-modal"><div class="modal-content"><span class="close" onclick="closeModal(6)">&times;</span><img src="images/Fashion Sunglasses.jpg"><h3>Fashion Sunglasses</h3><p class="desc">UV-protected stylish sunglasses.</p><label>Quantity:</label><input type="number" id="qty6" value="1" min="1"><button class="buy-now" onclick="modalAddToCart(6,'Fashion Sunglasses',39,'images/Fashion Sunglasses.jpg')">Add to Bag</button></div></div>

  <!-- ---------- SCRIPTS ---------- -->
  <script>
    const cartSidebar = document.getElementById('cartSidebar');
    const userSidebar = document.getElementById('userSidebar');
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const cartTotal = document.getElementById('cartTotal');

    let cart = [];

    function toggleCart(){cartSidebar.classList.toggle('active');userSidebar.classList.remove('active');}
    function toggleUserSidebar(){userSidebar.classList.toggle('active');cartSidebar.classList.remove('active');}
    function closeSidebars(){userSidebar.classList.remove('active');cartSidebar.classList.remove('active');}

    function addToCart(name,price,img,qty=1){const existing=cart.find(i=>i.name===name);if(existing){existing.qty+=qty;}else{cart.push({name,price,img,qty});}renderCart();}

    function modalAddToCart(id,name,price,img){const qty=parseInt(document.getElementById('qty'+id).value)||1;addToCart(name,price,img,qty);closeModal(id);cartSidebar.classList.add('active');}

    function removeItem(idx){cart.splice(idx,1);renderCart();}
    function changeQty(idx,delta){cart[idx].qty+=delta;if(cart[idx].qty<1)cart[idx].qty=1;renderCart();}

    function renderCart(){cartItems.innerHTML='';let total=0;cart.forEach((item,idx)=>{total+=item.price*item.qty;const el=document.createElement('div');el.className='cart-item';el.innerHTML=`<img src="${item.img}" alt="${item.name}"/><div class='cart-item-info'><h4>${item.name}</h4><span>$${item.price.toFixed(2)}</span><div class='qty-box'><button onclick='changeQty(${idx},-1)'>-</button><span>${item.qty}</span><button onclick='changeQty(${idx},1)'>+</button></div><button class='remove-btn' onclick='removeItem(${idx})'>Remove</button></div>`;cartItems.appendChild(el);});cartCount.textContent=cart.reduce((a,b)=>a+b.qty,0);cartTotal.textContent=`$${total.toFixed(2)}`;}

    /* ---------- Modal helpers ---------- */
    function openModal(id){document.getElementById('modal'+id).style.display='flex';}
    function closeModal(id){document.getElementById('modal'+id).style.display='none';}
    window.onclick=e=>{[1,2,3,4,5,6].forEach(i=>{const m=document.getElementById('modal'+i);if(e.target===m)m.style.display='none';});};
  </script>
</body>
</html>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --brand-accent: #6c5ce7;
      --brand-accent-hover: #5944d4;
      --text-dark: #222;
      --text-light: #fff;
      --bg-light: #faf9ff;
    }
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
    body{background:var(--bg-light);color:var(--text-dark);}  
    .checkout-form-modal {display:none;position:fixed;top:0;left:0;width:100%;height:100vh;background:rgba(0,0,0,0.6);backdrop-filter:blur(3px);justify-content:center;align-items:center;z-index:1002;}
    .checkout-form {background:#fff;padding:30px;border-radius:16px;width:90%;max-width:480px;box-shadow:0 15px 40px rgba(0,0,0,.2);position:relative;}
    .checkout-form h3 {margin-bottom:20px;font-size:22px;color:var(--brand-accent);text-align:center;}
    .checkout-form label {display:block;margin-bottom:6px;font-weight:600;}
    .checkout-form input, .checkout-form select {width:100%;padding:10px 14px;margin-bottom:20px;border:1px solid #ccc;border-radius:8px;font-size:15px;}
    .checkout-form button {background:var(--brand-accent);color:#fff;border:none;padding:12px;border-radius:8px;font-weight:600;width:100%;cursor:pointer;}
    .checkout-form .close {position:absolute;top:10px;right:20px;font-size:22px;color:#555;cursor:pointer;}
    .checkout-btn {background:var(--brand-accent);color:#fff;padding:12px;border:none;border-radius:8px;font-weight:600;cursor:pointer;width:100%;margin-top:10px;}
    .checkout-btn:hover {background:var(--brand-accent-hover);}
  </style>




  <!-- Checkout Modal -->
  <div class="checkout-form-modal" id="checkoutModal">
    <div class="checkout-form">
      <span class="close" onclick="document.getElementById('checkoutModal').style.display='none'">&times;</span>
      <h3>Checkout Information</h3>
      <label for="username">Full Name</label>
      <input type="text" id="username" placeholder="Enter your name" required>
      <label for="email">Email</label>
      <input type="email" id="email" placeholder="Enter your email" required>
      <label for="payment">Payment Method</label>
      <select id="payment">
        <option value="bank">Bank Account</option>
        <option value="rast">Rast Payment</option>
        <option value="easypaisa">EasyPaisa</option>
        <option value="jazzcash">JazzCash</option>
      </select>
      <button onclick="submitCheckout()">Submit Order</button>
    </div>
  </div>


  <script>
    function submitCheckout() {
  const name = document.getElementById("username").value;
  const email = document.getElementById("email").value;
  const payment = document.getElementById("payment").value;

  if (!name || !email) {
    alert("Please fill all fields");
    return;
  }

  // Send order to PHP backend
  fetch('submit_order.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      name: name,
      email: email,
      payment: payment,
      cart: cart
    })
  })
  .then(res => res.json())
  .then(data => {
    if(data.success){
      alert("Order placed successfully!");
      cart = [];
      renderCart();
      document.getElementById("checkoutModal").style.display = "none";
    } else {
      alert("Error: " + data.message);
    }
  });
}

  </script>


  <!-- FOOTER -->
<footer class="footer-layout1">
  <div class="footer-top-newsletter" data-aos="fade-left">
    <div class="container">
      <div class="newsletter-row">
        <div class="newsletter-col salone-text">ELEAGANCE SALONE</div>
        <div class="newsletter-col">Newsletter</div>
        <div class="newsletter-col"><input type="email" placeholder="Enter email" class="newsletter-input" /></div>
        <div class="newsletter-col"><button class="newsletter-btn">Send</button></div>
      </div>
    </div>
  </div>

  <div class="tlp-border-wrap" data-aos="fade-left" data-aos-delay="100">
    <div class="container"><span class="tlp-border"></span></div>
  </div>

  <div class="footer-middle">
    <div class="container">
      <div class="row">
        <div class="footer-box" data-aos="fade-left" data-aos-delay="200">
          <h3 class="footer-heading">Contact Us</h3>
          <ul class="footer-list">
            <li><i class="fas fa-map-marker-alt"></i> 329 Queensberry Street, CA 559</li>
            <li><i class="fas fa-envelope"></i> <a href="#">admin@ownmail.com</a></li>
            <li><i class="fas fa-phone"></i> <a href="#">+123 456 780 123</a></li>
          </ul>
        </div>

        <div class="footer-box" data-aos="fade-left" data-aos-delay="300">
          <h3 class="footer-heading">Best Services</h3>
          <ul class="footer-list">
            <li><a href="#">Hair Cutting</a></li>
            <li><a href="#">Hair Styling</a></li>
            <li><a href="#">Detan & Bleach</a></li>
            <li><a href="#">Facials</a></li>
            <li><a href="#">Hair Colouring</a></li>
          </ul>
        </div>

        <div class="footer-box" data-aos="fade-left" data-aos-delay="400">
          <h3 class="footer-heading">Our Products</h3>
          <ul class="footer-list">
            <li><a href="#">Shampoo</a></li>
            <li><a href="#">Conditioner</a></li>
            <li><a href="#">Treatment</a></li>
            <li><a href="#">Styling Products</a></li>
            <li><a href="#">Brushes & Combs</a></li>
          </ul>
        </div>

        <div class="footer-box" data-aos="fade-left" data-aos-delay="500">
          <h3 class="footer-heading">Recent Posts</h3>
          <div class="recent-post">
            <img src="https://radiustheme.com/demo/wordpress/themes/salion/wp-content/uploads/2021/04/blog1-75x65.jpg" />
            <div>
              <p class="date">April 8, 2021</p>
              <a href="#">The 90’s are back...</a>
            </div>
          </div>
          <div class="recent-post">
            <img src="https://radiustheme.com/demo/wordpress/themes/salion/wp-content/uploads/2021/04/blog5-75x65.jpg" />
            <div>
              <p class="date">April 8, 2021</p>
              <a href="#">Men’s Fade Haircut...</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bottom" data-aos="fade-left" data-aos-delay="600">
    <div class="container">
      <p class="copy-right-text">© 2025 Salone by RadiusTheme. All Rights Reserved.</p>
    </div>
  </div>
</footer>
</body>
<!-- Before </body> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();
</script>
  </html>


    
</body>
</html>