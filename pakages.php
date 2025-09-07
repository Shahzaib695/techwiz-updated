
<?php
// echo "Welcome Farrukh";
session_start();
include 'db.php';

if(!isset($_SESSION['email'])){
    header('location:login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="loader.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="shop.CSs">
    
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- AOS Animation -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />

    <title>Document</title>
    <style>
:root {
  --brand-accent: #d4a373;  /* warm light brown */
  --text-color: #f1f1f1;
  --muted: #d3c6b5;
}

/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Base */
html, body {
  overflow-x: hidden;
  background-color: #000000ff;
  color: var(--text-color);
  font-family: 'Segoe UI', sans-serif;
  line-height: 1.6;
}

/* Navbar */
.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 999;
  padding: 20px 40px;
  background-color: transparent;
  transition: background-color 0.4s ease,
              box-shadow 0.4s ease,
              backdrop-filter 0.4s ease;
}

/* Scroll state */
.navbar.scrolled {
  background-color: rgba(17, 17, 17, 0.95);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(10px);
}

/* Navbar container */
.nav-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

/* Logo */
.logo img {
  max-width: 400px;
  height: 100px;
  border-radius: 50%;
  padding: 5px;
}

/* Centered navigation */
.nav-center {
  flex: 1;
  display: flex;
  justify-content: center;
}

/* Nav links */
.nav-links {
  display: flex;
  align-items: center;
  gap: 22px;
  flex-wrap: wrap;
}

.nav-links a {
  text-decoration: none;
  color: white;
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
  text-shadow: 0 0 6px rgba(212, 163, 115, 0.6);
}

/* Icons */
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

/* Cart badge */
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

/* Hamburger menu */
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

/* Hamburger active animation */
.hamburger.active span:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.hamburger.active span:nth-child(2) {
  opacity: 0;
}

.hamburger.active span:nth-child(3) {
  transform: rotate(-45deg) translate(5px, -5px);
}

/* Responsive */
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

</style>
<!-- navbar and logo set end -->
<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <div class="logo">
      <img src="decorevistaimages/logo.png" alt="Salone Logo" />
    </div>
    <div class="nav-center">
      <div class="nav-links" id="navLinks">
        <a href="home.php" class="active">HOME</a>
        <a href="about us.php">ABOUT</a>
        <a href="product.php">PRODUCT</a>
        <a href="PRICEING.PHP">PRICING</a>
        <a href="service.PHP">SERVICE</a>
        <a href="blog.php">BLOG</a>
        <a href="contect.php">CONTACT</a>
        <a href="Detail.php">APPOINTMENT</a>
        <a href="pakages.php">DEALS</a>
      </div>
    </div>
    <div class="nav-icons">
      <i class="fas fa-user" onclick="toggleUserSidebar()" title="User Panel"></i>
      <i class="fas fa-file-invoice" onclick="toggleAppointmentSidebar()" title="Appointment Bills"></i>
      <div class="cart-icon" onclick="toggleCart()" title="Your Cart">
        <i class="fas fa-bag-shopping"></i>
        <span class="cart-badge" id="cartCount">0</span>
      </div>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
    </div>
  </div>
</nav>

<!-- AOS Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();
  const hamburger = document.getElementById("hamburger");
  const navLinks = document.getElementById("navLinks");
  hamburger.addEventListener("click", () => {
    navLinks.classList.toggle("show");
    hamburger.classList.toggle("active");
  });
  window.addEventListener("scroll", function () {
    document.getElementById("navbar").classList.toggle("scrolled", window.scrollY > 50);
  });
</script>

<!-- USER SIDEBAR -->
<div class="cart-sidebar" id="userSidebar">
  <span class="close-btn" onclick="closeSidebars()">&times;</span>
  <h3>User Panel</h3>

  <?php
  if (isset($_GET['remove_order']) && is_numeric($_GET['remove_order'])) {
      $orderId = intval($_GET['remove_order']);
      $userEmail = $_SESSION['email'];
      $deleteQuery = "DELETE FROM orders WHERE id = $orderId AND email = '$userEmail'";
      mysqli_query($conn, $deleteQuery);
      echo "<script>alert('Data deleted'); window.location.href='home.php'</script>";
      exit();
  }

  if (isset($_SESSION['email'])) {
      $user_email = $_SESSION['email'];
      echo "<p><strong>Welcome:</strong> $user_email</p>";
      $query = "SELECT * FROM orders WHERE email = '$user_email' ORDER BY id DESC";
      $result = mysqli_query($conn, $query);
      if (mysqli_num_rows($result) > 0) {
          echo "<h4>Your Orders:</h4><div style='max-height: 400px; overflow-y: auto;'>";
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div style='border-bottom: 1px solid rgba(255,255,255,0.1);padding:10px 0;display:flex;align-items:center;gap:10px;'>";
              echo "<img src='" . $row['product_image'] . "' style='width:60px;border-radius:6px;' alt=''>";
              echo "<div style='font-size:14px;color:#ddd;flex:1;'>
                      <strong>" . $row['product_name'] . "</strong><br>
                      Qty: " . $row['quantity'] . "<br>
                      Status: <span style='color:#fdd835'>" . $row['status'] . "</span><br>
                      <small style='color:#999;'>Date: " . $row['order_date'] . "</small>
                    </div>";
              echo "<a href='?remove_order=" . $row['id'] . "' style='color:red;font-size:13px;' onclick=\"return confirm('Are you sure you want to delete this order?')\">Remove</a>";
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

<!-- CART SIDEBAR -->
<!-- <div class="cart-sidebar" id="cartSidebar">
  <span class="close-btn" onclick="closeSidebars()">&times;</span>
  <h3>Your Bag</h3>
  <div class="cart-items" id="cartItems"></div>
  <div class="cart-footer">
    <div class="cart-total"><span>Total:</span><span id="cartTotal">$0.00</span></div>
    <button class="checkout-btn" onclick="document.getElementById('checkoutModal').style.display='flex'">Proceed to Checkout</button>
  </div>
</div> -->

  <!-- ---------- CART SIDEBAR ---------- -->
  <div class="cart-sidebar" id="cartSidebar">
    <span class="close-btn" onclick="closeSidebars()">&times;</span>
    <h3>Your Bag</h3>
    <div class="cart-items" id="cartItems"></div>
    <!-- ✅ CART SIDEBAR BUTTON -->
<div class="cart-footer">
  <div class="cart-total"><span>Total:</span><span id="cartTotal">$0.00</span></div>
  <button type="button" class="checkout-btn" onclick="document.getElementById('checkoutModal').style.display = 'flex'">Proceed to Checkout</button>
</div>

  </div>


    <!-- BANNER -->
  <section class="inner-banner-wrap">
    <div class="inner-banner-box">
      <h1 class="title">DEALS</h1>
      <ul class="breadcrumbs">
        <li><a href="HOME.PHP">Home</a></li>
        <li>DEALS</li>
      </ul>
    </div>
  </section>
  <!-- BANNER end -->


  

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





<script>
  function toggleAppointmentSidebar() {
  document.getElementById('appointmentSidebar').classList.toggle('active');
  cartSidebar.classList.remove('active');
  userSidebar.classList.remove('active');
}

function closeSidebars() {
  cartSidebar.classList.remove('active');
  userSidebar.classList.remove('active');
  document.getElementById('appointmentSidebar').classList.remove('active');
}

</script>


<style>
  /* check out modal  */

.checkout-form-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(4px);
  justify-content: center;
  align-items: center;
  z-index: 1002;
}

.checkout-form {
  background: #111;
  padding: 30px;
  border-radius: 20px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 0 25px rgba(108, 92, 231, 0.5);
  position: relative;
  border: 1px solid var(--brand-accent);
  color: var(--text-light);
  text-align: center;
}

.checkout-form h3 {
  margin-bottom: 20px;
  font-size: 26px;
  color: var(--brand-accent);
  text-shadow: 0 0 6px var(--brand-accent);
  font-weight: 600;
}

.checkout-form label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: #fff;
  text-shadow: 0 0 2px #fff;
  text-align: left;
}

.checkout-form input,
.checkout-form select {
  width: 100%;
  padding: 12px 14px;
  margin-bottom: 20px;
  border: 1px solid #444;
  border-radius: 10px;
  background: #222;
  color: #fff;
  font-size: 15px;
  transition: border-color 0.3s;
}

.checkout-form input:focus,
.checkout-form select:focus {
  outline: none;
  border-color: var(--brand-accent);
  box-shadow: 0 0 6px var(--brand-accent);
}

.checkout-form button {
  background: var(--brand-accent);
  color: #fff;
  border: none;
  padding: 14px;
  border-radius: 30px;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  width: 100%;
  transition: background 0.3s ease, transform 0.2s ease;
  box-shadow: 0 0 10px var(--brand-accent);
}

.checkout-form button:hover {
  background: var(--brand-accent-hover);
  transform: scale(1.03);
  box-shadow: 0 0 12px var(--brand-accent-hover);
}

.checkout-form .close {
  position: absolute;
  top: 14px;
  right: 18px;
  font-size: 24px;
  color: #aaa;
  cursor: pointer;
  transition: 0.2s ease;
  text-shadow: 0 0 3px #fff;
}

.checkout-form .close:hover {
  color: #fff;
  transform: scale(1.2);
}

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


<!-- APPOINTMENT BILLS SIDEBAR -->
<div class="cart-sidebar" id="appointmentSidebar">
  <span class="close-btn" onclick="closeSidebars()">&times;</span>
  <h3>🧾 Appointment Bills</h3>
  <?php
  $apptQuery = "SELECT * FROM appointments ORDER BY id DESC LIMIT 10";
  $apptResult = mysqli_query($conn, $apptQuery);
  if (mysqli_num_rows($apptResult) > 0) {
      echo "<div style='max-height:400px;overflow-y:auto;'>";
      while ($row = mysqli_fetch_assoc($apptResult)) {
          echo "<div style='border-bottom:1px solid rgba(255,255,255,0.1);padding:10px 0;font-size:14px;color:#eee;'>";
          echo "<strong>Client:</strong> " . htmlspecialchars($row['client_name']) . "<br>";
          echo "<strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "<br>";
          echo "<strong>Employee:</strong> " . htmlspecialchars($row['employee_name']) . "<br>";
          echo "<strong>Service:</strong> " . htmlspecialchars($row['service']) . "<br>";
          echo "<strong>Date:</strong> " . htmlspecialchars($row['date']) . "<br>";
          echo "<strong>Time:</strong> " . htmlspecialchars($row['time']) . "<br>";
          echo "<strong>Amount:</strong> Rs. " . (isset($row['amount']) ? htmlspecialchars($row['amount']) : 'N/A') . "<br>";
          echo "<strong>Status:</strong> <span style='color:" . ($row['status'] === 'Approved' ? '#2ecc71' : '#f1c40f') . ";'>" . $row['status'] . "</span>";
          echo "</div>";
      }
      echo "</div>";
  } else {
      echo "<p>No recent appointments.</p>";
  }
  ?>
</div>

<script>
  const userSidebar = document.getElementById('userSidebar');
  const cartSidebar = document.getElementById('cartSidebar');
  const appointmentSidebar = document.getElementById('appointmentSidebar');

  function toggleUserSidebar(){
    userSidebar.classList.toggle('active');
    cartSidebar.classList.remove('active');
    appointmentSidebar.classList.remove('active');
  }
  function toggleCart(){
    cartSidebar.classList.toggle('active');
    userSidebar.classList.remove('active');
    appointmentSidebar.classList.remove('active');
  }
  function toggleAppointmentSidebar(){
    appointmentSidebar.classList.toggle('active');
    userSidebar.classList.remove('active');
    cartSidebar.classList.remove('active');
  }
  function closeSidebars(){
    userSidebar.classList.remove('active');
    cartSidebar.classList.remove('active');
    appointmentSidebar.classList.remove('active');
  }
</script>


  <?php
include 'db.php';

// Fetch all packages from DB
$query = "SELECT * FROM packages ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Salon Packages</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

/* === ROOT VARIABLES (DecorVista Luxury Theme) === */
:root {
  --brand-accent: #d4a373; /* Gold accent */
  --brand-accent-hover: #b89560;
  --text-light: #f9f9f9;
  --text-dark: #ffffff;
  --shadow-glow: 0 0 12px var(--brand-accent), 0 0 24px var(--brand-accent);
  --bg-dark: #0f0f0f;
  --bg-card: rgba(255, 255, 255, 0.05);
  --glass-blur: blur(14px);
  --danger: #ff4d4d;
}

/* === GLOBAL STYLES === */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(180deg, #0f0f0f, #1a1a1a);
  color: var(--text-light);
  overflow-x: hidden;
}

/* === SECTION TITLE === */
.section-title {
  font-size: 44px;
  text-align: center;
  font-weight: 800;
  color: var(--text-dark);
  margin: 50px 0 30px;
  text-shadow: 0 0 12px var(--brand-accent);
  position: relative;
}

.section-title::after {
  content: '';
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: -14px;
  width: 100px;
  height: 4px;
  background: var(--brand-accent);
  border-radius: 2px;
  box-shadow: var(--shadow-glow);
}

/* === PRODUCT GRID === */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 3rem;
  padding: 60px 8%;
}

