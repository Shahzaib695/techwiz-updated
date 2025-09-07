<?php
session_start();
include 'db.php';
if (!isset($_SESSION['email'])) {
    header('location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <!-- <link rel="stylesheet" href="loader.css"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="home.css">
  <link rel="stylesheet" href="blog.CSs">
  <title>Contact - Salon</title>


  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- AOS Animation -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />

   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
<style>
:root {
  --brand-accent: #d4a373;       /* warm light brown */
  --brand-accent-hover: #b8895c; /* deeper brown for hover */
  --text-dark: #2b2b2b;
  --text-light: #fff;
  --bg-cream: #fdfaf6;           /* soft cream background */
  --card-bg: rgba(212, 163, 115, 0.08);
  --card-border: rgba(212, 163, 115, 0.2);
}

/* 🌟 Testimonial Section */
.testimonial-section {
  padding: 50px 20px;
  background: var(--bg-cream);
  border-radius: 20px;
  margin: 60px auto;
  box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
  border: 1px solid var(--card-border);
  color: var(--text-dark);
  max-width: 1000px;
}

/* 🧾 Testimonial Form */
.testimonial-form {
  max-width: 550px;
  margin: 0 auto 35px;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.testimonial-form input,
.testimonial-form textarea {
  padding: 14px;
  border-radius: 10px;
  background: #fff;
  border: 1px solid var(--card-border);
  color: var(--text-dark);
  outline: none;
  resize: none;
  transition: border 0.3s ease;
}

.testimonial-form input:focus,
.testimonial-form textarea:focus {
  border-color: var(--brand-accent);
}

.testimonial-form input::placeholder,
.testimonial-form textarea::placeholder {
  color: #999;
}

.testimonial-form button {
  padding: 14px;
  background-color: var(--brand-accent);
  border: none;
  color: var(--text-light);
  font-weight: bold;
  border-radius: 10px;
  cursor: pointer;
  transition: background 0.3s ease, box-shadow 0.3s ease;
}

.testimonial-form button:hover {
  background-color: var(--brand-accent-hover);
  box-shadow: 0 6px 15px rgba(212, 163, 115, 0.4);
}

/* 💬 Testimonials Slider */
.testimonial-slider {
  display: flex;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  gap: 20px;
  padding: 25px 0;
  scroll-behavior: smooth;
}

.testimonial-card {
  flex: 0 0 auto;
  width: 300px;
  max-height: 260px;
  background: #fff;
  border: 1px solid var(--card-border);
  border-radius: 15px;
  padding: 20px;
  scroll-snap-align: start;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  color: var(--text-dark);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.testimonial-card:hover {
  transform: translateY(-5px);
}

/* 💬 Text inside card */
.testimonial-card .message {
  font-style: italic;
  color: #555;
  line-height: 1.6;
}

.testimonial-card .author {
  margin-top: 12px;
  font-weight: bold;
  color: var(--brand-accent);
  font-size: 16px;
}

/* ⭐ Stars */
.testimonial-card .stars {
  margin-top: 6px;
  font-size: 18px;
  color: #f5c518;
}

/* 🌟 Star Rating Input (Form) */
.rating-stars {
  display: flex;
  justify-content: center;
  gap: 6px;
  direction: rtl;
}

.rating-stars input {
  display: none;
}

.rating-stars label span {
  font-size: 24px;
  color: #bbb;
  cursor: pointer;
  transition: color 0.2s;
}

.rating-stars input:checked ~ label span,
.rating-stars label:hover span,
.rating-stars label:hover ~ label span {
  color: #f5c518;
}

/* 📱 Responsive */
@media (max-width: 480px) {
  .testimonial-section {
    padding: 30px 15px;
  }

  .testimonial-slider {
    gap: 14px;
  }

  .testimonial-card {
    width: 260px;
  }
}
</style>

</head>
<body>

<!-- ✅ NAVBAR -->
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
<div class="cart-sidebar" id="cartSidebar">
  <span class="close-btn" onclick="closeSidebars()">&times;</span>
  <h3>Your Bag</h3>
  <div class="cart-items" id="cartItems"></div>
  <div class="cart-footer">
    <div class="cart-total"><span>Total:</span><span id="cartTotal">$0.00</span></div>
    <button class="checkout-btn" onclick="document.getElementById('checkoutModal').style.display='flex'">Proceed to Checkout</button>
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



  <!-- Banner -->
  <section class="inner-banner-wrap">
    <div class="inner-banner-box">
      <h1 class="title">BLOG</h1>
      <ul class="breadcrumbs">
        <li><a href="home.php">HOME</a></li>
        <li>Service</li>
      </ul>
    </div>
  </section>



   <!--Faqs-->
<section class="faq-section">
  <div class="faq-container">
    <h2 class="faq-title" data-aos="fade-up" style="text-align: center; margin-bottom: 10px;">Frequently Asked Questions</h2>

    <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
      <button class="faq-question">
        <i class="fas fa-clock"></i> What are DecorVista’s store hours?
        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
      </button>
      <div class="faq-answer">Our showroom is open Monday to Sunday from 10:00 AM to 8:00 PM.</div>
    </div>

    <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
      <button class="faq-question">
        <i class="fas fa-truck"></i> Do you offer home delivery?
        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
      </button>
      <div class="faq-answer">Yes, we provide safe and reliable home delivery for all furniture items across major cities.</div>
    </div>

    <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
      <button class="faq-question">
        <i class="fas fa-couch"></i> Can I customize furniture designs?
        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
      </button>
      <div class="faq-answer">Absolutely! We offer customization in size, color, and fabric to match your home’s interior style.</div>
    </div>

    <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
      <button class="faq-question">
        <i class="fas fa-credit-card"></i> What payment methods do you accept?
        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
      </button>
      <div class="faq-answer">We accept cash, debit/credit cards, bank transfers, and installment options on select items.</div>
    </div>

    <div class="faq-item" data-aos="fade-up" data-aos-delay="500">
      <button class="faq-question">
        <i class="fas fa-sync-alt"></i> What is your return and exchange policy?
        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
      </button>
      <div class="faq-answer">You can return or exchange furniture within 7 days of delivery if it’s unused and in original condition.</div>
    </div>
  </div>
</section>


  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script>
  AOS.init();

  const swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    effect: "fade",
    fadeEffect: {
      crossFade: true,
    },
  });

  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const btn = item.querySelector('.faq-question');
    btn.addEventListener('click', () => {
      const isActive = item.classList.contains('active');
      faqItems.forEach(el => el.classList.remove('active'));
      if (!isActive) item.classList.add('active');
    });
  });

  // ✅ Add this missing function
  function toggleNav() {
    const nav = document.getElementById("navLinks");
    nav.classList.toggle("show");
  }
