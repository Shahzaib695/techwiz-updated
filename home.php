<?php
// echo "Welcome Farrukh";
session_start();
include 'db.php';

if(!isset($_SESSION['email'])){
    header('location:login.php');
    exit();
}
?>


  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700&display=swap" rel="stylesheet">
  <style>


    #loader {
      position: fixed;
      inset: 0;
      background: url('decorevistaimages/214ae3e55fb608d768b3bc455ddcf18a.jpg') center/cover no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      z-index: 9999;
      transition: opacity 0.8s ease, visibility 0.8s ease;
      animation: zoomBg 5s ease-out forwards;
    }

    @keyframes zoomBg {
      0% { transform: scale(1); }
      100% { transform: scale(1.05); }
    }

    #loader::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
    }

    #loader.hide {
      opacity: 0;
      visibility: hidden;
    }

    .logo {
      max-width: 180px;
      z-index: 2;
      animation: logoIn 1s ease-out forwards;
      opacity: 0;
      transform: translateY(30px) skewY(6deg);
    }

    @keyframes logoIn {
      to {
        opacity: 1;
        transform: translateY(0) skewY(0deg);
      }
    }

    .circle {
      width: 60px;
      height: 60px;
      border: 5px solid rgba(255, 255, 255, 0.2);
      border-top: 5px solid #6c5ce7;
      border-radius: 50%;
      animation: spin 1.2s linear infinite, glow 1.5s ease-in-out infinite alternate;
      margin-top: 25px; /* ✅ removed bottom margin */
      z-index: 2;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @keyframes glow {
      0%   { box-shadow: 0 0 6px #6c5ce7; }
      100% { box-shadow: 0 0 20px #6c5ce7; }
    }

    .text {
      font-size: 2.5rem;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 3px;
      display: flex;
      gap: 6px;
      z-index: 2;
    }

    .text span {
      display: inline-block;
      opacity: 0;
      transform: translateY(40px) skewY(6deg);
      animation: letterIn 0.8s ease forwards;
    }

    .text span:nth-child(1) { animation-delay: 1.1s; }
    .text span:nth-child(2) { animation-delay: 1.2s; }
    .text span:nth-child(3) { animation-delay: 1.3s; }
    .text span:nth-child(4) { animation-delay: 1.4s; }
    .text span:nth-child(5) { animation-delay: 1.5s; }
    .text span:nth-child(6) { animation-delay: 1.6s; }
    .text span:nth-child(7) { animation-delay: 1.7s; }
    .text span:nth-child(8) { animation-delay: 1.8s; }
    .text span:nth-child(9) { animation-delay: 1.9s; }
    .text span:nth-child(10) { animation-delay: 2s; }
    .text span:nth-child(11) { animation-delay: 2.1s; }
    .text span:nth-child(12) { animation-delay: 2.2s; }

    @keyframes letterIn {
      to {
        opacity: 1;
        transform: translateY(0) skewY(0deg);
      }
    }

    .swipe-line {
      position: absolute;
      top: 50%;
      left: -100%;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, transparent, #fff, transparent);
      animation: knifeSwipe 1.2s ease-out 1.2s forwards;
      z-index: 3;
    }

    @keyframes knifeSwipe {
      to { left: 100%; }
    }

    .main {
      display: none;
      opacity: 0;
      text-align: center;
      color: #111;
      padding: 60px 20px;
    }

    body.loaded {
      overflow: auto;
      overflow-x: hidden;
    }

    body.loaded .main {
      display: block;
      animation: fadeInMain 1s ease forwards 0.5s;
    }

    @keyframes fadeInMain {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 600px) {
      .logo {
        max-width: 140px;
      }

      .text {
        font-size: 1.6rem;
        gap: 4px;
      }

      .circle {
        width: 45px;
        height: 45px;
      }
    }
  </style>
</head>
<body>

  <!-- Loader -->
  <div id="loader">
  <img src="decorevistaimages/logo.png" height="200px"  alt="Logo" class="logo" />

    <div class="circle"></div>
    <div class="text">
      <span>D</span><span>E</span><span>C</span><span>O</span><span>R</span><span>V</span><span>I</span>
      <span></span><span>S</span><span>T</span><span>A</span>
    </div>
    <div class="swipe-line"></div>
  </div>

  <!-- Main Content
  <div class="main">
    <h1>Welcome to Elegance Salone</h1>
    <p>Your full website content goes here.</p>
  </div> -->

  <!-- Optional Sound -->
  <audio id="loaderSound" src="load.mp3" preload="auto"></audio>

  <script>
    window.addEventListener("load", () => {
      const sound = document.getElementById("loaderSound");
      if (sound) {
        sound.volume = 0.4;
        sound.play().catch(() => {});
      }

      setTimeout(() => {
        document.body.classList.add("loaded");
        document.getElementById("loader").classList.add("hide");
      }, 2800);
    });
  </script>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
 
  <link rel="stylesheet" href="home.css">

  <title>DECORVISTA-HOMEPAGE</title>
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- AOS Animation -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />

  <!-- ✅ NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <!-- Logo -->
    <div class="logo">
      <img src="decorevistaimages/logo.png" alt="decorevista logo" />
    </div>

    <!-- Navigation Links -->
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
    const navbar = document.getElementById("navbar");
    navbar.classList.toggle("scrolled", window.scrollY > 50);
  });
</script>

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
    const navbar = document.getElementById("navbar");
    navbar.classList.toggle("scrolled", window.scrollY > 50);
  });