/* === PRODUCT CARD === */
.product-card {
  background: var(--bg-card);
  border-radius: 22px;
  backdrop-filter: var(--glass-blur);
  box-shadow: 0 12px 40px rgba(212,163,115,0.3);
  overflow: hidden;
  transition: 0.35s ease;
  cursor: pointer;
  border: 1px solid var(--brand-accent);
  max-width: 300px;
}

.product-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(212,163,115,0.5);
}

/* === PRODUCT IMAGE === */
.product-image {
  height: 260px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.product-card:hover img {
  transform: scale(1.1);
}

/* === PRODUCT INFO === */
.product-info {
  padding: 24px;
  text-align: center;
}

.product-info h3 {
  font-size: 20px;
  margin-bottom: 8px;
  color: var(--text-light);
}

.product-info .price {
  font-size: 18px;
  font-weight: 600;
  color: var(--brand-accent);
  margin-bottom: 14px;
}

/* === BUY BUTTON === */
.buy-btn {
  background: var(--brand-accent);
  border: none;
  padding: 10px 26px;
  border-radius: 30px;
  color: #fff;
  font-weight: 600;
  font-size: 15px;
  cursor: pointer;
  transition: 0.3s ease;
  animation: pulse 1.6s infinite ease-in-out;
}

.buy-btn:hover {
  background: var(--brand-accent-hover);
}

@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(212,163,115,0.7); }
  70% { box-shadow: 0 0 0 10px rgba(212,163,115,0); }
  100% { box-shadow: 0 0 0 0 rgba(212,163,115,0); }
}

