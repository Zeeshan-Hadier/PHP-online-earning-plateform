<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}
$username = $_SESSION['username'];
$_SESSION['username'] = $username;
//___________________________________________________________

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdraw_amount'], $_POST['bp20_address'])) {
    include "db.php"; // your DB connection

    $withdraw_amount = (float) $_POST['withdraw_amount'];
    $withdraw_amount =$withdraw_amount - $withdraw_amount*0.1;
    $bp20_address    = $conn->real_escape_string($_POST['bp20_address']);

    // ✅ Step 1: Fetch balance from balance table
    $sql_balance = "SELECT balance FROM balance WHERE name = '$username'";
    $result_balance = $conn->query($sql_balance);

    if ($result_balance && $result_balance->num_rows > 0) {
        $row = $result_balance->fetch_assoc();
        $current_balance = (float) $row['balance'];

        // ✅ Step 2: Check if amount is less than 5
        if ($withdraw_amount < 5) {
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
        <h4 style="margin-bottom:10px; color:red;">Low balance!</h4>
        <p> 
        Your Withdawal Balance is less then<b>$5
        </b>so you cant withdraw this</p>

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
        }
        // ✅ Step 3: Check if amount is greater than available balance
        elseif ($withdraw_amount > $current_balance) {
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
        <h4 style="margin-bottom:10px; color:red;">Low balance!</h4>
        <p> 
        Your current balance is less so you cant withdraw this</p>

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
';}
        else {
            // ✅ Step 4: Insert withdrawal request
            $sql_insert = "INSERT INTO withdraw (name, state, amount, adress) 
                           VALUES ('$username', 'pending', '$withdraw_amount', '$bp20_address')";
            $result_insert = $conn->query($sql_insert);

            if ($result_insert) {
                // ✅ Step 5: Subtract from balance and update
                $new_balance = $current_balance - $withdraw_amount;
                $sql_update = "UPDATE balance SET balance = '$new_balance' WHERE name = '$username'";
                $result_update = $conn->query($sql_update);

                if ($result_update) {
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
        <h4 style="margin-bottom:10px; color:green;">Withdarawal Submited!</h4>
        <p> 
        Your withdarawal is in pending state this will be approved within <b>1 hour
        </b>7% charges of gass fee and 3% charges of service charges will be applied</p>

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
                    echo "<p style='color:red;'>⚠️ Error updating balance: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color:red;'>⚠️ Error inserting withdrawal request: " . $conn->error . "</p>";
            }
        }
    } else {
        echo "<p style='color:red;'>⚠️ Error fetching balance for user $username.</p>";
    }

    $conn->close();
}
//_______________________________________________________________
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
<!-------------------------Withdarawal page-------------------------------------->
<style>
    .withdraw-form {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      width: 90%;
      max-width: 400px;
    }
    .withdraw-form h2 {
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }
    .withdraw-form input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    .withdraw-form button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      background: #dbb404;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      color: #fff;
      cursor: pointer;
      transition: 0.3s ease;
    }
    .withdraw-form button:hover {
      background: #b79504;
    }
  </style>
  <div class="my-5">1</div>
   <form class="withdraw-form mx-auto mt-5 pt-5" method="POST">
    <h2>Withdrawal</h2>
    <input type="number" name="withdraw_amount" placeholder="Withdrawal Amount" required>
    <input type="text" name="bp20_address" placeholder="BP20 Address" required>
    <button type="submit">Submit</button>
  </form>
  <ul class="my-5 mx-3">
  <li>Enter the amount you wish to withdraw in the field provided</li>
  <li>Provide a valid BP20 wallet address carefully</li>
  <li>Click the "Submit" button to place your withdrawal request</li>
  <li>Wait for approval — withdrawals are usually processed within 24 hours</li>
  <li>Once approved, the funds will be transferred to your BP20 address securely</li>
</ul>



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

