<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}
$username = $_SESSION['username'];
$sql2 = "SELECT * FROM balance WHERE name = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $username);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($row = $result2->fetch_assoc()) {
    $TotalBalance = $row['balance'];  
}
if (isset($_POST['lux1'])){
   
   if ($TotalBalance < 15.00) {
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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">⚠ Low Balance</h4>
        <p>Your balance is below <b>15.00</b>. Please recharge to continue.</p>
        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
   }else{
$sql = "INSERT INTO investment ( username , investment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$investment = 15.00; // fixed value
$stmt->bind_param("sd", $username, $investment); // s = string, i = integer
$stmt->execute();
    $newTotalBalance = $TotalBalance - 15.00;
    $sql = "UPDATE balance SET balance = ? WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $newTotalBalance, $username); // d = decimal/double, s = string
$stmt->execute();

// ✅ Step 1: Get upliner of given username
$sqllv1 = "SELECT upliner FROM users WHERE name = '$username'";
$resultlv1 = $conn->query($sqllv1);

$uplinerlv1 = null; // default value

if ($resultlv1 && $resultlv1->num_rows > 0) {
    $rowlv1 = $resultlv1->fetch_assoc();
    $uplinerlv1 = $rowlv1['upliner']; // store upliner in variable
}
if($uplinerlv1){

$sqllv1_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv1'";
$resultlv1_n = $conn->query($sqllv1_n);

$uplinername = null; // default value

if ($resultlv1_n && $resultlv1_n->num_rows > 0) {
    $rowlv1_n = $resultlv1_n->fetch_assoc();
    $uplinern = $rowlv1_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv11_balance = "SELECT balance FROM balance WHERE name = '$uplinern'";
$resultlv11_balance = $conn->query($sqllv11_balance);

$oldbalancelv11 = 0; // default value

if ($resultlv11_balance && $resultlv11_balance->num_rows > 0) {
    $rowlv11_balance = $resultlv11_balance->fetch_assoc();
    $oldbalancelv11 = $rowlv11_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv11 = $oldbalancelv11 + (15 * 0.09);

// ✅ Step 4: Update balance for upliner
$sqllv111_update = "UPDATE balance SET balance = '$newbalancelv11' WHERE name = '$uplinern'";
$resultlv111_update = $conn->query($sqllv111_update);

if ($resultlv111_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}
/////////////////////////////////////////////////LEVel 2 balance

// ✅ Step 1: Get upliner of given username
$sqllv2 = "SELECT upliner FROM users WHERE name = '$uplinern'";
$resultlv2 = $conn->query($sqllv2);

$uplinerlv2 = null; // default value

if ($resultlv2 && $resultlv2->num_rows > 0) {
    $rowlv2 = $resultlv2->fetch_assoc();
    $uplinerlv2 = $rowlv2['upliner']; // store upliner in variable
}
if($uplinerlv2){

$sqllv2_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv2'";
$resultlv2_n = $conn->query($sqllv2_n);

$uplinern2 = null; // default value

if ($resultlv2_n && $resultlv2_n->num_rows > 0) {
    $rowlv2_n = $resultlv2_n->fetch_assoc();
    $uplinern2 = $rowlv2_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv22_balance = "SELECT balance FROM balance WHERE name = '$uplinern2'";
$resultlv22_balance = $conn->query($sqllv22_balance);

$oldbalancelv22 = 0; // default value

if ($resultlv22_balance && $resultlv22_balance->num_rows > 0) {
    $rowlv22_balance = $resultlv22_balance->fetch_assoc();
    $oldbalancelv22 = $rowlv22_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv22 = $oldbalancelv22 + (15 * 0.04);

// ✅ Step 4: Update balance for upliner
$sqllv222_update = "UPDATE balance SET balance = '$newbalancelv22' WHERE name = '$uplinern2'";
$resultlv222_update = $conn->query($sqllv222_update);

if ($resultlv222_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}

/////////////////////////////////////////////////LEVel 3 balance

// ✅ Step 1: Get upliner of given username
$sqllv3 = "SELECT upliner FROM users WHERE name = '$uplinern2'";
$resultlv3 = $conn->query($sqllv3);

$uplinerlv3 = null; // default value

if ($resultlv3 && $resultlv3->num_rows > 0) {
    $rowlv3 = $resultlv3->fetch_assoc();
    $uplinerlv3 = $rowlv3['upliner']; // store upliner in variable
}
if($uplinerlv3){

$sqllv3_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv3'";
$resultlv3_n = $conn->query($sqllv3_n);

$uplinern3 = null; // default value

if ($resultlv3_n && $resultlv3_n->num_rows > 0) {
    $rowlv3_n = $resultlv3_n->fetch_assoc();
    $uplinern3 = $rowlv3_n['name']; // store name in variable
} 

// ✅ Step 3: Get old balance of upliner
$sqllv33_balance = "SELECT balance FROM balance WHERE name = '$uplinern3'";
$resultlv33_balance = $conn->query($sqllv33_balance);

$oldbalancelv33 = 0; // default value

if ($resultlv33_balance && $resultlv33_balance->num_rows > 0) {
    $rowlv33_balance = $resultlv33_balance->fetch_assoc();
    $oldbalancelv33 = $rowlv33_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv33 = $oldbalancelv33 + (15 * 0.02);

// ✅ Step 4: Update balance for upliner
$sqllv333_update = "UPDATE balance SET balance = '$newbalancelv33' WHERE name = '$uplinern3'";
$resultlv333_update = $conn->query($sqllv333_update);

if ($resultlv333_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}


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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">Successfully Invested </h4>
<p>You invested in Lux 1. <b>$15.00</b> has been deducted from your balance.</p>

        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
    
   }
}

///---------------------------------------------------------------------------lux 2
if (isset($_POST['lux2'])){
   
   if ($TotalBalance < 30.00) {
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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">⚠ Low Balance</h4>
        <p>Your balance is below <b>30.00</b>. Please recharge to continue.</p>
        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
   }else{
$sql = "INSERT INTO investment ( username , investment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$investment = 30.00; // fixed value
$stmt->bind_param("sd", $username, $investment); // s = string, i = integer
$stmt->execute();
    $newTotalBalance = $TotalBalance - 30.00;
    $sql = "UPDATE balance SET balance = ? WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $newTotalBalance, $username); // d = decimal/double, s = string
$stmt->execute();



// ✅ Step 1: Get upliner of given username
$sqllv1 = "SELECT upliner FROM users WHERE name = '$username'";
$resultlv1 = $conn->query($sqllv1);

$uplinerlv1 = null; // default value

if ($resultlv1 && $resultlv1->num_rows > 0) {
    $rowlv1 = $resultlv1->fetch_assoc();
    $uplinerlv1 = $rowlv1['upliner']; // store upliner in variable
}
if($uplinerlv1){

$sqllv1_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv1'";
$resultlv1_n = $conn->query($sqllv1_n);

$uplinername = null; // default value

if ($resultlv1_n && $resultlv1_n->num_rows > 0) {
    $rowlv1_n = $resultlv1_n->fetch_assoc();
    $uplinern = $rowlv1_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv11_balance = "SELECT balance FROM balance WHERE name = '$uplinern'";
$resultlv11_balance = $conn->query($sqllv11_balance);

$oldbalancelv11 = 0; // default value

if ($resultlv11_balance && $resultlv11_balance->num_rows > 0) {
    $rowlv11_balance = $resultlv11_balance->fetch_assoc();
    $oldbalancelv11 = $rowlv11_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv11 = $oldbalancelv11 + (30 * 0.09);

// ✅ Step 4: Update balance for upliner
$sqllv111_update = "UPDATE balance SET balance = '$newbalancelv11' WHERE name = '$uplinern'";
$resultlv111_update = $conn->query($sqllv111_update);

if ($resultlv111_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}
/////////////////////////////////////////////////LEVel 2 balance

// ✅ Step 1: Get upliner of given username
$sqllv2 = "SELECT upliner FROM users WHERE name = '$uplinern'";
$resultlv2 = $conn->query($sqllv2);

$uplinerlv2 = null; // default value

if ($resultlv2 && $resultlv2->num_rows > 0) {
    $rowlv2 = $resultlv2->fetch_assoc();
    $uplinerlv2 = $rowlv2['upliner']; // store upliner in variable
}
if($uplinerlv2){

$sqllv2_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv2'";
$resultlv2_n = $conn->query($sqllv2_n);

$uplinern2 = null; // default value

if ($resultlv2_n && $resultlv2_n->num_rows > 0) {
    $rowlv2_n = $resultlv2_n->fetch_assoc();
    $uplinern2 = $rowlv2_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv22_balance = "SELECT balance FROM balance WHERE name = '$uplinern2'";
$resultlv22_balance = $conn->query($sqllv22_balance);

$oldbalancelv22 = 0; // default value

if ($resultlv22_balance && $resultlv22_balance->num_rows > 0) {
    $rowlv22_balance = $resultlv22_balance->fetch_assoc();
    $oldbalancelv22 = $rowlv22_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv22 = $oldbalancelv22 + (30 * 0.04);

// ✅ Step 4: Update balance for upliner
$sqllv222_update = "UPDATE balance SET balance = '$newbalancelv22' WHERE name = '$uplinern2'";
$resultlv222_update = $conn->query($sqllv222_update);

if ($resultlv222_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}

/////////////////////////////////////////////////LEVel 3 balance

// ✅ Step 1: Get upliner of given username
$sqllv3 = "SELECT upliner FROM users WHERE name = '$uplinern2'";
$resultlv3 = $conn->query($sqllv3);

$uplinerlv3 = null; // default value

if ($resultlv3 && $resultlv3->num_rows > 0) {
    $rowlv3 = $resultlv3->fetch_assoc();
    $uplinerlv3 = $rowlv3['upliner']; // store upliner in variable
}
if($uplinerlv3){

$sqllv3_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv3'";
$resultlv3_n = $conn->query($sqllv3_n);

$uplinern3 = null; // default value

if ($resultlv3_n && $resultlv3_n->num_rows > 0) {
    $rowlv3_n = $resultlv3_n->fetch_assoc();
    $uplinern3 = $rowlv3_n['name']; // store name in variable
} 

// ✅ Step 3: Get old balance of upliner
$sqllv33_balance = "SELECT balance FROM balance WHERE name = '$uplinern3'";
$resultlv33_balance = $conn->query($sqllv33_balance);

$oldbalancelv33 = 0; // default value

if ($resultlv33_balance && $resultlv33_balance->num_rows > 0) {
    $rowlv33_balance = $resultlv33_balance->fetch_assoc();
    $oldbalancelv33 = $rowlv33_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv33 = $oldbalancelv33 + (30 * 0.02);

// ✅ Step 4: Update balance for upliner
$sqllv333_update = "UPDATE balance SET balance = '$newbalancelv33' WHERE name = '$uplinern3'";
$resultlv333_update = $conn->query($sqllv333_update);

if ($resultlv333_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}

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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">Successfully Invested </h4>
<p>You invested in Lux 2. <b>$30.00</b> has been deducted from your balance.</p>

        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
    
   }
}
///-------------------------------------------------------------------------------------lux 3
if (isset($_POST['lux3'])){
   
   if ($TotalBalance < 50.00) {
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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">⚠ Low Balance</h4>
        <p>Your balance is below <b>30.00</b>. Please recharge to continue.</p>
        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
   }else{
$sql = "INSERT INTO investment ( username , investment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$investment = 50.00; // fixed value
$stmt->bind_param("sd", $username, $investment); // s = string, i = integer
$stmt->execute();
    $newTotalBalance = $TotalBalance - 50.00;
    $sql = "UPDATE balance SET balance = ? WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $newTotalBalance, $username); // d = decimal/double, s = string
$stmt->execute();



// ✅ Step 1: Get upliner of given username
$sqllv1 = "SELECT upliner FROM users WHERE name = '$username'";
$resultlv1 = $conn->query($sqllv1);

$uplinerlv1 = null; // default value

if ($resultlv1 && $resultlv1->num_rows > 0) {
    $rowlv1 = $resultlv1->fetch_assoc();
    $uplinerlv1 = $rowlv1['upliner']; // store upliner in variable
}
if($uplinerlv1){

$sqllv1_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv1'";
$resultlv1_n = $conn->query($sqllv1_n);

$uplinername = null; // default value

if ($resultlv1_n && $resultlv1_n->num_rows > 0) {
    $rowlv1_n = $resultlv1_n->fetch_assoc();
    $uplinern = $rowlv1_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv11_balance = "SELECT balance FROM balance WHERE name = '$uplinern'";
$resultlv11_balance = $conn->query($sqllv11_balance);

$oldbalancelv11 = 0; // default value

if ($resultlv11_balance && $resultlv11_balance->num_rows > 0) {
    $rowlv11_balance = $resultlv11_balance->fetch_assoc();
    $oldbalancelv11 = $rowlv11_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv11 = $oldbalancelv11 + (50 * 0.09);

// ✅ Step 4: Update balance for upliner
$sqllv111_update = "UPDATE balance SET balance = '$newbalancelv11' WHERE name = '$uplinern'";
$resultlv111_update = $conn->query($sqllv111_update);

if ($resultlv111_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}
/////////////////////////////////////////////////LEVel 2 balance

// ✅ Step 1: Get upliner of given username
$sqllv2 = "SELECT upliner FROM users WHERE name = '$uplinern'";
$resultlv2 = $conn->query($sqllv2);

$uplinerlv2 = null; // default value

if ($resultlv2 && $resultlv2->num_rows > 0) {
    $rowlv2 = $resultlv2->fetch_assoc();
    $uplinerlv2 = $rowlv2['upliner']; // store upliner in variable
}
if($uplinerlv2){

$sqllv2_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv2'";
$resultlv2_n = $conn->query($sqllv2_n);

$uplinern2 = null; // default value

if ($resultlv2_n && $resultlv2_n->num_rows > 0) {
    $rowlv2_n = $resultlv2_n->fetch_assoc();
    $uplinern2 = $rowlv2_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv22_balance = "SELECT balance FROM balance WHERE name = '$uplinern2'";
$resultlv22_balance = $conn->query($sqllv22_balance);

$oldbalancelv22 = 0; // default value

if ($resultlv22_balance && $resultlv22_balance->num_rows > 0) {
    $rowlv22_balance = $resultlv22_balance->fetch_assoc();
    $oldbalancelv22 = $rowlv22_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv22 = $oldbalancelv22 + (50 * 0.04);

// ✅ Step 4: Update balance for upliner
$sqllv222_update = "UPDATE balance SET balance = '$newbalancelv22' WHERE name = '$uplinern2'";
$resultlv222_update = $conn->query($sqllv222_update);

if ($resultlv222_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}

/////////////////////////////////////////////////LEVel 3 balance

// ✅ Step 1: Get upliner of given username
$sqllv3 = "SELECT upliner FROM users WHERE name = '$uplinern2'";
$resultlv3 = $conn->query($sqllv3);

$uplinerlv3 = null; // default value

if ($resultlv3 && $resultlv3->num_rows > 0) {
    $rowlv3 = $resultlv3->fetch_assoc();
    $uplinerlv3 = $rowlv3['upliner']; // store upliner in variable
}
if($uplinerlv3){

$sqllv3_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv3'";
$resultlv3_n = $conn->query($sqllv3_n);

$uplinern3 = null; // default value

if ($resultlv3_n && $resultlv3_n->num_rows > 0) {
    $rowlv3_n = $resultlv3_n->fetch_assoc();
    $uplinern3 = $rowlv3_n['name']; // store name in variable
} 

// ✅ Step 3: Get old balance of upliner
$sqllv33_balance = "SELECT balance FROM balance WHERE name = '$uplinern3'";
$resultlv33_balance = $conn->query($sqllv33_balance);

$oldbalancelv33 = 0; // default value

if ($resultlv33_balance && $resultlv33_balance->num_rows > 0) {
    $rowlv33_balance = $resultlv33_balance->fetch_assoc();
    $oldbalancelv33 = $rowlv33_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv33 = $oldbalancelv33 + (50 * 0.02);

// ✅ Step 4: Update balance for upliner
$sqllv333_update = "UPDATE balance SET balance = '$newbalancelv33' WHERE name = '$uplinern3'";
$resultlv333_update = $conn->query($sqllv333_update);

if ($resultlv333_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}


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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">Successfully Invested </h4>
<p>You invested in Lux 3. <b>$50.00</b> has been deducted from your balance.</p>

        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
    
   }
}
///-------------------------------------------------------------------------------------lux 4
if (isset($_POST['lux4'])){
   
   if ($TotalBalance < 85.00) {
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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">⚠ Low Balance</h4>
        <p>Your balance is below <b>30.00</b>. Please recharge to continue.</p>
        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
   }else{
$sql = "INSERT INTO investment ( username , investment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$investment = 85.00; // fixed value
$stmt->bind_param("sd", $username, $investment); // s = string, i = integer
$stmt->execute();
    $newTotalBalance = $TotalBalance - 85.00;
    $sql = "UPDATE balance SET balance = ? WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $newTotalBalance, $username); // d = decimal/double, s = string
$stmt->execute();


// ✅ Step 1: Get upliner of given username
$sqllv1 = "SELECT upliner FROM users WHERE name = '$username'";
$resultlv1 = $conn->query($sqllv1);

$uplinerlv1 = null; // default value

if ($resultlv1 && $resultlv1->num_rows > 0) {
    $rowlv1 = $resultlv1->fetch_assoc();
    $uplinerlv1 = $rowlv1['upliner']; // store upliner in variable
}
if($uplinerlv1){

$sqllv1_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv1'";
$resultlv1_n = $conn->query($sqllv1_n);

$uplinername = null; // default value

if ($resultlv1_n && $resultlv1_n->num_rows > 0) {
    $rowlv1_n = $resultlv1_n->fetch_assoc();
    $uplinern = $rowlv1_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv11_balance = "SELECT balance FROM balance WHERE name = '$uplinern'";
$resultlv11_balance = $conn->query($sqllv11_balance);

$oldbalancelv11 = 0; // default value

if ($resultlv11_balance && $resultlv11_balance->num_rows > 0) {
    $rowlv11_balance = $resultlv11_balance->fetch_assoc();
    $oldbalancelv11 = $rowlv11_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv11 = $oldbalancelv11 + (85 * 0.09);

// ✅ Step 4: Update balance for upliner
$sqllv111_update = "UPDATE balance SET balance = '$newbalancelv11' WHERE name = '$uplinern'";
$resultlv111_update = $conn->query($sqllv111_update);

if ($resultlv111_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}
/////////////////////////////////////////////////LEVel 2 balance

// ✅ Step 1: Get upliner of given username
$sqllv2 = "SELECT upliner FROM users WHERE name = '$uplinern'";
$resultlv2 = $conn->query($sqllv2);

$uplinerlv2 = null; // default value

if ($resultlv2 && $resultlv2->num_rows > 0) {
    $rowlv2 = $resultlv2->fetch_assoc();
    $uplinerlv2 = $rowlv2['upliner']; // store upliner in variable
}
if($uplinerlv2){

$sqllv2_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv2'";
$resultlv2_n = $conn->query($sqllv2_n);

$uplinern2 = null; // default value

if ($resultlv2_n && $resultlv2_n->num_rows > 0) {
    $rowlv2_n = $resultlv2_n->fetch_assoc();
    $uplinern2 = $rowlv2_n['name']; // store name in variable
} 

// ✅ Step 2: Get old balance of upliner
$sqllv22_balance = "SELECT balance FROM balance WHERE name = '$uplinern2'";
$resultlv22_balance = $conn->query($sqllv22_balance);

$oldbalancelv22 = 0; // default value

if ($resultlv22_balance && $resultlv22_balance->num_rows > 0) {
    $rowlv22_balance = $resultlv22_balance->fetch_assoc();
    $oldbalancelv22 = $rowlv22_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv22 = $oldbalancelv22 + (85 * 0.04);

// ✅ Step 4: Update balance for upliner
$sqllv222_update = "UPDATE balance SET balance = '$newbalancelv22' WHERE name = '$uplinern2'";
$resultlv222_update = $conn->query($sqllv222_update);

if ($resultlv222_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}

/////////////////////////////////////////////////LEVel 3 balance

// ✅ Step 1: Get upliner of given username
$sqllv3 = "SELECT upliner FROM users WHERE name = '$uplinern2'";
$resultlv3 = $conn->query($sqllv3);

$uplinerlv3 = null; // default value

if ($resultlv3 && $resultlv3->num_rows > 0) {
    $rowlv3 = $resultlv3->fetch_assoc();
    $uplinerlv3 = $rowlv3['upliner']; // store upliner in variable
}
if($uplinerlv3){

$sqllv3_n = "SELECT name FROM users WHERE Refcode = '$uplinerlv3'";
$resultlv3_n = $conn->query($sqllv3_n);

$uplinern3 = null; // default value

if ($resultlv3_n && $resultlv3_n->num_rows > 0) {
    $rowlv3_n = $resultlv3_n->fetch_assoc();
    $uplinern3 = $rowlv3_n['name']; // store name in variable
} 

// ✅ Step 3: Get old balance of upliner
$sqllv33_balance = "SELECT balance FROM balance WHERE name = '$uplinern3'";
$resultlv33_balance = $conn->query($sqllv33_balance);

$oldbalancelv33 = 0; // default value

if ($resultlv33_balance && $resultlv33_balance->num_rows > 0) {
    $rowlv33_balance = $resultlv33_balance->fetch_assoc();
    $oldbalancelv33 = $rowlv33_balance['balance']; // store balance in variable
}

// ✅ Step 3: Calculate new balance (adding commission/reward)
$newbalancelv33 = $oldbalancelv33 + (85 * 0.02);

// ✅ Step 4: Update balance for upliner
$sqllv333_update = "UPDATE balance SET balance = '$newbalancelv33' WHERE name = '$uplinern3'";
$resultlv333_update = $conn->query($sqllv333_update);

if ($resultlv333_update === TRUE) {
    echo " ";
} else {
    echo "Error updating balance (lv111): " . $conn->error;
}
}
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
        width: 300px;
    ">
        <h4 style="margin-bottom:10px;">Successfully Invested </h4>
<p>You invested in Lux 4. <b>$85.00</b> has been deducted from your balance.</p>

        <button onclick="document.getElementById(\'customPopup\').style.display=\'none\'" 
                style="margin-top:10px; padding:6px 12px; background:#dbb404; border:none; color:white; border-radius:5px; cursor:pointer;">
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
          width: 50px;
          height: 50px;
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
              document.body.classList.remove("loading"); // show scrollbar back
          }, 1500);
      });
    </script>
    ';
    
   }
}
?>
<style>
   body.loading {
  overflow: hidden; /* 🚫 disables scrolling */
html, body {
  margin: 0;
  padding: 0;
  overflow-x: hidden; /* 🚫 hides horizontal scrollbar */
}

body::-webkit-scrollbar {
  display: none; /* hides scrollbar in Chrome, Edge, Safari */
}

body {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}
}

    </style>
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
<div class="d-flex">
<h1 class="mx-auto mt-5 pt-5">
   Luxes
   <div class="border1 my-1" style="width:100%"></div>
   
</h1> </div>
<div class="d-flex flex-column">
    <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux1</h2></div>
    <div class="availbtn px-3 mt-1">Available</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/1.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 15$</div>
        <div>Daily ROI : 0.75$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form method="POST" class="w-100 d-flex mt-4">
        <button name="lux1" class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
     <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux2</h2></div>
    <div class="availbtn px-3 mt-1">Available</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/2.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 30$</div>
        <div>Daily ROI : 1.5$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form method="post"  class="w-100 d-flex mt-4">
        <button name="lux2" class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
       <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux3</h2></div>
    <div class="availbtn px-3 mt-1">Available</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/3.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 50$</div>
        <div>Daily ROI : 2.50$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form method="post" class="w-100 d-flex mt-4">
        <button name="lux3" class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
  <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux4</h2></div>
    <div class="availbtn px-3 mt-1">Available</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/4.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 85$</div>
        <div>Daily ROI : 4.25$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form method="post" class="w-100 d-flex mt-4">
        <button name="lux4" class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
    <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux5</h2></div>
    <div class="unavailbtn px-3 mt-1">Not Yet</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/5.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 120$</div>
        <div>Daily ROI : 6.00$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form action="" class="w-100 d-flex mt-4">
        <button class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
    <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux6</h2></div>
    <div class="unavailbtn px-3 mt-1">Not Yet</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/6.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 165$</div>
        <div>Daily ROI : 8.25$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form action="" class="w-100 d-flex mt-4">
        <button class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
    <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux7</h2></div>
    <div class="unavailbtn px-3 mt-1">Not Yet</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/7.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 220$</div>
        <div>Daily ROI : 11$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form action="" class="w-100 d-flex mt-4">
        <button class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
     <!-----------------------lux item start---------------->
<div class="lux mx-auto my-3">
<div class="d-flex justify-content-between w-100">
    <div><h2>Lux8</h2></div>
    <div class="unavailbtn px-3 mt-1">Not Yet</div>
</div>
<hr>
<div class="luxcon">
    <div class="w-25">
        <img src="assests/8.jpeg" class="img-fluid"alt="">
    </div>
    <div class="luxdet me-3">
        <div>Investment : 300$</div>
        <div>Daily ROI : 15$</div>
        <div>Duration : 40 Days</div>
    </div>
  
</div>  <form action="" class="w-100 d-flex mt-4">
        <button class="buttoninvest mx-auto ">Invest Now</button>
    </form>
</div>
  <!-----------------------lux item end---------------->
</div>


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