/* === PRODUCT MODAL === */
.product-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.85);
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.modal-content {
  background-color: var(--bg-dark);
  padding: 30px;
  border-radius: 18px;
  box-shadow: 0 0 28px rgba(212,163,115,0.7);
  max-width: 500px;
  width: 100%;
  text-align: center;
  color: var(--text-light);
  position: relative;
  animation: fadeIn 0.4s ease-in-out;
  border: 1px solid var(--brand-accent);
}

.modal-content img {
  max-width: 300px;
  height: auto;
  border-radius: 10px;
  margin-bottom: 20px;
}

.modal-content h3 {
  font-size: 26px;
  margin-bottom: 10px;
  color: var(--text-light);
}

.modal-content .desc {
  font-size: 16px;
  color: #ddd;
  margin-bottom: 20px;
  white-space: normal;
  word-wrap: break-word;
}

.modal-content .buy-now {
  background: var(--brand-accent);
  border: none;
  padding: 12px 28px;
  font-size: 16px;
  font-weight: 600;
  color: white;
  border-radius: 30px;
  cursor: pointer;
  transition: 0.3s ease, transform 0.2s ease;
  box-shadow: 0 0 10px var(--brand-accent);
}

.modal-content .buy-now:hover {
  background: var(--brand-accent-hover);
  transform: scale(1.05);
  box-shadow: 0 0 14px var(--brand-accent-hover);
}

