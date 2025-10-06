<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}
$username = $_SESSION['username'];
$_SESSION['username'] = $username;

//___________________________________________________________________________
//___________________________________________________________________________
if (isset($_POST['deposit'])) {
    $amount = $_POST['amount'];
    $screenshot = $_FILES['screenshot'];

    // Make sure the deposit folder exists
    $targetDir = "deposit/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate unique filename
    $fileName = time() . "_" . basename($screenshot["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if (in_array($fileType, $allowedTypes)) {
        // Upload file
        if (move_uploaded_file($screenshot["tmp_name"], $targetFilePath)) {
            
            // Insert into database
            $sql = "INSERT INTO deposit (name, amount, Screenshot) 
                    VALUES ('$username', '$amount', '$targetFilePath')";
            
            if ($conn->query($sql) === TRUE) {
               echo '
    <!-- Spinner -->
    <div id="spinner" style="
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 3000;
    ">
        <div class="loader"></div>
    </div>

    <!-- Custom Popup -->
    <div id="customPopup" style="
        display:none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        color: #333;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 2000;
        text-align: center;
        width: 320px;
    ">
        <h4 style="margin-bottom:10px; color:green;">Deposit Submitted!</h4>
        <p>Your deposit request has been received and is currently <b>pending approval</b>. 
        It will be approved within <b>1 hour</b>, after which you can use it to buy Luxes.</p>

        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 14px; background:#dbb404; border:none; color:white; border-radius:6px; cursor:pointer;">
            Close
        </button>
    </div>

    <style>
        body.loading {
          overflow: hidden; /* 🚫 hide scroll bar */
        }
        .loader {
          border: 6px solid #f3f3f3;
          border-top: 6px solid #dbb404;
          border-radius: 50%;
          width: 55px;
          height: 55px;
          animation: spin 1s linear infinite;
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
    </style>

    <script>
      document.body.classList.add("loading"); // hide scrollbar
      window.addEventListener("load", function() {
          setTimeout(function(){
              document.getElementById("spinner").style.display = "none";
              document.getElementById("customPopup").style.display = "block";
              document.body.classList.remove("loading"); // restore scroll
          }, 1500);
      });
    </script>
';

            } else {
                echo "Database Error: " . $conn->error;
            }
        } else {
            echo "<p style='color:red;'>Error uploading file. Please try again.</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid file type. Only JPG, PNG, and GIF are allowed.</p>";
    }
}
//___________________________________________________________________________
//___________________________________________________________________________
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProfitLux</title>
    <link rel="stylesheet" href="style.css?v2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

</head>
<body>
<div class="bodycon">
  
    <div class="nav ">
       <h2> 
        
  <!------------->
<!-- Toggle button -->
<h2>
  <button class="zindx mx-2 p-1 btn btn-outline-light" 
          style="font-size:20px; width:40px;" 
          id="toggleBtn">☰</button>
</h2>
        <div class="logo mx-auto">
            <img src="logo.jpg" class="m-1">
            <div class="logotext">
            <h1>Profit Lux</h1><div class="smalltext">UNLOCK THE POWER OF Luxury ASSETS</div></div>
            </div>    
        <div class="telegram mt-2">
        <img src="telegram.JPEG" alt="">
        </div>
    </div>
    <!------------->
    
<!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Slider -->
<div id="slider" class="slider">
  <style>
     .sliderlinkshow{
      text-decoration: none;
      text-color:white;
      border:2px solid white;
      border-radius: 2px;
      padding: 0.5rem;
    }
    a.sliderlinkshow {
  color: #ffffff !important;        /* white text */
  text-decoration: none !important; /* no underline */
  outline: none !important; 
  box-shadow: none !important; 
  background: transparent !important;
  transition: none !important;
}

/* Keep it the same on all states */
a.sliderlinkshow:link,
a.sliderlinkshow:visited,
a.sliderlinkshow:hover,
a.sliderlinkshow:active,
a.sliderlinkshow:focus {
  color: #ffffff !important;
  text-decoration: none !important;
}
    </style>
  <span class="close-btn" id="closeBtn">&times;</span>
  <div class="slider-content">
    <h6>Profit Lux</h6>
  
    <a href="https://profitlux.site/profile.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Profile</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/luxes.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>luxes</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/deposit.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Deposit Amount</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/withdrawal.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Withdaw Amount</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/team.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>My Team</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/rank.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Account Stars</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/d-history.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Deposit History</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/w-history.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Withdrawal History</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
    <a href="https://profitlux.site/logout.php" class="sliderlinkshow my-2 d-flex justify-content-between">
      <div>Log out</div>
      <div><i class="fa-solid fa-greater-than"></i></div>
    </a>
  </div>
</div>

<!------------------------------>
<style>
    .adress{
        padding:1rem;
       box-shadow: 0px 0px 8px #e2a80a;
       display:flex;
       border:2px solid #e2a80a;
       flex-direction:column;
       justify-content:center;
       align-items:center;
    }
    .color{
        color:#e2a80a;
    }
        .form-container {
      background: rgba(0, 0, 0, 0.8);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 5px rgba(255, 183, 3, 0.8); /* glowing yellow */
      width: 350px;
      text-align: center;
    }

    .form-container h2 {
      color: #ffb703; /* dark yellow heading */
      margin-bottom: 20px;
    }

    .form-container input[type="number"],
    .form-container input[type="file"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 8px;
      border: none;
      outline: none;
      background: #fff;
    }

    .form-container button {
      width: 100%;
      padding: 3px;
      margin-top: 15px;
      border: none;
      border-radius: 8px;
      background: linear-gradient(90deg, #ffb703, white);
      color: #000000ff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .form-container button:hover {
      background: linear-gradient(90deg, white, #ffb703);
      transform: scale(1.05);
    }
    </style>
    <div class="my-4 footerspace"></div>
<div class="adress mx-5">
    <h3 class="color">Bep20</h3>
    
    <div id="reflink" class="mx-auto fsize my-3" >0xd608862Db114Ae97eC48b97f7eD902a2C3DEA88B</div>
<div class="mx-auto reflink" id="buttonofcopyreflink" style="cursor:pointer;">
      
        <h6 class="py-1 my-auto">
        <i class="fa-solid fa-copy mx-3"></i> Copy Bep Address</h6>
      </div>
</div>
<hr>
 <script>
    function customAlert(message) {
  // remove old popup if exists
  const oldPopup = document.getElementById("customAlertBox");
  if (oldPopup) oldPopup.remove();

  // create wrapper
  const popup = document.createElement("div");
  popup.id = "customAlertBox";
  popup.style.position = "fixed";
  popup.style.top = "50%";
  popup.style.left = "50%";
  popup.style.transform = "translate(-50%, -50%)";
  popup.style.background = "white";
  popup.style.color = "#333";
  popup.style.padding = "20px 30px";
  popup.style.borderRadius = "12px";
  popup.style.boxShadow = "0 4px 15px rgba(0,0,0,0.2)";
  popup.style.zIndex = "2000";
  popup.style.textAlign = "center";
  popup.style.width = "320px";

  // content
  popup.innerHTML = `
    <h4 style="margin-bottom:10px;">Copied</h4>
    <p>${message}</p>
    <button id="customAlertCloseBtn" 
            style="margin-top:10px; padding:4px 10px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
      OK
    </button>
  `;

  // add to DOM
  document.body.appendChild(popup);

  // close handler
  document.getElementById("customAlertCloseBtn").onclick = function() {
    popup.remove();
  };
}
        const copyBtnlink = document.getElementById("buttonofcopyreflink");
const reflink = document.getElementById("reflink");

if (copyBtnlink && reflink) {
  copyBtnlink.addEventListener("click", () => {
    navigator.clipboard.writeText(reflink.innerText)
      .then(() => customAlert( "The adress of Bep20 copied to clipboard"));
  });
}
        </script>
        <div class="mx-1 my-4">
          <ul>
            Your deposit request will be carefully reviewed and approved within 1 hour. Once approved, the deposited amount will instantly be added to your account balance, allowing you to explore and purchase exclusive Luxes seamlessly. Enjoy a smooth, secure, and rewarding experience with ProfitLux.
          </ul>
        </div>

<form class="form-container mx-auto my-5" method="POST" enctype="multipart/form-data">
    <h2>Deposit Amount</h2>
    <input type="number" name="amount" placeholder="Enter amount" required>
    <input type="file" name="screenshot" required>
    <button class="px-5" type="submit" name="deposit">Deposit</button>
  </form>
<ul class="my-5 mx-3">
  <li>Enter your deposit amount in the field provided</li>
  <li>Upload a valid payment proof (screenshot or receipt)</li>
  <li>Click the "Deposit" button to submit your request securely</li>
  <li>Wait for approval — deposits are reviewed within 1 hour</li>
  <li>Once approved, your balance will update and you can start purchasing Luxes instantly</li>
  <h6>thanks</h6>
</ul>
</div>

<div class="my-5 footerspace"></div>
<!-------------Footer--------------->
<footer class="mt-5">
  <div class="footer-container">
    <!-- Home -->
    <div class="footer-item">
      <a href="https://profitlux.site/index.php">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
      </a>
    </div>

    <!-- Team -->
    <div class="footer-item">
      <a href="https://profitlux.site/team.php">
        <i class="fa-solid fa-user-group"></i>
        <span>Team</span>
      </a>
    </div>

    <!-- Chat -->
    <div class="footer-item">
      <a href="https://profitlux.site/luxes.php">
       <i class="fa-brands fa-product-hunt"></i>
        <span>Luxes</span>
      </a>
    </div>
      <!-- Chat -->
    <div class="footer-item">
      <a href="https://profitlux.site/rank.php">
       <i class="fa-solid fa-medal"></i>
        <span>Rank</span>
      </a>
    </div>

    <!-- Deals -->
    <div class="footer-item">
      <a href="https://profitlux.site/profile.php">
        <i class="fa-solid fa-user"></i>
        <span>Profile</span>
      </a>
    </div>
  </div>
</footer>
<!--end of body-->
</div>
<script src="script.js"></script>
</body>
</html>

