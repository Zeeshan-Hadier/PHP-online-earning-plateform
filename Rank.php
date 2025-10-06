<?php

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


   <div class="rankhero">
  <h4 class="mx-auto my-4">Your progress till Star 6</h4>
  <div class="d-flex justify-content-between">

    <div style="display:none;" id="selfpurchase">
      <?php echo "100"; ?>
    </div>

    <div style="display:none;" id="teampurchase">430</div>

    <div class="progress-container pt-3">
      <div class="title">Self Purchase</div>
      <div class="progress-circle" id="circle1">
        <div class="progress-value" id="value1">0%</div>
      </div>
      <div class="amount" id="amount1">0$</div>
    </div>

    <div class="progress-container pt-3">
      <div class="title">Direct Joinings</div>
      <div class="progress-circle" id="circle2">
        <div class="progress-value" id="value2">0%</div>
      </div>
      <div class="amount" id="amount2">0$</div>
    </div>

  </div>
</div>
<div class="levelcard mx-3"style="margin-top:-3rem;">
    <h4 class="mx-1 m-0 p-0 mt-2">Star 01</h4>
    <div class="mx-1 m-0 p-0 fsize">Unlock Your Level and Get Benefits of Level 01</div>
    <div class="d-flex mx-2">
        <div class="d-flex flex-column mx-auto fsize2 my-3">
            Self Purchase<br><span class="middletext m-0 p-0">(Atleat once)</span><h5 class="m-0 p-0 mx-2">$15.00</h5>
        </div>
          <div class="d-flex flex-column mx-auto fsize2 my-3">
            Team Investment<br><span class="middletext m-0 p-0">(New joinings)</span><h5 class="m-0 p-0 mx-2">$150.00</h5>
        </div>
    </div>
    <div class="border1 mx-auto my-1"></div>
</div>
<!------------------------------>
<div class="levelcard mx-3 my-4">
    <h4 class="mx-1 m-0 p-0 mt-2">Star 02</h4>
    <div class="mx-1 m-0 p-0 fsize">Unlock Your Level and Get Benefits of Star 02</div>
    <div class="d-flex mx-2">
        <div class="d-flex flex-column mx-auto fsize2 my-3">
            Self Purchase<br><span class="middletext m-0 p-0">(Atleat once)</span><h5 class="m-0 p-0 mx-2">$30.00</h5>
        </div>
          <div class="d-flex flex-column mx-auto fsize2 my-3">
            Team Investment<br><span class="middletext m-0 p-0">(New joinings)</span><h5 class="m-0 p-0 mx-2">$300.00</h5>
        </div>
    </div>
    <div class="border1 mx-auto my-1"></div>
</div>
<!------------------------------>
<!------------------------------>
<div class="levelcard mx-3 my-4">
    <h4 class="mx-1 m-0 p-0 mt-2">Star 03</h4>
    <div class="mx-1 m-0 p-0 fsize">Unlock Your Level and Get Benefits of Star 03</div>
    <div class="d-flex mx-2">
        <div class="d-flex flex-column mx-auto fsize2 my-3">
            Self Purchase<br><span class="middletext m-0 p-0">(Atleat once)</span><h5 class="m-0 p-0 mx-2">$50.00</h5>
        </div>
          <div class="d-flex flex-column mx-auto fsize2 my-3">
            Team Investment<br><span class="middletext m-0 p-0">(New joinings)</span><h5 class="m-0 p-0 mx-2">$500.00</h5>
        </div>
    </div>
    <div class="border1 mx-auto my-1"></div>
</div>
<!------------------------------>
<!------------------------------>
<div class="levelcard mx-3 my-4">
    <h4 class="mx-1 m-0 p-0 mt-2">Star 04</h4>
    <div class="mx-1 m-0 p-0 fsize">Unlock Your Level and Get Benefits of Star 04</div>
    <div class="d-flex mx-2">
        <div class="d-flex flex-column mx-auto fsize2 my-3">
            Self Purchase<br><span class="middletext m-0 p-0">(Atleat once)</span><h5 class="m-0 p-0 mx-2">$85.00</h5>
        </div>
          <div class="d-flex flex-column mx-auto fsize2 my-3">
            Team Investment<br><span class="middletext m-0 p-0">(New joinings)</span><h5 class="m-0 p-0 mx-2">$850.00</h5>
        </div>
    </div>
    <div class="border1 mx-auto my-1"></div>
</div>
<!------------------------------>
<!------------------------------>
<div class="levelcard mx-3 my-4">
    <h4 class="mx-1 m-0 p-0 mt-2">Star 05</h4>
    <div class="mx-1 m-0 p-0 fsize">Unlock Your Level and Get Benefits of Star 05</div>
    <div class="d-flex mx-2">
        <div class="d-flex flex-column mx-auto fsize2 my-3">
            Self Purchase<br><span class="middletext m-0 p-0">(Atleat once)</span><h5 class="m-0 p-0 mx-2">$125.00</h5>
        </div>
          <div class="d-flex flex-column mx-auto fsize2 my-3">
            Team Investment<br><span class="middletext m-0 p-0">(New joinings)</span><h5 class="m-0 p-0 mx-2">$1200.00</h5>
        </div>
    </div>
    <div class="border1 mx-auto my-1"></div>
</div>
<!------------------------------>
<!------------------------------>
<div class="levelcard mx-3 my-4">
    <h4 class="mx-1 m-0 p-0 mt-2">Star 06</h4>
    <div class="mx-1 m-0 p-0 fsize">Unlock Your Level and Get Benefits of Star 06</div>
    <div class="d-flex mx-2">
        <div class="d-flex flex-column mx-auto fsize2 my-3">
            Self Purchase<br><span class="middletext m-0 p-0">(Atleat once)</span><h5 class="m-0 p-0 mx-2">$165.00</h5>
        </div>
          <div class="d-flex flex-column mx-auto fsize2 my-3">
            Team Investment<br><span class="middletext m-0 p-0">(New joinings)</span><h5 class="m-0 p-0 mx-2">$1650.00</h5>
        </div>
    </div>
    <div class="border1 mx-auto my-1"></div>
</div>
<!------------------------------>


<div class="my-4 footerspace"></div>
<!-------------Footer--------------->
<footer class="mt-5">
  <div class="footer-container">
    <!-- Home -->
    <div class="footer-item">
      <a href="https://index.php">
        <i class="fa-solid fa-house"></i>
        <span>Dashboard</span>
      </a>
    </div>

    <!-- Team -->
    <div class="footer-item">
      <a href="#">
        <i class="fa-solid fa-user-group"></i>
        <span>Team</span>
      </a>
    </div>

    <!-- Chat -->
    <div class="footer-item">
      <a href="https://profitlab.store/chat.php">
        <i class="fa-solid fa-comment"></i>
        <span>Chat</span>
      </a>
    </div>

    <!-- Deals -->
    <div class="footer-item">
      <a href="https://profitlab.store/homepage/home.php">
        <i class="fa-solid fa-handshake"></i>
        <span>Deals</span>
      </a>
    </div>
  </div>
</footer><footer class="mt-5">
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

