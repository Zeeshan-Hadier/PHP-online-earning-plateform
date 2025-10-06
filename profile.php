<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}
$username = $_SESSION['username'];
$_SESSION['username'] = $username;
$sql = "SELECT * FROM users WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $email = $row['Email'];
    $refcode=$row['Refcode'];
}
$sql2 = "SELECT * FROM balance WHERE name = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $username);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($row = $result2->fetch_assoc()) {
    $TotalBalance = $row['balance'];  
}
// profile pic
// ✅ fetch user data
$sqlp = "SELECT profile FROM users WHERE name = ?";
$stmtp = $conn->prepare($sqlp);
$stmtp->bind_param("s", $username);
$stmtp->execute();
$resultp = $stmtp->get_result();

// ✅ check row exists
if ($row = $resultp->fetch_assoc()) {
    $profileImage = htmlspecialchars($row['profile']); 
    if($profileImage=="unknown"){
      $extention=".JPG";
    }else{
$extention=" ";
    }
}
$sql = "SELECT SUM(investment) AS total_invest, COUNT(*) AS totalluxes 
        FROM investment 
        WHERE username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$totalInvest = 0; 
$totalluxes = 0;

if ($row = $result->fetch_assoc()) {
    $totalInvest = $row['total_invest'] ?? 0;   // ✅ total investment
    $totalluxes  = $row['totalluxes'] ?? 0;     // ✅ number of rows
}
// Find the user's upliner
$sql = "SELECT upliner FROM users WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc() && !empty($row['upliner'])) {
    $upliner = $row['upliner']; // use the upliner from DB
} else {
    // No upliner → get profitlux's Refcode
    $stmtDefault = $conn->prepare("SELECT Refcode FROM users WHERE name = 'Profit_Lux'");
    $stmtDefault->execute();
    $resultDefault = $stmtDefault->get_result();
    if ($rowDefault = $resultDefault->fetch_assoc()) {
        $upliner = $rowDefault['Refcode'];
    }
}

// Now get upliner details
$stmtu = $conn->prepare("SELECT name, profile, created_at, email FROM users WHERE Refcode = ?");
$stmtu->bind_param("s", $upliner);
$stmtu->execute();
$resultu = $stmtu->get_result();

if ($row = $resultu->fetch_assoc()) {
    $uplinerName    = $row['name'];
    $uplinerProfile = $row['profile'];
    $uplinerCreated = $row['created_at'];
    $uplinerEmail   = $row['email'];
}