</script>


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
  
  <!-- DecorVista Furniture Carousel Content -->
<section class="carousel-container">
  <div class="carousel">
    <div class="carousel-track">

      <!-- Slide 1 -->
      <div class="card">
        <img src="decorevistaimages/3759d98e9032719b8e11ce45eb9eb859.jpg" alt="Slide 1">
        <div class="slide-content">
          <h2 class="headline">EXCLUSIVE COLLECTION</h2>
          <h1 class="title">MODERN LIVING SPACES</h1>
          <p class="desc">Discover handcrafted furniture that blends style, comfort, and luxury for your home.</p>
          <a href="#services" class="hero-button">Explore Collection ⟶</a>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="card">
        <img src="decorevistaimages/14afc745369f7131936b07aca8674c8c.jpg" alt="Slide 2">
        <div class="slide-content">
          <h2 class="headline">DESIGN INNOVATION</h2>
          <h1 class="title">ELEGANT FURNITURE</h1>
          <p class="desc">Our pieces combine craftsmanship and modern aesthetics to create timeless interiors.</p>
          <a href="#services" class="hero-button">View Designs ⟶</a>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="card">
        <img src="decorevistaimages/262e967abda82e69852bbe51aa1bc0a6.jpg" alt="Slide 3">
        <div class="slide-content">
          <h2 class="headline">LUXURY LIVING</h2>
          <h1 class="title">CUSTOMIZED FURNITURE</h1>
          <p class="desc">Tailor-made designs crafted to reflect your personal taste and lifestyle.</p>
          <a href="#services" class="hero-button">Book Consultation ⟶</a>
        </div>
      </div>

    </div>
  </div>

  <!-- Navigation Buttons -->
  <button class="carousel-btn prev">⟵</button>
  <button class="carousel-btn next">⟶</button>
</section>

  <script>
    // crousel start

const track = document.querySelector('.carousel-track');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');
const slides = document.querySelectorAll('.card');
let index = 0;

function updateCarousel() {
  const width = slides[0].offsetWidth;
  track.style.transform = `translateX(-${index * width}px)`;
  animateSlideText();
}

// Apply animation to active slide text
function animateSlideText() {
  slides.forEach((slide, i) => {
    const content = slide.querySelector('.slide-content');
    if (i === index) {
      content.classList.remove('animate'); // Reset
      void content.offsetWidth; // Trigger reflow
      content.classList.add('animate');
    } else {
      content.classList.remove('animate');
    }
  });
}

// Manual controls
nextBtn.addEventListener('click', () => {
  index = (index + 1) % slides.length;
  updateCarousel();
});

prevBtn.addEventListener('click', () => {
  index = (index - 1 + slides.length) % slides.length;
  updateCarousel();
});

// Auto slide every 4 seconds
setInterval(() => {
  index = (index + 1) % slides.length;
  updateCarousel();
}, 4000);

// Initial setup
window.addEventListener('load', updateCarousel);

  </script>

<style>
 

</style>

 <!-- About Section -->
