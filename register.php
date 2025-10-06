<?php
session_start();
require 'db.php'; // database connection file
$warnMsg = ""; // default empty

if (isset($_POST['signup_submit'])) {
    // Get form values safely
     
    $fullname  = $conn->real_escape_string($_POST['fullname']);
    $email     = $conn->real_escape_string($_POST['email']);
    $password  = $conn->real_escape_string($_POST['password']); 
    $uplainer  = $conn->real_escape_string($_POST['referral_code']); 
    // Check if username already exists
    $checkUser = "SELECT * FROM users WHERE name = '$fullname' LIMIT 1";
    $result = $conn->query($checkUser);
    $checkmail = "SELECT * FROM users WHERE Email = '$email' LIMIT 1";
    $resultmail = $conn->query($checkmail);
if (strpos($fullname, ' ') !== false) {
  $warnMsg = '
        <div id="autoAlert" class="alert alert-dismissible fade show" role="alert" 
             style="position: fixed; padding:0.5rem; top: 0.5rem; left: 0.5rem; right: 0.5rem; 
                    margin: 0.5rem; background-color: white; border-radius:15px;
                    color:#dbb404; z-index: 1050; transition: opacity 0.5s ease;">
          <strong>Profit Lux!</strong> Username can not contain spaces.
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
          setTimeout(function() {
            var alertBox = document.getElementById("autoAlert");
            if (alertBox) {
              alertBox.style.opacity = "0"; // fade out
              setTimeout(function() {
                alertBox.remove(); // remove from DOM
              }, 500); 
            }
          }, 3000);
        </script>';
}

    elseif ($result && $result->num_rows > 0) {
        // Username exists → save warning message
        $warnMsg = '
        <div id="autoAlert" class="alert alert-dismissible fade show" role="alert" 
             style="position: fixed; padding:0.5rem; top: 0.5rem; left: 0.5rem; right: 0.5rem; 
                    margin: 0.5rem; background-color: white; border-radius:15px;
                    color:#dbb404; z-index: 1050; transition: opacity 0.5s ease;">
          <strong>Profit Lux!</strong> Username <b>' . htmlspecialchars($fullname) . '</b> already exists.
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
          setTimeout(function() {
            var alertBox = document.getElementById("autoAlert");
            if (alertBox) {
              alertBox.style.opacity = "0"; // fade out
              setTimeout(function() {
                alertBox.remove(); // remove from DOM
              }, 500); 
            }
          }, 3000);
        </script>';
    } 
     elseif ($resultmail && $resultmail->num_rows > 0) {
        // Username exists → save warning message
        $warnMsg = '
        <div id="autoAlert" class="alert alert-dismissible fade show" role="alert" 
             style="position: fixed; padding:0.5rem; top: 0.5rem; left: 0.5rem; right: 0.5rem; 
                    margin: 0.5rem; background-color: white; border-radius:15px;
                    color:#dbb404; z-index: 1050; transition: opacity 0.5s ease;">
          <strong>Profit Lux!</strong> Email <b>' . htmlspecialchars($email) . '</b> linked with another account.
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
          setTimeout(function() {
            var alertBox = document.getElementById("autoAlert");
            if (alertBox) {
              alertBox.style.opacity = "0"; // fade out
              setTimeout(function() {
                alertBox.remove(); // remove from DOM
              }, 500); 
            }
          }, 3000);
        </script>';
    }else {
        // Generate random referral code (3 digits + 3 letters)
       function generateCode($conn) {
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';
    do {
        $digits = strval(rand(100, 999));
        $letters = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $refcode = $digits . $letters;
        $stmt = mysqli_prepare($conn, "SELECT 1 FROM users WHERE Refcode=?");
        mysqli_stmt_bind_param($stmt, "s", $refcode);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $exists = mysqli_stmt_num_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
    } while ($exists);
    return $refcode;
}

$refcode = generateCode($conn);
        // Insert into database
        $sql = "INSERT INTO users (name, email, password, upliner, Refcode) 
                VALUES ('$fullname', '$email', '$password', '$uplainer', '$refcode')";
$stmtb = $conn->prepare("INSERT INTO balance (name, balance) VALUES (?, ?)");
$balance = 0.00;
$stmtb->bind_param("sd", $fullname, $balance); // s = string, d = double
$stmtb->execute();
$stmtb->close();

        if ($conn->query($sql) === TRUE) {
            // Redirect on success
             $_SESSION['username'] = $fullname;  
            header("Location: index.php");
            exit();
        } else {
            echo "❌ Error: " . $conn->error;
        }
    }
}
//________________________________________________________________sign in____________
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signinBtn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // plain password as per your requirement

    // Fetch user from DB
    $sql = "SELECT * FROM users WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check password
        if ($row['password'] === $password) {
            // Redirect on success
             $_SESSION['username'] = $username;  
            header("Location: index.php");
            exit();
        } else {
            // Password does not match
            $warnMsg = '
            <div id="autoAlert" class="alert alert-dismissible fade show" role="alert" 
                 style="position: fixed; padding:0.5rem; top: 0.5rem; left: 0.5rem; right: 0.5rem; 
                        margin: 0.5rem; background-color: white; border-radius:10px;
                        color:#dbb404; padding:0.5rem; border-radius:15px; z-index: 1050;">
              <strong>Profit Lux!</strong> Password does not match.
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <script>
              setTimeout(function() {
                var alertBox = document.getElementById("autoAlert");
                if (alertBox) {
                  var bsAlert = new bootstrap.Alert(alertBox);
                  bsAlert.close();
                }
              }, 2000);
            </script>
            ';
        }
    } else {
        // Username not found
       $warnMsg = '
        <div id="autoAlert" class="alert alert-dismissible fade show" role="alert" 
             style="position: fixed; padding:0.5rem; top: 0.5rem; left: 0.5rem; right: 0.5rem; 
                    margin: 0.5rem; background-color: white; border-radius:10px;
                    color:#dbb404; padding:0.5rem; border-radius:15px; z-index: 1050;">
          <strong>Profit Lux!</strong> Username not found.
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <script>
          setTimeout(function() {
            var alertBox = document.getElementById("autoAlert");
            if (alertBox) {
              var bsAlert = new bootstrap.Alert(alertBox);
              bsAlert.close();
            }
          }, 2000);
        </script>
        ';
    }
}
//___________________________________________________________End of sign in
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Profit Lux </title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }
    body {
      min-height: 100vh;
      display: flex;
      flex-direction:column;
      align-items: center;
      justify-content: center;
      background: #000000ff;
    }
    .wrapper {
      position: relative;
      max-width: 470px;
      width: 100%;
      border-radius: 12px;
      padding: 20px 30px 120px;
      background: #dbb404;
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .form.login {
      position: absolute;
      left: 50%;
      bottom: -86%;
      transform: translateX(-50%);
      width: calc(100% + 220px);
      padding: 20px 140px;
      border-radius: 50%;
      height: 100%;
      background: #fff;
      transition: all 0.6s ease;
    }
    .wrapper.active .form.login {
      bottom: -15%;
      border-radius: 35%;
      box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.1);
    }
    .form header {
      font-size: 30px;
      text-align: center;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
    }
    .form.login header {
      color: #333;
      opacity: 0.6;
    }
    .wrapper.active .form.login header {
      opacity: 1;
    }
    .wrapper.active .signup header {
      opacity: 0.6;
    }
    .wrapper form {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 40px;
    }
    form input {
      height: 60px;
      outline: none;
      border: none;
      padding: 0 15px;
      font-size: 16px;
      font-weight: 400;
      color: #333;
      border-radius: 8px;
      background: #fff;
    }
    .form.login input {
      border: 1px solid #aaa;
    }
    .form.login input:focus {
      box-shadow: 0 1px 0 #ddd;
    }
    form .checkbox {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .checkbox input[type="checkbox"] {
      height: 16px;
      width: 16px;
      accent-color: #fff;
      cursor: pointer;
    }
    form .checkbox label {
      cursor: pointer;
      color: #fff;
    }
    form a {
      color: #333;
      text-decoration: none;
    }
    form a:hover {
      text-decoration: underline;
    }
    form input[type="submit"] {
      margin-top: 15px;
      padding: none;
      font-size: 18px;
      font-weight: 500;
      cursor: pointer;
    }
    .form.login input[type="submit"] {
      background: #dbb404;
      color: #fff;
      border: none;
    }
    #supbtn{
      background-color:#dbb404;
      border: 2px solid white;
      box-shadow: 8px 8px 16px rgba(255, 255, 255, 0.3);
    }
    /* Loader */
    #loader {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.7); display: flex; justify-content: center; 
      align-items: center; z-index: 2000; display: none;
    }
  </style>
  <body>
    <!-- Loader Spinner -->
    <div id="loader">
      <div class="spinner-border text-warning" role="status" style="width: 4rem; height: 4rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

   

    <section class="wrapper">
       <!-- Warning message (if any) -->
   
      <div class="form signup">
         <div id="warn">
      <?php if (!empty($warnMsg)) echo $warnMsg; ?>
    </div>
        <header style="margin-top:-10px;"> 
          <img src="reglogo.jpg" style="width:70px; height:70px; border-radius:50%;" alt=""> Signup
        </header>
        <form method="POST" id="signupForm">
          <input type="text" name="fullname" placeholder="Full name" required />
          <input type="email" name="email" placeholder="Email address" required />
          <input type="password" name="password" placeholder="Password" required />
           <input type="hidden" name="referral_code" id="referral_code"
       value="<?php echo isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : ''; ?>">
        

          <div class="checkbox">
            <input type="checkbox" id="signupCheck" required />
            <label for="signupCheck">I Remember my password</label>
          </div>

          <input id="supbtn" type="submit" name="signup_submit" value="Signup" />
        </form>
      </div>

      <div class="form login">
        <header>Login</header>
       <form action="" method="POST">
  <input type="text" name="username" placeholder="Username" required />
  <input type="password" name="password" placeholder="Password" required />
   <a href="#">Forgot password?</a>
  <input type="submit" name="signinBtn" value="Sign In" />
</form>

      </div>
    </section>

    <script>
      // toggle wrapper (login/signup)
      const wrapper = document.querySelector(".wrapper"),
            signupHeader = document.querySelector(".signup header"),
            loginHeader = document.querySelector(".login header");
      loginHeader.addEventListener("click", () => {
        wrapper.classList.add("active");
      });
      signupHeader.addEventListener("click", () => {
        wrapper.classList.remove("active");
      });

      // show loader on form submit
      document.getElementById("signupForm").addEventListener("submit", () => {
        document.getElementById("loader").style.display = "flex";
      });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