// Handle extension for image
if ($uplinerProfile == "unknown") {
    $upextention = ".JPG";
} else {
    $upextention = "";
}
///_____________________________________________uploading profile picture
if (isset($_POST['upload-profile']) && !empty($_FILES['profile-pic']['name'])) {
      // File details
        $fileTmpPath = $_FILES['profile-pic']['tmp_name'];
        $fileName = basename($_FILES['profile-pic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Allow only images
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileExt, $allowedExts)) {
            echo "❌ Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
            exit;
        }

        // Unique file name
        $newFileName = uniqid("profile_", true) . "." . $fileExt;
        $uploadDir = "profile/";
        $destPath = $uploadDir . $newFileName;

        // Move file
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Save only filename into DB
            $sql = "UPDATE users SET profile = ? WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newFileName, $username);

            if ($stmt->execute()) {
                echo " ";
            } else {
                echo "❌ Database error: " . $conn->error;
            }
        } else {
            echo " ";
        }
    } else {
        echo " ";
}
?>
<?php
// Assume you already have $conn (MySQLi connection) and $username set
if (isset($_POST['update_password_btn'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Fetch stored password
    $stmt = $conn->prepare("SELECT password FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $db_password = $row['password'];

        // ✅ if you are storing plain text passwords (not recommended!)
        if ($old_password === $db_password) {
            $update = $conn->prepare("UPDATE users SET password = ? WHERE name = ?");
            $update->bind_param("ss", $new_password, $username);
            if ($update->execute()) {
                echo '
<!-- Custom Popup -->
<div id="popup" style="display:flex; position:fixed; top:0; left:0; width:100%; height:100%; 
background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
  <div style="background:#fff; padding:20px 30px; border-radius:10px; text-align:center; 
  width:300px; max-width:90%; box-shadow:0 5px 15px rgba(0,0,0,0.3); position:relative;">
    <span onclick="this.closest(`#popup`).style.display=`none`" 
    style="position:absolute; top:10px; right:15px; font-size:24px; font-weight:bold; 
    cursor:pointer; color:#333;">&times;</span>
    <p>Password updated successfully!</p>
  </div>
</div>';

            } else {
              echo '
<!-- Custom Popup -->
<div id="popup" style="display:flex; position:fixed; top:0; left:0; width:100%; height:100%; 
background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
  <div style="background:#fff; padding:20px 30px; border-radius:10px; text-align:center; 
  width:300px; max-width:90%; box-shadow:0 5px 15px rgba(0,0,0,0.3); position:relative;">
    <span onclick="this.closest(`#popup`).style.display=`none`" 
    style="position:absolute; top:10px; right:15px; font-size:24px; font-weight:bold; 
    cursor:pointer; color:#333;">&times;</span>
    <p>pasword update failed!</p>
  </div>
</div>';

            }
        } else {
           echo '
<!-- Custom Popup -->
<div id="popup" style="display:flex; position:fixed; top:0; left:0; width:100%; height:100%; 
background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
  <div style="background:#fff; padding:20px 30px; border-radius:10px; text-align:center; 
  width:300px; max-width:90%; box-shadow:0 5px 15px rgba(0,0,0,0.3); position:relative;">
    <span onclick="this.closest(`#popup`).style.display=`none`" 
    style="position:absolute; top:10px; right:15px; font-size:24px; font-weight:bold; 
    cursor:pointer; color:#333;">&times;</span>
    <p>Old password is incorrect!</p>
  </div>
</div>';

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProfitLux</title>
    <link rel="stylesheet" href="style.css">
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
    <div class="hero" style="height: 12vh;">
        <div class="header py-3 mx-3">
            <div class="propic my-auto">
               <img src="profile/<?php echo $profileImage.$extention; ?>" 
     alt="Profile Image" 
     class="img-fluid rounded-circle"style="  width: 100%;
  height: 100%;
  object-fit: cover;">
            </div>
      <form method="post" enctype="multipart/form-data" id="uploadForm">
  <!-- hidden file input -->
  <input hidden type="file" id="profile-pic" name="profile-pic" accept="image/*">

  <!-- hidden input to send flag -->
  <input type="hidden" name="upload-profile" value="1">

  <!-- button triggers file input instead of direct submit -->
  <button class="editbtn" type="button" id="uploadBtn">
    <i class="fa-solid fa-pen-to-square"></i>
  </button>
</form>
<script>
  const fileInput = document.getElementById("profile-pic");
  const uploadBtn = document.getElementById("uploadBtn");
  const form = document.getElementById("uploadForm");

  // when button is clicked → open file picker
  uploadBtn.addEventListener("click", () => {
    fileInput.click();
  });

  // when file is chosen → auto-submit form
  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      form.submit();
    }
  });
</script>
            <div class="info mx-4 mt-2">
                <div><h6 class="m-0 p-0"><?php echo $username ?></h6></div>
                <div><?php echo $email;?></div>
            </div>
            <div class="balance">
                <div>Balance</div>
                <h5><?php echo $TotalBalance ."$";?></h5>
            </div>
        </div>
    </div>
<!-------------------------------end of hero section-->
<div class="informationpro mt-2 mx-1 d-flex">
  <div class="sponser  text-center">
    <img src="profile/<?php echo $uplinerProfile . $upextention; ?>" 
         alt="Profile Image"
         class="img-fluid rounded-circle"
         style="object-fit:cover; width:3rem; height:3rem;">
    <h6 class="name p-0 m-0 mt-1"><?php echo $uplinerName; ?></h6>
    <div class="name p-0 m-0"><?php echo $uplinerEmail; ?></div>
    <div class="sponsorbtn">Sponsor</div>
  </div>
  <div class="teamdet mx-auto">
    <div class="div1pro">
      <div class="bgcolor">
        <i class="fa-solid fa-gift"></i>
      </div>
      <div class="textdivpro d-flex flex-column mx-2">
        <div class="text ms-5 ps-1">0</div>
        <div class="color2 mx-auto">Gifts From Sponser</div>
      </div>
    </div>
    <div class="div1pro mt-3">
      <div class="bgcolor">
        <i class="fa-solid fa-gifts"></i>
      </div>
      <div class="textdivpro d-flex flex-column mx-2">
        <div class="text ms-5 ps-1">0</div>
        <div class="color2 mx-auto">Gifts to Sponser</div>
      </div>
    </div>
  </div>
</div>
<!-----------------------------end of sponser---->
<div id="reflink" class="hidden" >http://localhost/fyneraLux/register.php?ref=<?php echo $refcode; ?></div>
<div class="mx-auto reflink" id="buttonofcopyreflink" style="cursor:pointer;">
     
      <div   >
        <h3 class="mx-4 my-auto">
        <i class="fa-solid fa-copy mx-3"></i> Copy Refral Link</h3>
      </div>
</div>
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
      .then(() => customAlert( reflink.innerText));
  });
}
        </script>
<!-------------------divs for investa and balance--------------------->
<div class="d-flex m-2 mb-4">
   <div class="boxpro mx-auto">
    <h6 class="color2p">Current Balance</h6>
    <h3><?php echo $TotalBalance ."$";?></h3>
   </div>
   <!-----------box 2------------------>
    <div class="boxpro mx-auto">
    <h6 class="color2p">Current Investment</h6>
    <h3><?php echo $totalInvest ."$";?></h3>
   </div>
</div>
<!------------------------Update your pas----------------------------->
    
  <style>
    

    .form-container {
      background: #111; /* dark grey box */
      padding: 20px;
      border-radius: 12px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #f9e58c; /* golden heading */
    }

    .form-container label {
      display: block;
      margin-bottom: 8px;
      font-size: 14px;
      color: #f9e58c;
    }

    .form-container input[type="password"],
    .form-container input[type="file"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      margin-bottom: 15px;
      background: #222;
      color: #fff;
    }

    .form-container input[type="file"] {
      background: #111;
      border: 1px solid #c68c00;
      padding: 8px;
    }

    .form-container button {
      width: 100%;
      padding-left: 12px;
      padding-right:12px;
      padding:3px;
      border: none;
      border-radius: 25px;
      background: linear-gradient(to right, #c68c00, #f9e58c);
      font-size: 16px;
      font-weight: 600;
      color: #000;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .form-container button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    }
  </style>

  <div class="form-container mx-auto mb-5">
    <h2>Update Your password</h2>
   <form method="post" id="updatePasswordForm">
  <label for="old_password">Old Password</label>
  <input type="password" id="old_password" name="old_password" placeholder="Enter Old password" required>

  <label for="new_password">New Password</label>
  <input type="password" id="new_password" name="new_password" placeholder="Enter New password" required>

  <button type="submit" name="update_password_btn">Update Password</button>
</form>
  </div>



        <!-------------------------------footer section-->
<div class=" footerspace"></div>

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
