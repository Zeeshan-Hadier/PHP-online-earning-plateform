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
if (isset($_POST['depositbtn'])) {
    header("Location: deposit.php");
    exit(); // always use exit after header
}
if (isset($_POST['withdrawbtn'])) {
    header("Location: withdrawal.php");
    exit(); // always use exit after header
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

<script>
document.getElementById("telegramlink").addEventListener("click", function () {
    window.open(); // opens in new tab
});
</script>

        <a  href="https://t.me/profitlux" class="telegram mt-2" id="telegramlink">
        <img src="telegram.JPEG" alt="">
        </a>
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
    <div class="hero">
        <div class="header py-3 mx-3">
            <div class="propic my-auto">
               <img src="profile/<?php echo $profileImage.$extention; ?>" 
     alt="Profile Image" 
     class="img-fluid rounded-circle"style=" object-fit:cover;">

            </div>
            <div class="info mx-4 mt-2">
                <div><h6 class="m-0 p-0"><?php echo $username ?></h6></div>
                <div><?php echo $email;?></div>
            </div>
            <div class="balance">
                <div>Balance</div>
                <h5><?php echo $TotalBalance ."$";?></h5>
            </div>
        </div>
        <div class="marquee ">
            <marquee behavior="" direction="">Welcome to the ProfitLux, your trusted gateway to unlocking the limitless power of digital wealth.</marquee>
        </div>
        <div class="datainfo mt-3">
            <div class="d-flex mx-auto">Your current Balance</div>
            <div class="d-flex mx-auto">Your Total Investment</div>

        </div>
    </div>
    <div class="boxes d-flex mx-3">
        <div class="tbox d-flex mx-auto">
             <div class="upereye"><i class="fa-solid fa-eye"></i> </div>
             <div class="headingbox d-flex">
                <div class="b1 ms-2"><h6><i class="fa-solid fa-credit-card mt-2"></i></h6></div>
                <div class="b2 fw-bolder mx-auto my-auto"><h5>Balance</h5></div>
             </div>
             <div class="d-flex my-2">
             <div class="mx-auto mt-1 inboxs">current <h6><?php echo $TotalBalance;?>$</h6></div>
             <div class="mx-auto mt-1 inboxs">Pending <h6>0.00$</h6></div>
             </div>
             <div class="d-flex inboxs colorlight mx-4">
                <div>
                <i class="fa-solid fa-circle-info me-2 "></i>Pending Status will take 15 min to approve</div>
                </div>
        </div>
            <div class="tbox d-flex mx-auto"> 
                <div class="upereye"><i class="fa-solid fa-eye"></i> </div>
                <div class="headingbox d-flex">
                <div class="b1 ms-2"><h5><i class="fa-solid fa-seedling mt-2"></i></h5></div>
                <div class="b2 fw-bolder mx-auto my-auto"><h4>Invested</h4></div>
             </div>
             <div class="d-flex my-2">
             <div class="mx-auto mt-1 inboxs">current <h6><?php echo $totalInvest ;?></h6></div>
             <div class="mx-auto mt-1 inboxs">Luxes <h6><?php echo $totalluxes ;?></h6></div>
             </div>
             <div class="d-flex inboxs colorlight mx-4">
                <div>
                <i class="fa-solid fa-circle-info me-2 "></i>Pending Status will take 15 min to approve</div>
                </div>
            </div>
    </div>
    <div class="btnhero mx-3 d-flex">
        <form method="post" style="width:200%">
    <button type="submit" name="depositbtn" class="btntop">+ Deposit</button>
</form>
        <form method="post" style="width:200%">
            <button type="submit" name="withdrawbtn" class="btntop"> - Withdraw</button>
        </form>
    </div>

<!-------------------------------end of hero section-->
<div class="mx-4 my-5 sliderunderhero">
  <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="1stbanner.png" class="d-block w-100" alt="Banner" 
          style="object-fit: fill; height: 130px; background:black;">
      </div>
      <div class="carousel-item">
        <img src="bannera.png" class="d-block w-100" alt="Banner" 
          style="object-fit: fill; height: 130px; background:black;">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>
<!-----------end of slider ---------------------------->
<h3 class="mx-4 mt-3 m-0 p-0">Ways to earn</h3>
<div class="mx-4 m-0 p-0">Explore ways to earn money from profitLux</div>
<div class="mx-4 mt-4 mb-2 d-flex">
  
    <div class="byellow mx-auto">
        <div class="mt-2 mx-3 d-flex justify-content-between">
            <h4 class=" p-2" style="background-color:black; border-radius:50%;"><i class="fa-solid fa-leaf"></i></h4>
            <div class="mt-2 me-1">$0.00</div>
        </div>
        <div class="mx-3 fsize"><h5>ROI on Luxes</h5> Start investing in high demand luxes today and watch you profit growth</div>
    </div>

    <div class="bblack mx-auto">
         <div class="my-2 mx-3 d-flex justify-content-between">
            <h4 class=" p-2" style="background-color:#caa606dc; border-radius:50%;"><i class="fa-solid fa-user-plus"></i></h4>
            <div class="mt-2 me-1">$0.00</div>
        </div>
         <div class="mx-3 fsize"><h5>Referrals</h5> Invite your friends to invest in luxes </div>
    </div>
</div>
<div class="mx-4 mb-2 d-flex">

    <div class="bblack mx-auto"> <div class="my-2 mx-3 d-flex justify-content-between">
            <h4 class=" p-2" style="background-color:#caa606dc; border-radius:50%;"><i class="fa-solid fa-star"></i></h4>
            <div class="mt-2 me-1">$0.00</div>
        
    </div>
         <div class="mx-3 fsize"><h5>Level Upgrad</h5> Work hard and meet the requirments of Stars</div>
</div>

    <div class="byellow mx-auto">
         <div class="my-2 mx-3 d-flex justify-content-between">
            <h4 class=" p-2" style="background-color:black; border-radius:50%;"><i class="fa-solid fa-sack-dollar"></i></h4>
            <div class="mt-2 me-1">$0.00</div>
        </div>
         <div class="mx-3 fsize"><h5>Salary</h5>Reach on 5th or 6th star then get monthly salary</div>
    </div>
    
</div>
<!--------------------------end of ways of earning------------------------>

<div class="collectearn my-5 mx-4">
    <h3 class="m-0 p-0">Collect Profit</h3>
    <div class="fsize m-0 p-0">collect profit from luxes in which you invest</div>
    <?php
if (isset($_POST['openearn'])) {
    header("Location: earn.php");
    exit();
}
?>
    <form method="post" class="mb-5">
        <button name="openearn" class="btncollectearn my-3"> <h4>Check For profit</h4> </button>
    </form>
</div>
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