<section class="about-section" id="about-us">
  <div class="about-left">
    <h2>About Us</h2>
    <p class="heading-description">
      At Decore Vista, we craft spaces that reflect your style and personality.
    </p>
    <p class="description">
      With a passion for interior design and a commitment to excellence, our team transforms ordinary places into extraordinary living experiences. From concept to execution, we blend creativity, functionality, and elegance to bring your vision to life. Whether it's residential, commercial, or bespoke decor solutions — Decore Vista delivers with precision and flair.
    </p>
    <a href="contect.php" class="btn-contact">Contact Us →</a>
  </div>
  <div class="about-right">
    <img
      src="decorevistaimages/dd97665d10903c95fc5d21a7dfae08a5.jpg"
      alt="Team Image 1"
      class="fade-element"
    />
    <img
      src="decorevistaimages/dbcf94561d27fab71a0a63c570bf96ba.jpg"
      alt="Team Image 2"
      class="fade-element"
    />
  </div>
</section>


<script>
  document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".fade-element");

    function revealOnScroll() {
      elements.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 50) {
          el.classList.add("fadeIn");
        }
      });
    }

    window.addEventListener("scroll", revealOnScroll);
    revealOnScroll();
  });
</script>

<section class="services-section">
  <div class="content-side">
    <h2 class="section-title">What We Do</h2>
    <p class="section-desc">
      At Decore Vista, we specialize in creating personalized spaces that merge functionality with style, offering exceptional design solutions for every type of interior.
    </p>
    <ul class="service-list">
      <li class="service-card" data-delay="0.2s">
        <div class="icon"><i class="fas fa-couch"></i></div>
        <div class="text">
          <h3>Residential Interiors</h3>
          <p>Transform your home into a stylish, comfortable, and functional living space tailored to your taste and needs.</p>
        </div>
      </li>
      <li class="service-card" data-delay="0.7s">
        <div class="icon"><i class="fas fa-briefcase"></i></div>
        <div class="text">
          <h3>Commercial Interiors</h3>
          <p>We design and create efficient, modern, and inspiring spaces for offices, retail stores, and other commercial establishments.</p>
        </div>
      </li>
      <li class="service-card" data-delay="0.6s">
        <div class="icon"><i class="fas fa-paint-roller"></i></div>
        <div class="text">
          <h3>Space Planning & Design</h3>
          <p>Maximize the potential of your space with smart planning, innovative design solutions, and aesthetic appeal.</p>
        </div>
      </li>
    </ul>
  </div>
  <div class="image-side"></div>
</section>


<style>
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".service-card");

    function animateOnScroll() {
      cards.forEach((card) => {
        const rect = card.getBoundingClientRect();
        const delay = card.getAttribute("data-delay") || "0s";

        if (rect.top < window.innerHeight - 50) {
          card.style.opacity = 1;
          card.style.transform = "translateX(0)";
          card.style.transitionDelay = delay;
        }
      });
    }

    window.addEventListener("scroll", animateOnScroll);
    animateOnScroll();
  });
</script>

<!-- shedule -->
<section class="container themed-section">
  <!-- Furniture Prices -->
  <div class="box">
    <h2>Our Fair Prices</h2>
    <div class="card prices">
      <div class="item">
        <div>
          <h3>Luxury Sofa</h3>
          <p>Premium 3-seater comfort</p>
        </div>
        <span>$1,250</span>
      </div>
      <div class="item">
        <div>
          <h3>Dining Set</h3>
          <p>6 chairs with solid wood table</p>
        </div>
        <span>$1,450</span>
      </div>
      <div class="item">
        <div>
          <h3>Office Chair</h3>
          <p>Ergonomic design with support</p>
        </div>
        <span>$375</span>
      </div>
      <div class="item">
        <div>
          <h3>Kids Bed</h3>
          <p>Stylish and durable for juniors</p>
        </div>
        <span>$750</span>
      </div>
      <div class="item">
        <div>
          <h3>Wardrobe</h3>
          <p>Spacious storage with mirror</p>
        </div>
        <span>$1,050</span>
      </div>
      <a href="pricing.php" class="more-link">More Furniture →</a>
    </div>
  </div>

  <!-- Delivery Schedule -->
  <div class="box">
    <h2>Delivery Schedule</h2>
    <div class="card schedule">
      <div class="date-heading">
        <span>Available Delivery Slots – September 3, 2025</span>
        <a href="#">View Calendar</a>
      </div>

      <div class="slot">
        <div><i>🚚</i> 10:00 am – 12:00 pm <p>5 deliveries available</p></div>
        <button class="btn unavailable">Unavailable</button>
      </div>
      <div class="slot">
        <div><i>🚚</i> 12:00 pm – 2:00 pm <p>3 deliveries available</p></div>
        <button class="btn available">Book Delivery</button>
      </div>
      <div class="slot">
        <div><i>🚚</i> 2:00 pm – 4:00 pm <p>6 deliveries available</p></div>
        <button class="btn unavailable">Unavailable</button>
      </div>
      <div class="slot">
        <div><i>🚚</i> 4:00 pm – 6:00 pm <p>4 deliveries available</p></div>
        <button class="btn available">Book Delivery</button>
      </div>

      <a href="schedule.php" class="more-link">Full Schedule →</a>
    </div>
  </div>
