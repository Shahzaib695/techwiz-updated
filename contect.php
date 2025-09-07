<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$response = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users1 WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $response = "duplicate";
    } else {
        $insert = "INSERT INTO users1 (name, email, message) VALUES ('$name', '$email', '$message')";
        if (mysqli_query($conn, $insert)) {
            $response = "success";
        } else {
            $response = "error";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <!-- <link rel="stylesheet" href="loader.css"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="contect.CSs">
  <title>Contact - Salon</title>
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- AOS Animation -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
  <!-- ✅ NAVBAR -->


    <!-- Navigation Links -->
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
    <!-- <input type="email" id="email" placeholder="Enter your email" required> -->
     <input type="email" id="checkoutEmail" placeholder="Enter your email" required>
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
  // const email = document.getElementById("email").value;
const email = document.getElementById("checkoutEmail").value;
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
      <h1 class="title">Contact</h1>
      <ul class="breadcrumbs">
        <li><a href="home.php">Home</a></li>
        <li>Contact</li>
      </ul>
    </div>
  </section>

  <section class="contact-wrapper">
  <!-- Left Section -->
  <div class="contact-left" data-aos="fade-right">
    <div class="contact-decor" data-aos="fade-down">
      <img src="https://radiustheme.com/demo/wordpress/themes/salion/wp-content/uploads/2021/03/element6.png" alt="Decor" />
    </div>
    <div class="contact-box">
      <h2>Contacts Us</h2>
      <p>Feel free to reach out for any queries or support. We’re here to help you anytime.</p>
      <ul class="address-info">
        <li data-aos="fade-up" data-aos-delay="100">
          <i class="fas fa-map-marker-alt"></i>
          <h3 class="address-title">Address</h3>
          <div class="address-description">962 Fifth Avenue Str, 3rd Floor-Trump Building NY 10006</div>
        </li>
        <li data-aos="fade-up" data-aos-delay="200">
          <i class="fas fa-clock"></i>
          <h3 class="address-title">Hours</h3>
          <div class="address-description">Mon – Sat: 8AM – 6PM<br>Sun: 11AM – 3PM</div>
        </li>
        <li data-aos="fade-up" data-aos-delay="300">
          <i class="fas fa-phone"></i>
          <h3 class="address-title">Contacts</h3>
          <div class="address-description"><strong>+54 647 541 4518</strong><br><strong>contact@example.com</strong></div>
        </li>
      </ul>
    </div>
  </div>
<!-- ✅ Contact Form Section -->
<div class="contact-right" data-aos="fade-left">
  <h2>Send Message</h2>
  <form class="contact-form" id="contactForm" method="POST" novalidate>
    <input type="text" name="name" id="name" placeholder="Your Name" required />
    <small id="nameError" style="color:red; display:block; font-size:13px;"></small>

    <input type="email" name="email" id="email" placeholder="Your Email" required />
    <small id="emailError" style="color:red; display:block; font-size:13px;"></small>

    <textarea name="message" id="message" placeholder="Your Message" rows="5" required></textarea>
    <small id="messageError" style="color:red; display:block; font-size:13px;"></small>

    <button type="submit" name="Submit">Send</button>
  </form>
</div>

</section>



<script>
document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault();

  let isValid = true;

  const name = document.getElementById("name");
  const email = document.getElementById("email");
  const message = document.getElementById("message");

  const nameError = document.getElementById("nameError");
  const emailError = document.getElementById("emailError");
  const messageError = document.getElementById("messageError");

  // Clear previous errors
  nameError.textContent = "";
  emailError.textContent = "";
  messageError.textContent = "";

  // Name: only letters & spaces
  const nameRegex = /^[A-Za-z\s]{3,30}$/;
  if (!nameRegex.test(name.value.trim())) {
    nameError.textContent = "Please enter a valid name (only letters & spaces)";
    isValid = false;
  }

  // Email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email.value.trim())) {
    emailError.textContent = "Please enter a valid email address";
    isValid = false;
  }

  // Message: at least 10 characters
  if (message.value.trim().length < 10) {
    messageError.textContent = "Message must be at least 10 characters long";
    isValid = false;
  }

  if (isValid) {
    this.submit(); // If all valid, submit the form
  }
});
</script>


<?php if ($response === "success"): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Message Sent!',
  text: 'Your message has been successfully submitted.',
  confirmButtonColor: '#3085d6'
});
</script>
<?php elseif ($response === "duplicate"): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Duplicate Email',
  text: 'This email is already used. Please try another.',
  confirmButtonColor: '#d33'
});
</script>
<?php elseif ($response === "error"): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Error',
  text: 'Something went wrong while submitting your message.',
  confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>




<!-- MAP SECTION -->
<section style="height: 600px; width: 100%; padding: 0; margin: 0; overflow: hidden;">
  <iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.962024302838!2d-74.011345684593!3d40.70714957933182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a219d79f5c1%3A0x2bc74e5ff4225c79!2sTrump%20Building%2C%2040%20Wall%20St%2C%20New%20York%2C%20NY%2010005%2C%20USA!5e0!3m2!1sen!2s!4v1654691317421!5m2!1sen!2s"
    width="100%"
    height="100%"
    style="border:0;"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</section>

  


 
 
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
