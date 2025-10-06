<?php
//..............................

session_start();
require 'db.php';
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}
$username = $_SESSION['username'];
$_SESSION['username'] = $username;
$sql = "SELECT SUM(investment) AS total FROM investment WHERE username = '$username'";
$result = $conn->query($sql);

$totalinvestment = 0; // default value

if ($result && $row = $result->fetch_assoc()) {
    $totalinvestment = $row['total'] ?? 0; // store the sum safely
}
//___________________________________________________________
//handling daily earning
date_default_timezone_set("Asia/Karachi");

$increment = $totalinvestment*0.05;
// Using procedural style (mysqli_query)
$stmt = $conn->prepare("SELECT balance FROM balance WHERE name = ?");
$stmt->bind_param("s", $username);  // s = string
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$balance = $row['balance'] ?? 0;
if ($result && $row = mysqli_fetch_assoc($result)) {
    $balance = $row['balance'];
}
    $sql = "SELECT btnstatus FROM users WHERE name='$username'";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        $hidden = $row['btnstatus'];}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['collect_btn'])) {
       
            $newbalance =$balance+$increment;

            // 1. Hide the button
            $update_sql5 = "UPDATE users SET btnstatus = 'hidden' WHERE name = '$username'";
            mysqli_query($conn, $update_sql5);
          $stmt = $conn->prepare("UPDATE balance SET balance = ? WHERE name = ?");
$stmt->bind_param("ds", $newbalance, $username); 
// "d" = double/decimal for balance, "s" = string for username
$stmt->execute();
$stmt->close();
            // 2. Update balance + timestamps
            $update_time = date("Y-m-d H:i:s"); 
            $target_time = date("Y-m-d H:i:s", strtotime($update_time . " +1 day"));
            $update_sql = "UPDATE users
                           SET  last_update='$update_time', next_update='$target_time'
                           WHERE name='$username'";
            mysqli_query($conn, $update_sql);

            // 3. Redirect AFTER all updates
         echo "<script>window.location.href='earn.php';</script>";
            exit;
        
    }
}



    $sql = "SELECT next_update FROM users WHERE name='$username'";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    $next_update = $row['next_update'];  // e.g. "2025-09-01 19:50:00"

    // Current time (server time)
    $current_time = date("Y-m-d H:i:s");

    // Convert both times to UNIX timestamps
    $next_ts = strtotime($next_update);
    $current_ts = strtotime($current_time);

    // Calculate difference in seconds
    $diff = $next_ts - $current_ts;

    if ($diff > 0) {
        // Convert into H:i:s
        $hours   = floor($diff / 3600);
        $minutes = floor(($diff % 3600) / 60);
        $seconds = $diff % 60;

        // Show as  HH:MM:SS
       
          $update_sql1 = "UPDATE users SET btnstatus = 'hidden' WHERE Name = '$username'";
          mysqli_query($conn, $update_sql1);
    } else {
         $update_sql1 = "UPDATE users SET btnstatus = ' ' WHERE name = '$username'";
          mysqli_query($conn, $update_sql1);
  // already passed
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
  <style>
    .hidden{
    display: none;
}
 .timercard {
            font-size: 12px;
              box-shadow: 1px 10px 5px rgba(0, 0, 0, 0.3);
             
           
              display: flex;
              justify-content: center;
              align-items: center;
              cursor: pointer;
        }
        
        
        #buttoncollectit{
          width: 100%;
          background-color:#e2a80a;
        }
    </style>
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
<div class="my-4 footerspace"></div>
<div class="d-flex justify-content-between px-4 mx-3 py-2"style="box-shadow: 0px 0px 8px #e2a80a;">
<div>Investment in Luxes</div>
<div><?php
echo $totalinvestment;
?>$</div>
</div>
<!---------------------------------------------------->
<div class="d-flex "><div class="mx-auto my-5 p-5" style="box-shadow: 0px 0px 15px #ffbb00ff;">
 <h4 class="card-title"><u>Your Profit From Luxes</u></h4>






   <p class="card-text text-center"> <div class="timercard ">
                       <h5 class="card-title">
<?php 

$sql = "SELECT next_update FROM users WHERE name='$username'";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    $next_update = $row['next_update'];  // e.g. "2025-09-01 19:50:00"
    // Convert both times to UNIX timestamps
    $next_ts = strtotime($next_update);
    $current_ts = time();
    // Difference in seconds
    $diff = $next_ts - $current_ts;
    if ($diff > 0) {
        echo "<span id='timer'></span>"; // placeholder for countdown
        echo "<script>
        let diff = $diff; // seconds from PHP

        function repeatTask() {
            if (diff <= 0) {
                document.getElementById('timer').innerHTML = '00:00:00';
                return;
            }

            let hours = Math.floor(diff / 3600);
            let minutes = Math.floor((diff % 3600) / 60);
            let seconds = diff % 60;

            document.getElementById('timer').innerHTML =
                ('0' + hours).slice(-2) + ':' +
                ('0' + minutes).slice(-2) + ':' +
                ('0' + seconds).slice(-2);

            diff--; // decrease by 1 second
        }

        repeatTask(); // run immediately
        setInterval(repeatTask, 1000);
        </script>";
    }
    else{
         echo "<span id='timer'></span>"; 
  echo"<script>
  
   document.getElementById('timer').innerHTML ='00:00:00<br>';
   
  </script>";
  
}
}
?>
</h5>

            </div></p>





             <p class="card-text text-center"> <div class="timercard ">
                        <h5 class="card-title">Daily Profit :<?php echo $increment ?></h5>
            </div></p>
           <?php
// --- PHP Part: Fetch data from DB ---
$sql = "SELECT btnstatus, next_update FROM users WHERE name='$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$btnstatus = $row['btnstatus'];
$next_update = $row['next_update'];
?>

<!-- Collect Button -->
<form method="POST" action="">
    <button id="buttoncollectit" type="submit" name="collect_btn"
        class="btn btn-primary <?php echo $btnstatus; ?>">
        Collect ROI
    </button>
</form>

<script>
// --- JS Part: Auto-check every second ---
function checkButtonStatus() {
    fetch("check_status.php")  // separate backend check
    .then(res => res.json())
    .then(data => {
        let btn = document.getElementById("buttoncollectit");

        if (data.showButton === true) {
            btn.style.display = "inline-block"; // show
        } else {
            btn.style.display = "none"; // hide
        }
    })
    .catch(err => console.error(err));
}

// run every second
setInterval(checkButtonStatus, 1000);
</script>

  

  </div>
</div>         <ul class="my-5 mx-3">
  <li>Your daily ROI is generated automatically based on your active Luxes</li>
  <li>ROI can only be collected once every 24 hours</li>
  <li>Click the "Collect ROI" button to receive your earnings</li>
  <li>Collected ROI will be added instantly to your account balance</li>
  <li>Uncollected ROI does not accumulate — make sure to claim it daily</li>
  <h6>Thanks</h6>
</ul>
 
</div></div>

</div></div>


<div class="my-4 footerspace"></div>
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

