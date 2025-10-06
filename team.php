<?php
session_start();
require 'db.php';
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: register.php");
    exit();
}
$username = $_SESSION['username'];
$_SESSION['username'] = $username;
 $sql = "SELECT Refcode FROM users WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$minerefcode = null; // default
if ($row = $result->fetch_assoc()) {
    $minerefcode = $row['Refcode'];
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
<!---------------------content of team---------------------------------->
<div class="pt-4 mt-4 mx-4">
    <div class="container pb-2 pt-5">
    <div class="" style="position: relative"> 
        <div class="scroll-wrapper">
            <div class="d-flex scrollable-buttons" id="levelButtons">
                                    <div id="Lbtn1" class="btn-active mx-1 level-btn activebtn" style="width:130px;" data-level="1"><span>Level 01</span></div>
                                    <div id="Lbtn2" class="btn-active mx-1 level-btn " style="width:130px;" data-level="2"><span>Level 02</span></div>
                                    <div id="Lbtn3" class="btn-active mx-1 level-btn" style="width:130px;" data-level="3"><span>Level 03</span></div>
                                 </div>
                             </div>
        <hr>
    </div>
</div>
</div>
<!---------------------------Starting teams div------------------>
<!----------------------Team 1------------------>
<?php
$sqlInvestment1 = "SELECT Level1invest FROM users WHERE name = '$username'";
$resultInvestment1 = $conn->query($sqlInvestment1);

$Investment1 = 0; // default value

if ($resultInvestment1 && $rowInvestment1 = $resultInvestment1->fetch_assoc()) {
    $Investment1 = $rowInvestment1['Level1invest'] ?? 0; 
}
?>
<div id="Levelteam1">
<div class="d-flex justify-content-center levelhead">
    Level 01 Team
</div>
<div class="teamdetails mx-auto my-2">
    <div class="d-flex justify-content-center"><h6 class="mt-2 mb-4">Details of Level 1 Team</h6></div>
    <div class="d-flex justify-content-between mx-3">
        <div calss="d-flex flex-column ">
            <div class="fsize">Total team</div>
            <h6 class="d-flex justify-content-center">0</h6>
        </div>
         <div calss="d-flex flex-column ">
            <div class="fsize">Investment</div>
            <h6 class="d-flex justify-content-center"><?php echo $Investment1; ?>$</h6>
        </div>
         <div calss="d-flex flex-column ">
            <div class="fsize">Your Commision</div>
            <h6 class="d-flex justify-content-center"><?php echo $Investment1*0.09; ?>$</h6>
        </div>
    </div>
</div>

<?php
// Fetch rows where upliner = $minerefcode
$sql = "SELECT name, email, created_at FROM users WHERE upliner = '$minerefcode'";
$result = $conn->query($sql);
?>
<style>/* Scrollable container */
.scroll-container {
    overflow-x: auto;
    margin: 20px 0;
    background-color: black; /* background behind table */
    padding: 5px;
}

/* Table */
.tableresponsive {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    min-width: 600px; /* ensures horizontal scroll */
}

.tableresponsive th, .tableresponsive td {
    padding: 10px;
    border: 1px solid #ddd;
}

.tableresponsive thead {
    background-color: #f1a500ff; /* yellow header */
    color: black;
}

.tableresponsive tbody {
    background-color: #000000; /* black body */
    color: #ffffff;
}

.tableresponsive tr:hover {
    background-color: #333333; /* hover effect */
}

/* Custom Scrollbar - WebKit browsers */
.scroll-container::-webkit-scrollbar {
    height: 10px; /* horizontal scrollbar height */
}

.scroll-container::-webkit-scrollbar-track {
    background: black; /* track background */
}

.scroll-container::-webkit-scrollbar-thumb {
    background-color: #f19d00ff; /* scrollbar color */
    border-radius: 5px;
    border: 2px solid black; /* adds padding effect */
}

/* Custom Scrollbar - Firefox */
.scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #f19100ff black;
}

    </style>