</script>



<!-- Blog Section: DecorVista Furniture -->
<section class="blog-section">
  <div class="blog-header">
    <div class="heading-content">
      <h2>From Our Blog</h2>
      <p>Stay inspired with tips, trends, and ideas for your home décor and furniture.</p>
    </div>
    <a href="#" class="btn-view-all">All Posts <i class="fas fa-arrow-right"></i></a>
  </div>

  <div class="blog-container">
    <!-- Blog Card 1 -->
    <div class="blog-card animate-on-scroll" data-delay="0.1s">
      <div class="blog-img">
        <img src="decorevistaimages/6baefe68a02caba4e031604f0fd8051b.jpg" alt="Living Room Design">
        <span class="blog-category">Living Room</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> March 3, 2025</li>
          <li><i class="far fa-user"></i> Admin</li>
          <li><i class="far fa-comments"></i> 0 Comments</li>
        </ul>
        <h3 class="title">Transform your living room with modern furniture layouts and cozy touches</h3>
        <a href="#" class="read-more">Read More →</a>
      </div>
    </div>

    <!-- Blog Card 2 -->
    <div class="blog-card animate-on-scroll" data-delay="0.3s">
      <div class="blog-img">
        <img src="decorevistaimages/9ba191a849f995f8898f821d0cf4b54b.jpg" alt="Bedroom Ideas">
        <span class="blog-category">Bedroom</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> March 5, 2025</li>
          <li><i class="far fa-user"></i> Angela</li>
          <li><i class="far fa-comments"></i> 0 Comments</li>
        </ul>
        <h3 class="title">Elegant bedroom setups to maximize comfort and style in your personal space</h3>
        <a href="#" class="read-more">Read More →</a>
      </div>
    </div>

    <!-- Blog Card 3 -->
    <div class="blog-card animate-on-scroll" data-delay="0.5s">
      <div class="blog-img">
        <img src="decorevistaimages/14afc745369f7131936b07aca8674c8c.jpg" alt="Home Office Trends">
        <span class="blog-category">Home Office</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> March 6, 2025</li>
          <li><i class="far fa-user"></i> Mark</li>
          <li><i class="far fa-comments"></i> 4 Comments</li>
        </ul>
        <h3 class="title">Modern home office setups to boost productivity and style</h3>
        <a href="#" class="read-more">Read More →</a>
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





