<?php
session_start();
require 'db.php';
// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle login
$correctPassword = "Awanstech#1646";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'] ?? '';

    if ($password === $correctPassword) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "❌ Password does not match!";
    }
}

// Fetch pending withdrawals ONLY after login
$result = null;
if (isset($_SESSION['logged_in'])) {
    $sql = "SELECT * FROM withdraw WHERE state = 'pending'";
    $stmt = $conn->prepare($sql);
    
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<?php
if (isset($_POST['approve']) && isset($_POST['withdraw_id'])) {
    $withdraw_id = $_POST['withdraw_id'];
    approveUser($withdraw_id);
}

function approveUser($username) {
    global $conn;
    $stmt = $conn->prepare("UPDATE withdraw SET state='approved' WHERE name = ?");
    $stmt->bind_param("s", $username);  // "s" since User_Name is a string
    if ($stmt->execute()) {
        echo "<script>alert('Withdrawal approved!'); window.location='';</script>";
    } else {
        echo "<script>alert('Error approving withdrawal');</script>";
    }
}
//_________________________________________________________deposit approved

//_________________________________________________________________________


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Admin Panel profitlux</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
  <link rel="icon" type="image/png" href="favi-icon.png">
  <style>
    @media only screen and (max-width: 600px) {
    body {
        font-size: 8px;
    }
}
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }
    .container-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 800px;
      text-align: center;
    }
    .lock {
      font-size: 50px;
      color: #2a5298;
    }
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 15px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    button {
      background: #2a5298;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background: #1e3c72;
    }
    .error {
      color: red;
      margin-top: 10px;
    }
    .welcome {
      font-size: 20px;
      font-weight: bold;
      color: #2a5298;
      margin-bottom: 20px;
    }
    .logout-btn {
      margin-top: 20px;
      display: inline-block;
      background: red;
      padding: 10px 18px;
      border-radius: 8px;
      color: white;
      text-decoration: none;
    }
    .logout-btn:hover {
      background: darkred;
    }
    .tablecontainer{
        overflow-y: auto; /* vertical scroll */
      overflow-x: auto; /* horizontal scroll if needed */
    }
    .card {
        margin-bottom: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .card img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="container-box">
    <?php if (!isset($_SESSION['logged_in'])): ?>
        <div class="lock">🔒</div>
        <h2>Admin Panel Login (profitlux)</h2>
        <form method="POST" action="">
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    <?php else: ?>
        <div class="welcome">✅ Welcome to Admin Panel (lux)</div>
        <h3 class="mb-4">Pending Withdrawals</h3>
        <div class="table-responsive" style="border:1px solid black;">
            <table  class="table table-bordered table-striped ">
                <thead class="table-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Amount</th>
                        <th>Adress</th>
                        <th>State</th>
                        <th>Approve</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['amount']); ?></td>
                            <td><?= htmlspecialchars($row['adress']); ?></td>
                          
                            <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($row['state']); ?></span></td>
                            <td>
                               <form method="POST">
   <input type="hidden" name="withdraw_id" value="<?= $row['name'] ?>"> <!-- assuming 'id' is PK -->
    <button type="submit" name="approve" class="btn btn-info">
        <i class="fa-solid fa-person-circle-check"></i>
    </button>
</form>


                            </td>
                           
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No pending withdrawals found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            
        </div>
                <!--------------------------Withdrawal approved----------------------------->
<?php
// ===== Handle Deposit Approval =====
if (isset($_POST['approvedeposit'])) {
    $requestname = mysqli_real_escape_string($conn, $_POST['requestname']);
    $amount = (float) $_POST['amount'];

    // Fetch old balance
    $sqlBal = "SELECT balance FROM balance WHERE name = '$requestname'";
    $resultBal = $conn->query($sqlBal);

    if ($resultBal && $rowBal = $resultBal->fetch_assoc()) {
        $oldBalance = (float) $rowBal['balance'];
        $upbalance = $oldBalance + $amount;

        // Update balance table
        $sqlUpdateBal = "UPDATE balance SET balance = '$upbalance' WHERE name = '$requestname'";
        $conn->query($sqlUpdateBal);

        // Update deposit state
        $sqlUpdateDep = "UPDATE deposit SET state = 'approved' WHERE name = '$requestname'";
        $conn->query($sqlUpdateDep);

        echo "<script>alert('Deposit Approved Successfully!');</script>";
    }
}

// ===== Fetch Pending Deposits =====
$sql = "SELECT name, amount, Screenshot FROM deposit WHERE state = 'pending'";
$result = $conn->query($sql);

//===============================================================================
//                         Total investment and withdrawal
// ==
?>


<h2 class="mb-4">Pending Deposits</h2>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $username = htmlspecialchars($row['name']);
        $amount = htmlspecialchars($row['amount']);
        $screenshot = htmlspecialchars($row['Screenshot']);
        ?>
        
        <div class="card mb-4 p-3 d-flex flex-column">
            <h4><b><?php echo $username; ?></b></h4>
            <p>Amount: <strong><?php echo $amount; ?>$</strong></p>
            
            <div class="mb-3">
                <img src="<?php echo $screenshot; ?>" alt="Screenshot" class="img-fluid" style="max-width:250px; border:1px solid #ccc;">
            </div>

            <form method="post" class="mt-3">
                <input type="hidden" name="requestname" value="<?php echo $username; ?>">
                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" name="amount" class="form-control" required value="<?php echo $amount; ?>">
                </div>
                <button type="submit" name="approvedeposit" class="btn btn-success">Approve Deposit</button>
            </form>
        </div>

        <?php
    }
} else {
    echo "<div class='alert alert-info'>No pending deposits found.</div>";
}
?>

                <!--------------------------------------------------------------------------->

        <a href="?logout=1" class="logout-btn">Logout</a>
        <!--===================================================-->
        <?php
        //=== Fetch Total Investment =====
$sql = "SELECT SUM(investment) AS total_investment FROM investment";
$result = $conn->query($sql);

$totalInvestment = 0;
if ($result && $row = $result->fetch_assoc()) {
    $totalInvestment = $row['total_investment'] ?? 0;
}
//===================================================================================
//                           Total withdrawal

// ===== Fetch Total Withdraw =====
$sql = "SELECT SUM(amount) AS total_withdraw FROM withdraw";
$result = $conn->query($sql);

$totalWithdraw = 0;
if ($result && $row = $result->fetch_assoc()) {
    $totalWithdraw = $row['total_withdraw'] ?? 0;
}
        ?>
<div class="card py-1 mt-3 my-1 px-4">
    <h4>Total Investment</h4>
    <p class="fs-3 text-success"><b><?php echo $totalInvestment; ?>$</b></p>
</div>

<div class="card py-1 my-2 px-4">
    <h4>Total Withdraw</h4>
    <p class="fs-3 text-danger"><b><?php echo $totalWithdraw; ?>$</b></p>
</div>
        
        

<!---------------------------------------------->
</div>
        </div>










    <?php endif; ?>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>