<!-- Table HTML -->
<!-- Wrap table in a scrollable container -->
<div class="mx-4">
<div class="scroll-container ">
    <table class="mx-3 my-4 tableresponsive">
        <thead>
            <tr>
                <th>Name</th>
                <th>Joining Date</th>
                <th>Investment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $totalinvest1=0;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['created_at']}</td>";

                    // fetch user investment safely
                    $rowusername = mysqli_real_escape_string($conn, $row['name']);
                    $sql2 = "SELECT SUM(investment) AS total_investment FROM investment WHERE username = '$rowusername'";
                    $invResult = $conn->query($sql2);

                    $userinvestment = 0; 
                    if ($invResult && $invRow = $invResult->fetch_assoc()) {
                        $userinvestment = $invRow['total_investment'] ?? 0;
                    }
                    
                    $totalinvest1= $totalinvest1+$userinvestment;

                    echo "<td>".$userinvestment."</td>";
                    echo "</tr>";
                }
                $sqlUpdateInvest1 = "UPDATE users SET Level1invest = '$totalinvest1' WHERE name = '$username'";

if ($conn->query($sqlUpdateInvest1) === TRUE) {
    echo " ";
} else {
    echo "Error updating record: " . $conn->error;
}
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>

</div>



<!----------------------Team 2------------------>
<div id="Levelteam2" class="hidden">
    <?php
$sqlInvestment2 = "SELECT Level2invest FROM users WHERE name = '$username'";
$resultInvestment2 = $conn->query($sqlInvestment2);
 
$Investment2 = 0; // default value

if ($resultInvestment2 && $rowInvestment2 = $resultInvestment2->fetch_assoc()) {
    $Investment2 = $rowInvestment2['Level2invest'] ?? 0; 
}
?>
<div class="d-flex justify-content-center levelhead">
    Level 02 Team
</div>
<?php
$sql = "SELECT refcode FROM users WHERE upliner = '$minerefcode'";
$result = $conn->query($sql);

$Level2refcodes = []; // initialize empty array

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Level2refcodes[] = $row['refcode']; // push each refcode into array
    }
}

if (!empty($Level2refcodes)) {
    // Escape values to prevent SQL injection
    $safeLevel2refcodes = array_map(function($code) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $code) . "'";
    }, $Level2refcodes);

    // Convert array into comma-separated string for IN clause
    $refcodeListlevel2 = implode(",", $safeLevel2refcodes);

    // Now fetch all users where upliner is in that list
    $sqllevel2 = "SELECT name, email, created_at FROM users WHERE upliner IN ($refcodeListlevel2)";
    $resultlevel2 = $conn->query($sqllevel2);

    // ✅ Total number of rows
    $totalRowsLevel2 = $resultlevel2->num_rows;

} else {
   $totalRowsLevel2 = 0;
}
?>

<div class="teamdetails mx-auto my-2">
    <div class="d-flex justify-content-center"><h6 class="mt-2 mb-4">Details of Level 2 Team</h6></div>
    <div class="d-flex justify-content-between mx-3">
        <div calss="d-flex flex-column ">
            <div class="fsize">Total team</div>
            <h6 class="d-flex justify-content-center"><?php echo $totalRowsLevel2; ?></h6>
        </div>
         <div calss="d-flex flex-column ">
            <div class="fsize">Investment</div>
            <h6 class="d-flex justify-content-center"><?php echo $Investment2; ?>$</h6>
        </div>
         <div calss="d-flex flex-column ">
            <div class="fsize">Your Commision</div>
            <h6 class="d-flex justify-content-center"><?php echo $Investment2*0.04; ?>$</h6>
        </div>
    </div>
</div>
<style>/* Scrollable container */
.scroll-container {
    overflow-x: auto;
    margin: 20px 0;
    background-color: black; /* background behind table */
    padding: 5px;
}