/* === CLOSE BUTTON === */
.close {
  position: absolute;
  top: 14px;
  right: 18px;
  font-size: 28px;
  color: #aaa;
  cursor: pointer;
  transition: 0.2s ease;
  text-shadow: 0 0 3px #fff;
}

.close:hover {
  color: #fff;
  transform: scale(1.2);
}

/* === ANIMATIONS === */
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

/* === RESPONSIVE === */
@media(max-width: 768px) {
  .product-card { max-width: 90%; }
  .modal-content { padding: 20px; }
  .product-image img { width: 100%; height: auto; }
}

</style>

</head>
<body>

  <h2 class="section-title">DecorVista Packages</h2>

  <div class="product-grid">
    <?php $count = 0; while ($row = mysqli_fetch_assoc($result)): $count++; ?>
      <div class="product-card" onclick="openModal('modal<?= $count ?>')">
        <div class="product-image">
          <img src="<?= $row['image'] ?>" alt="Package Image">
        </div>
        <div class="product-info">
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <p class="price">$<?= number_format($row['price']) ?></p>
          <button class="buy-btn">Buy Now</button>
        </div>
      </div>

      <!-- Modal -->
      <!-- Modal -->
<div id="modal<?= $count ?>" class="product-modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('modal<?= $count ?>')">&times;</span>
    <img src="<?= $row['image'] ?>" alt="Package Image">
    <h3><?= htmlspecialchars($row['name']) ?></h3>
    <p class="desc"><?= htmlspecialchars($row['description']) ?></p>
    <a href="pakage_form.php?package=<?= urlencode($row['name']) ?>" class="buy-now">Buy Now</a>
  </div>
