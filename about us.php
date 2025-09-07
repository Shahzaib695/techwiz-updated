<?php
// echo "Welcome Farrukh";
session_start();
include 'db.php';

if(!isset($_SESSION['email'])){
    header('location:login.php');
    exit();
}
// start
if(isset($_POST['Submit'])){
    $name= $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $query = "INSERT INTO `users1`(`name`, `email`, `subject`, `message`) VALUES ('$name','$email','$subject','$message')";

    $result = mysqli_query($conn, $query);

    if($result){
        // echo "data insert success";

        echo '<script>alert("Data Inserted")
      window.location.href = "contect_view.php" </script>';
    }else{
echo '<script>alert("Email is duplicated please new and change email")
      window.location.href = "contect.php" </script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="about us.css">
  <title>ABOUT US ELEGANCE SALONE</title>
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- AOS Animation -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />
</script>


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
  <!-- BANNER -->
  <section class="inner-banner-wrap">
    <div class="inner-banner-box">
      <h1 class="title">ABOUT-US</h1>
      <ul class="breadcrumbs">
        <li><a href="HOME.PHP">Home</a></li>
        <li>ABOUT-US</li>
      </ul>
    </div>
  </section>

  
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

<!-- what we do section -->
<section class="what-we-do-section">
  <!-- Left Side: Image -->
  <div class="what-we-do-image" data-aos="fade-right">
    <div class="image-wrapper">
      <img src="decorevistaimages/c4f7c868a1fd2bf2d6b9eec9f1938831.jpg" alt="DecorVista Furniture">
    </div>
  </div>

  <!-- Right Side: Content -->
  <div class="what-we-do-content" data-aos="fade-left">
    <div class="sub-title">What We Do</div>
    <h2 class="main-title">Transforming Homes with Timeless Furniture</h2>
    <p>
      At <strong>DecorVista</strong>, we bring style, comfort, and durability together to make your dream spaces a reality. 
      Our collection blends modern designs with classic elegance, ensuring your home reflects your personality.
    </p>
    <p>
      From luxury sofas and elegant dining sets to innovative storage solutions, 
      we offer furniture that enhances both aesthetics and functionality.
    </p>

    <div class="info-cards">
      <div class="info-card" data-aos="fade-up">
        <div class="info-icon"><i class="fa-solid fa-couch"></i></div>
        <div class="info-text">
          <h3>Premium Quality</h3>
          <p>Crafted with the finest materials for lasting comfort</p>
        </div>
      </div>
      <div class="info-card" data-aos="fade-up" data-aos-delay="100">
        <div class="info-icon"><i class="fa-solid fa-house-chimney"></i></div>
        <div class="info-text">
          <h3>Stylish Living</h3>
          <p>Designs that redefine modern homes</p>
        </div>
      </div>
    </div>

    <button class="cta-button">Explore Collection</button>
  </div>
</section>


<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>

<!-- Team Section (DecorVista Theme) -->
<section class="team-section" data-aos="fade-up">
  <div class="section-heading heading-layout7 heading-light">
    <div class="icon-box"><i class="fa-solid fa-users-gear"></i></div>
    <h2 class="heading-title">Meet Our Experts</h2>
    <p class="heading-description">
      Our passionate team of designers and architects bring creativity, elegance, and expertise to transform every space into a masterpiece.
    </p>
  </div>

  <div class="team-row">
    <!-- Team Member 1 -->
    <div class="team-box-layout2">
      <figure>
        <img src="medium-shot-woman-with-screwdriver.jpg" alt="Sophia Miller" loading="lazy">
        <a href="detail.php" class="item-btn">View Profile</a>
      </figure>
      <div class="content-box">
        <h3 class="title"><a href="detail.php">Sophia Miller</a></h3>
        <p class="description">Lead Interior Designer</p>
      </div>
    </div>

    <!-- Team Member 2 -->
    <div class="team-box-layout2">
      <figure>
        <img src="medium-shot-plus-size-man-working-as-barista.jpg" alt="David Carter" loading="lazy">
        <a href="detail.php" class="item-btn">View Profile</a>
      </figure>
      <div class="content-box">
        <h3 class="title"><a href="detail.php">David Carter</a></h3>
        <p class="description">Architect & Space Planner</p>
      </div>
    </div>

    <!-- Team Member 3 -->
    <div class="team-box-layout2">
      <figure>
        <img src="female-cutting-wood-plank.jpg" alt="Isabella Rossi" loading="lazy">
        <a href="detail.php" class="item-btn">View Profile</a>
      </figure>
      <div class="content-box">
        <h3 class="title"><a href="detail.php">Isabella Rossi</a></h3>
        <p class="description">Furniture Designer & Stylist</p>
      </div>
    </div>
  </div>
</section>






  


<!-- FOOTER -->
<footer class="footer-layout1">
  <!-- Newsletter Section -->
  <div class="footer-top-newsletter" data-aos="fade-left">
    <div class="container">
      <div class="newsletter-row">
        <div class="newsletter-col decorvista-text">
          <img src="decorevistaimages/logo.png" alt="DecorVista Logo" class="newsletter-logo" />
        </div>
        <div class="newsletter-col">Newsletter</div>
        <div class="newsletter-col">
          <input type="email" placeholder="Enter your email" class="newsletter-input" />
        </div>
        <div class="newsletter-col">
          <button class="newsletter-btn">Subscribe</button>
        </div>
      </div>
    </div>
  </div>

  <div class="tlp-border-wrap" data-aos="fade-left" data-aos-delay="100">
    <div class="container"><span class="tlp-border"></span></div>
  </div>

  <!-- Footer Middle -->
  <div class="footer-middle">
    <div class="container">
      <div class="row">
        <!-- Contact Info -->
        <div class="footer-box" data-aos="fade-left" data-aos-delay="200">
          <h3 class="footer-heading">Contact Us</h3>
          <ul class="footer-list">
            <li><i class="fas fa-map-marker-alt"></i> 329 Queensberry Street, CA 559</li>
            <li><i class="fas fa-envelope"></i> <a href="mailto:info@decorvista.com">info@decorvista.com</a></li>
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

  <!-- Footer Bottom -->
  <div class="footer-bottom" data-aos="fade-left" data-aos-delay="600">
    <div class="container">
      <p class="copy-right-text">© 2025 DecorVista. All Rights Reserved.</p>
    </div>
  </div>
</footer>

</body>
</html>