/* Table */
.tableresponsive {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    min-width: 600px; /* ensures horizontal scroll */
}

.tableresponsive th, .tableresponsive td {
    padding: 10px;
    border: 1px solid #ddd;
}

.tableresponsive thead {
    background-color: #f1a500ff; /* yellow header */
    color: black;
}

.tableresponsive tbody {
    background-color: #000000; /* black body */
    color: #ffffff;
}

.tableresponsive tr:hover {
    background-color: #333333; /* hover effect */
}

/* Custom Scrollbar - WebKit browsers */
.scroll-container::-webkit-scrollbar {
    height: 10px; /* horizontal scrollbar height */
}

.scroll-container::-webkit-scrollbar-track {
    background: black; /* track background */
}

.scroll-container::-webkit-scrollbar-thumb {
    background-color: #f19d00ff; /* scrollbar color */
    border-radius: 5px;
    border: 2px solid black; /* adds padding effect */
}

/* Custom Scrollbar - Firefox */
.scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #f19100ff black;
}

    </style>
<!-- Table HTML -->
<!-- Wrap table in a scrollable container -->
<div class="mx-4">
<div class="scroll-container">
    <table class="mx-3 my-4 tableresponsive">
        <thead>
            <tr>
                <th>Name</th>
                <th>Joining Date</th>
                <th>Investment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultlevel2->num_rows > 0) {
                $totalinvest2 = 0;
                while($row = $resultlevel2->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['created_at']}</td>";

                    // fetch user investment safely
                    $rowusername = mysqli_real_escape_string($conn, $row['name']);
                    $sql3 = "SELECT SUM(investment) AS total_investment FROM investment WHERE username = '$rowusername'";
                    $invResult = $conn->query($sql3);

                    $userinvestment2 = 0; 
                    if ($invResult && $invRow = $invResult->fetch_assoc()) {
                        $userinvestment2 = $invRow['total_investment'] ?? 0;
                    }
                        $totalinvest2 = $totalinvest2 + $userinvestment2 ;
                    echo "<td>".$userinvestment2."</td>";
                    echo "</tr>";
                }
                $sqlUpdateInvest2 = "UPDATE users SET Level2invest = '$totalinvest2' WHERE name = '$username'";

if ($conn->query($sqlUpdateInvest2) === TRUE) {
    echo " ";
} else {
    echo "Error updating record: " . $conn->error;
}
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>


</div>



<!----------------------Team 3------------------>
<div id="Levelteam3" class="hidden">
     <?php
$sqlInvestment3 = "SELECT Level3invest FROM users WHERE name = '$username'";
$resultInvestment3 = $conn->query($sqlInvestment3);

$Investment3 = 0; // default value

if ($resultInvestment3 && $rowInvestment3 = $resultInvestment3->fetch_assoc()) {
    $Investment3 = $rowInvestment3['Level3invest'] ?? 0; 
}
?>
<?php
if($totalRowsLevel2!= 0 ){
$sql = "SELECT refcode FROM users WHERE upliner IN ($refcodeListlevel2)";

$result = $conn->query($sql);

$Level3refcodes = []; // initialize empty array

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Level3refcodes[] = $row['refcode']; // push each refcode into array
    }
}


if (!empty($Level3refcodes)) {
    // Escape values to prevent SQL injection
    $safeLevel3refcodes = array_map(function($code) use ($conn) {
        return "'" . mysqli_real_escape_string($conn, $code) . "'";
    }, $Level3refcodes);

    // Convert array into comma-separated string for IN clause
    $refcodeListlevel3 = implode(",", $safeLevel3refcodes);

    // Now fetch all users where upliner is in that list
    $sqllevel3 = "SELECT name, email, created_at FROM users WHERE upliner IN ($refcodeListlevel3)";
    $resultlevel3 = $conn->query($sqllevel3);

    // ✅ Total number of rows
    $totalRowsLevel3 = $resultlevel3->num_rows;

} else {
   $totalRowsLevel3 = 0;
}
}else{
    $totalRowsLevel3 = 0;
    $resultlevel3=0;
}
?>