</section>

<!-- offer session    -->
 <section class="elementor-section">
  <div class="elementor-container">
    <div class="elementor-column">
      <div class="call-to-action-box-layout1">
        <h2 class="main-title">Offer Promotion</h2>
        <div class="sub-title">Our special hand made creme</div>
        <p class="description">There are many variations of passages of Lorem Ipsum majority have is suffered alteration in that some form believable.</p>
        <div class="d-flex">
          <div class="price">$45.00</div>
          <a href="#" class="btn-ghost">More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>    
  </div>
</section>


<!-- discove  -->
<section style=" background-color: #000000ff;" class="discover-section">
  <div class="container">
    <div class="discover-top-row">
      <div class="card-group">
        <div class="portfolio-box">
          <img src="decorevistaimages/01e3b1f0d62abd5f37600c08bdabc361.jpg" alt="Mustache Trimming">
          <div class="content-box">
            <h3 class="title">Mustache Trimming</h3>
            <p class="description">Short description about mustache trimming.</p>
            <a href="priceing.php" class="btn-text">Read More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
        <div class="portfolio-box">
          <img src="decorevistaimages/009d0b91530ad0955c2a110a5520f117.jpg" alt="Styling">
          <div class="content-box">
            <h3 class="title">Styling</h3>
            <p class="description">Short description about styling services.</p>
            <a href="#" class="btn-text">Read More <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="discover-right">
        <h2 class="heading-title">Discover Our Works</h2>
        <p class="heading-description">We offer amazing hair & beard services with quality products and professional care.</p>
        <a href="#" class="btn-ghost">All Works <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <div class="discover-grid">
      <div class="grid-item"><img src="decorevistaimages/14afc745369f7131936b07aca8674c8c.jpg" alt="1"></div>
      <div class="grid-item"><img src="decorevistaimages/73f4fc255b81c47a77f2af814e4a01d5.jpg" alt="2"></div>
      <div class="grid-item"><img src="decorevistaimages/dd97665d10903c95fc5d21a7dfae08a5.jpg" alt="3"></div>
      <div class="grid-item"><img src="decorevistaimages/dbcf94561d27fab71a0a63c570bf96ba.jpg" alt="4"></div>
    </div>
  </div>
</section>
<!-- dedicated session start -->
<section class="team-section" style="background-color: #000000ff;">
  <!-- Left Side (Cards) -->
  <div class="team-left">
    <div class="section-heading">
      <h2>Meet Our Experts</h2>
      <p>At <strong>DecorVista</strong>, our team of designers, artisans, and interior experts are dedicated to creating furniture that blends comfort, elegance, and timeless beauty for your spaces.</p>
    </div>

    <div class="team-grid">
      <?php
      // Fetch employees
      $employeesQuery = "SELECT id, name, image, designation, experience 
                         FROM employees ORDER BY id DESC LIMIT 4";
      $employeesResult = $conn->query($employeesQuery);

      // Fetch approved designers
      $designersQuery = "SELECT id, name, image, expertise AS designation, bio AS experience 
                         FROM designer_profiles WHERE status='Approved' ORDER BY id DESC LIMIT 4";
      $designersResult = $conn->query($designersQuery);

      // Merge results (Employees first, then designers)
      if ($employeesResult->num_rows > 0) {
          while ($row = $employeesResult->fetch_assoc()) {
              ?>
              <div class="team-member">
                <div class="multi-side-hover">
                  <figure class="item-img">
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                  </figure>
                  <div class="item-content">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['designation']) ?> — <?= htmlspecialchars($row['experience']) ?></p>
                  </div>
                </div>
              </div>
              <?php
          }
      }

      if ($designersResult->num_rows > 0) {
          while ($row = $designersResult->fetch_assoc()) {
              ?>
              <div class="team-member">
                <div class="multi-side-hover">
                  <figure class="item-img">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                  </figure>
                  <div class="item-content">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['designation']) ?> — <?= htmlspecialchars($row['experience']) ?></p>
                  </div>
                </div>
              </div>
              <?php
          }
      }
      ?>
      
    </div>
    <button class="btn available" onclick="window.location.href='Detail.php'">Book a Consultation</button>
  </div>

  <!-- Right Side (Banner) -->
  <div class="team-right">
    <img src="decorevistaimages/7baf8959623f504269f65a9b9c488ba9.jpg" alt="DecorVista Team">
  </div>