</div>

    <?php endwhile; ?>
  </div>

  <script>
    function openModal(id) {
      document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
    }

    window.onclick = function(event) {
      document.querySelectorAll('.product-modal').forEach(modal => {
        if (event.target === modal) {
          modal.style.display = "none";
        }
      });
    }
  </script>

</body>
</html>









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


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function submitCheckout() {
    const name = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const payment = document.getElementById("payment").value;

    const nameRegex = /^[a-zA-Z\s]{4,}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let error = "";

    if (!nameRegex.test(name)) {
      error += "✦ Name must be at least 4 letters and contain only alphabets.\n";
    }

    if (!emailRegex.test(email)) {
      error += "✦ Please enter a valid email address.\n";
    }

    if (!payment) {
      error += "✦ Please select a payment method.\n";
    }

    if (error) {
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: error,
        confirmButtonColor: '#6c5ce7'
      });
      return; // stop form submission
    }

    // If no errors, submit via fetch
    fetch('submit_order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name,
        email: email,
        payment: payment,
        cart: cart // assumes cart is defined globally
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Order Placed',
          text: 'Your order has been placed successfully!',
          confirmButtonColor: '#6c5ce7'
        });
        cart = [];
        renderCart();
        document.getElementById("checkoutModal").style.display = "none";
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Server Error',
          text: data.message || 'Something went wrong.',
          confirmButtonColor: '#6c5ce7'
        });
      }
    })
    .catch(err => {
      Swal.fire({
        icon: 'error',
        title: 'Network Error',
        text: 'Please try again later.',
        confirmButtonColor: '#6c5ce7'
      });
      console.error(err);
    });
  }
</script>


 
  


 
 
 <!-- FOOTER -->
<footer class="footer-layout1">
  <div class="footer-top-newsletter" data-aos="fade-left">
    <div class="container">
      <div class="newsletter-row">
     <div class="newsletter-col salone-text">
  <img src="decorevistaimages/logo.png" alt=" Logo" class="newsletter-logo" />
</div>
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

        <!-- Best Services -->
        <div class="footer-box" data-aos="fade-left" data-aos-delay="300">
          <h3 class="footer-heading">Best Services</h3>
          <ul class="footer-list">
            <li><a href="Detail.php">Custom Furniture</a></li>
            <li><a href="Detail.php">Interior Design</a></li>
            <li><a href="Detail.php">Space Planning</a></li>
            <li><a href="Detail.php">Home Renovation</a></li>
            <li><a href="Detail.php">Decor Consultation</a></li>
          </ul>
        </div>

        <!-- Our Products -->
        <div class="footer-box" data-aos="fade-left" data-aos-delay="400">
          <h3 class="footer-heading">Our Products</h3>
          <ul class="footer-list">
            <li><a href="product.php">Sofas & Chairs</a></li>
            <li><a href="product.php">Tables</a></li>
            <li><a href="product.php">Cabinets & Storage</a></li>
            <li><a href="product.php">Lighting</a></li>
            <li><a href="product.php">Home Accessories</a></li>
          </ul>
        </div>


        <!-- Recent Posts -->
        <div class="footer-box" data-aos="fade-left" data-aos-delay="500">
          <h3 class="footer-heading">Recent Posts</h3>
          <div class="recent-post">
            <img src="decorevistaimages/a655ffbfbe27b81a124069d018023bc3.jpg" alt="Blog Post" />
            <div>
              <p class="date">April 8, 2025</p>
              <a href="#" style="color: #ffffffff">Top 5 Modern Living Room Ideas</a>
            </div>
          </div>
          <div class="recent-post">
            <img src="decorevistaimages/3759d98e9032719b8e11ce45eb9eb859.jpg" alt="Blog Post" />
            <div>
              <p class="date">April 10, 2025</p>
              <a href="#" style="color: #ffffffff">Choosing the Perfect Furniture for Your Home</a>
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
</html>