<div class="d-flex justify-content-center levelhead">
    Level 03 Team
</div>
<div class="teamdetails mx-auto my-2">
    <div class="d-flex justify-content-center"><h6 class="mt-2 mb-4">Details of Level 3 Team</h6></div>
    <div class="d-flex justify-content-between mx-3">
        <div calss="d-flex flex-column ">
            <div class="fsize">Total team</div>
            <h6 class="d-flex justify-content-center"><?php echo $totalRowsLevel3; ?></h6>
        </div>
         <div calss="d-flex flex-column ">
            <div class="fsize">Investment</div>
            <h6 class="d-flex justify-content-center"><?php echo $Investment3 ;?>$</h6>
        </div>
         <div calss="d-flex flex-column ">
            <div class="fsize">Your Commision</div>
            <h6 class="d-flex justify-content-center"><?php echo $Investment3*0.02 ;?>$</h6>
        </div>
    </div>
</div>


<style>/* Scrollable container */
.scroll-container {
    overflow-x: auto;
    margin: 20px 0;
    background-color: black; /* background behind table */
    padding: 5px;
}

/* Table */
.tableresponsive {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    min-width: 600px; /* ensures horizontal scroll */
}

.tableresponsive th, .tableresponsive td {
    padding: 10px;
    border: 1px solid #ddd;
}

.tableresponsive thead {
    background-color: #f1a500ff; /* yellow header */
    color: black;
}

.tableresponsive tbody {
    background-color: #000000; /* black body */
    color: #ffffff;
}

.tableresponsive tr:hover {
    background-color: #333333; /* hover effect */
}

/* Custom Scrollbar - WebKit browsers */
.scroll-container::-webkit-scrollbar {
    height: 10px; /* horizontal scrollbar height */
}

.scroll-container::-webkit-scrollbar-track {
    background: black; /* track background */
}

.scroll-container::-webkit-scrollbar-thumb {
    background-color: #f19d00ff; /* scrollbar color */
    border-radius: 5px;
    border: 2px solid black; /* adds padding effect */
}

/* Custom Scrollbar - Firefox */
.scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #f19100ff black;
}

    </style>
<!-- Table HTML -->
<!-- Wrap table in a scrollable container -->
<div class="mx-4">
<div class="scroll-container">
    <table class="mx-3 my-4 tableresponsive">
        <thead>
            <tr>
                <th>Name</th>
                <th>Joining Date</th>
                <th>Investment</th>
            </tr>
        </thead>
        <tbody>

            <?php if($resultlevel3 !=0){
            if ($resultlevel3->num_rows > 0) {
                $totalinvest3 = 0;
                while($row = $resultlevel3->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['created_at']}</td>";

                    // fetch user investment safely
                    $rowusername = mysqli_real_escape_string($conn, $row['name']);
                    $sql4 = "SELECT SUM(investment) AS total_investment FROM investment WHERE username = '$rowusername'";
                    $invResult = $conn->query($sql4);

                    $userinvestment3 = 0; 
                    if ($invResult && $invRow = $invResult->fetch_assoc()) {
                        $userinvestment3 = $invRow['total_investment'] ?? 0;
                    }
                        $totalinvest3 = $totalinvest3 + $userinvestment3 ;
                    echo "<td>".$userinvestment3."</td>";
                    echo "</tr>";
                }
                $sqlUpdateInvest3 = "UPDATE users SET Level3invest = '$totalinvest3' WHERE name = '$username'";

if ($conn->query($sqlUpdateInvest3) === TRUE) {
    echo " ";
} else {
    echo "Error updating record: " . $conn->error;
}
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No records found</td></tr>";
            }}else{
                 echo "<tr><td colspan='4' style='text-align:center;'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>




</div>
<!--------------------End of Content team-------------------------------->


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