<!-- Blog Section: DecorVista Furniture -->
<section class="blog-section">
  <div class="blog-header">
    <div class="heading-content">
        <p>"Get inspired by the latest furniture trends, interior design tips, and home décor ideas."</p>
    </div>
  </div>

  <div class="blog-container">
    <!-- Blog Card 1 -->
    <div class="blog-card animate-on-scroll" data-delay="0.1s">
      <div class="blog-img">
        <img src="decorevistaimages/0076be46deb1c8d1d3a5ebfb5f4d3a8c.jpg" alt="Blog Image">
        <span class="blog-category">Living Room</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> March 3, 2025</li>
          <li><i class="far fa-user"></i> Admin</li>
          <li><i class="far fa-comments"></i> 0 Comments</li>
        </ul>
        <h3 class="title">Modern living room setups to elevate your home’s elegance and comfort.</h3>
        <a href="#" class="read-more">Read More →</a>
      </div>
    </div>

    <!-- Blog Card 2 -->
    <div class="blog-card animate-on-scroll" data-delay="0.3s">
      <div class="blog-img">
        <img src="decorevistaimages/84ce106242dd9ae70a4b75285826cbd4.jpg" alt="Blog Image">
        <span class="blog-category">Bedroom</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> March 5, 2025</li>
          <li><i class="far fa-user"></i> Angela</li>
          <li><i class="far fa-comments"></i> 0 Comments</li>
        </ul>
        <h3 class="title">Transform your bedroom into a serene retreat with minimalist furniture ideas.</h3>
        <a href="#" class="read-more">Read More →</a>
      </div>
    </div>

    <!-- Blog Card 3 -->
    <div class="blog-card animate-on-scroll" data-delay="0.5s">
      <div class="blog-img">
        <img src="decorevistaimages/262e967abda82e69852bbe51aa1bc0a6.jpg" alt="Blog Image">
        <span class="blog-category">Office</span>
      </div>
      <div class="blog-content">
        <ul class="meta">
          <li><i class="far fa-calendar-alt"></i> March 6, 2025</li>
          <li><i class="far fa-user"></i> Mark</li>
          <li><i class="far fa-comments"></i> 4 Comments</li>
        </ul>
        <h3 class="title">Functional and stylish home office setups to boost productivity and comfort.</h3>
        <a href="#" class="read-more">Read More →</a>
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



<!-- testimonial start -->
<!-- Testimonials Section -->
<section class="testimonial-section"  style="background-color: #000000ff ; "   id="testimonials">
  <h2  class="faq-title" data-aos="fade-up">What Our Clients Say</h2>
  
  <!-- Testimonial Form -->
  <!-- <form action="" method="post" class="testimonial-form">
    <input type="text" name="name" placeholder="Your Name" required>
    <textarea name="message" rows="3" placeholder="Your Feedback" required></textarea>
    <button type="submit" name="submit_testimonial">Submit Review</button>
  </form> -->
<!-- Testimonial Form -->
<form action="" method="post" class="testimonial-form">
  <input type="text" name="name" placeholder="Your Name" required>
  <textarea name="message" rows="3" placeholder="Your Feedback" required></textarea>

  <div class="rating-stars">
    <label><input type="radio" name="rating" value="5" required><span>★</span></label>
    <label><input type="radio" name="rating" value="4"><span>★</span></label>
    <label><input type="radio" name="rating" value="3"><span>★</span></label>
    <label><input type="radio" name="rating" value="2"><span>★</span></label>
    <label><input type="radio" name="rating" value="1"><span>★</span></label>
  </div>

  <button type="submit" name="submit_testimonial">Submit Review</button>
</form>



  <!-- Testimonials Display -->
  <div class="testimonial-slider">
    <?php
    // include 'db.php';
    // if (isset($_POST['submit_testimonial'])) {
    //     $name = mysqli_real_escape_string($conn, $_POST['name']);
    //     $message = mysqli_real_escape_string($conn, $_POST['message']);
    //     $query = "INSERT INTO testimonials (name, message) VALUES ('$name', '$message')";
    //     mysqli_query($conn, $query);
    //     echo "<script>alert('Thank you for your feedback!');</script>";
    // }
// if (isset($_POST['submit_testimonial'])) {
//     $name = mysqli_real_escape_string($conn, $_POST['name']);
//     $message = mysqli_real_escape_string($conn, $_POST['message']);
//     $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

//     $query = "INSERT INTO testimonials (name, message, rating) VALUES ('$name', '$message', $rating)";
//     mysqli_query($conn, $query);

//     echo "<script>alert('Thank you for your feedback!');</script>";
// }


if (isset($_POST['submit_testimonial'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    $query = "INSERT INTO testimonials (name, message, rating) VALUES ('$name', '$message', $rating)";
    mysqli_query($conn, $query);

    echo "<script>alert('Thank you for your feedback!');</script>";
}

$result = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 10");


    // $result = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY id DESC LIMIT 10");
    // while ($row = mysqli_fetch_assoc($result)) {
    //     echo "
    //     <div class='testimonial-card'>
    //         <p class='message'>\"{$row['message']}\"</p>
    //         <p class='author'>- {$row['name']}</p>
    //     </div>";
    // }
//     while ($row = mysqli_fetch_assoc($result)) {
//     $stars = str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']);

//     echo "
//     <div class='testimonial-card'>
//         <p class='message'>\"{$row['message']}\"</p>
//         <p class='author'>- {$row['name']}</p>
//         <p class='stars' style='color:#f5c518;'>$stars</p>
//     </div>";
// }
while ($row = mysqli_fetch_assoc($result)) {
    $stars = str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']);

    echo "
    <div class='testimonial-card'>
        <p class='message'>\"{$row['message']}\"</p>
        <p class='author'>- {$row['name']}</p>
        <p class='stars' style='color:#f5c518;'>$stars</p>
    </div>";
}


    ?>
  </div>
</section>

<!-- testimonial end... -->

  

 
 
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