</section>



  <!-- Hover Direction Script -->
  <script>
    document.querySelectorAll('.multi-side-hover').forEach(box => {
      const content = box.querySelector('.item-content');

      function getDirection(e, el) {
        const rect = el.getBoundingClientRect();
        const w = rect.width;
        const h = rect.height;
        const x = (e.clientX - rect.left - w / 2) * (w > h ? h / w : 1);
        const y = (e.clientY - rect.top - h / 2) * (h > w ? w / h : 1);
        const d = Math.round(Math.atan2(y, x) / 1.57079633 + 5) % 4;
        return d;
      }

      box.addEventListener('mouseenter', (e) => {
        const dir = getDirection(e, box);
        content.className = 'item-content';
        if (dir === 0) content.classList.add('from-top');
        if (dir === 1) content.classList.add('from-right');
        if (dir === 2) content.classList.add('from-bottom');
        if (dir === 3) content.classList.add('from-left');

        requestAnimationFrame(() => {
          content.classList.add('in');
        });
      });

      box.addEventListener('mouseleave', () => {
        content.classList.remove('in');
      });
    });
  </script>

<!-- dedicated session end -->

<!-- Blog Section -->
<section style="background-color: #000000ff;" class="blog-section">
  <div class="blog-header">
    <div class="heading-content">
      <h2>From Our Blog</h2>
      <p>Stay inspired with furniture tips, home styling trends, and decor ideas from our experts.</p>
    </div>
    <a href="blog.php" class="btn-view-all">All Posts <i class="fas fa-arrow-right"></i></a>
  </div>

  <div class="blog-container">
    <!-- Blog 1 -->
    <div class="blog-card animate-on-scroll" data-delay="0.1s">
      <div class="blog-img">
        <img src="decorevistaimages/015c679685cac79725a6c311e1464667.jpg" alt="Luxury Living Room">
        <span class="blog-category">Living Room</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> Sept 5, 2025</li>
          <li><i class="far fa-user"></i> Admin</li>
          <li><i class="far fa-comments"></i> 2 Comments</li>
        </ul>
        <h3 class="title">5 Ways to Elevate Your Living Room with Modern Furniture</h3>
        <a href="detail.php?id=1" class="read-more">Read More →</a>
      </div>
    </div>

    <!-- Blog 2 -->
    <div class="blog-card animate-on-scroll" data-delay="0.3s">
      <div class="blog-img">
        <img src="decorevistaimages/CROUSALIMAGES 3.avif" alt="Dining Trends">
        <span class="blog-category">Dining</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> Aug 28, 2025</li>
          <li><i class="far fa-user"></i> Design Team</li>
          <li><i class="far fa-comments"></i> 5 Comments</li>
        </ul>
        <h3 class="title">Dining Room Trends Every Homeowner Should Know</h3>
        <a href="detail.php?id=2" class="read-more">Read More →</a>
      </div>
    </div>

    <!-- Blog 3 -->
    <div class="blog-card animate-on-scroll" data-delay="0.5s">
      <div class="blog-img">
        <img src="decorevistaimages/download.jpg" alt="Bedroom Comfort">
        <span class="blog-category">Bedroom</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> Aug 20, 2025</li>
          <li><i class="far fa-user"></i> Decor Expert</li>
          <li><i class="far fa-comments"></i> 3 Comments</li>
        </ul>
        <h3 class="title">Creating a Cozy Bedroom: Furniture & Styling Tips</h3>
        <a href="detail.php?id=3" class="read-more">Read More →</a>
      </div>
    </div>
  </div>
</section>
 
 <script>
  document.addEventListener("DOMContentLoaded", () => {
    const blogCards = document.querySelectorAll(".animate-on-scroll");

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.2,
      }
    );

    blogCards.forEach((card) => observer.observe(card));
  });
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

<!-- ✅ Appointment Bills Sidebar -->
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
</body>
<!-- Before </body> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();
</script>
  </html>

  
