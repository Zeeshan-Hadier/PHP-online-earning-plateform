<?php
session_start();
require 'db.php';
// Handle logout

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Deposits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container my-4">

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
                <img src="<?php echo $screenshot; ?>" alt="Screenshot" class="img-fluid" style="max-width:400px; border:1px solid #ccc;">
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

</body>
</html>
