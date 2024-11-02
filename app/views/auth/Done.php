<?php
include_once __DIR__. "/../../controllers/UserController.php";


if (isset($_SESSION['UserData'])) {
    $userData = $_SESSION['UserData'];
    echo "Welcome, " . htmlspecialchars($userData['UserName']);
    print_r($_SESSION['UserData']);
} else {
    echo "No user is logged in.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $UserName = $_POST['UserName'] ?? '';

    $signUpResult = UserController::updateUserName($UserName);
  
    if ($signUpResult === true) {

    } else {
        echo "ERORR1";
    }
}else{
    echo "ERORR2";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Account Created – Swipe</title>
    <meta name="description" content="#">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap core CSS -->
    <link href="../dist/css/lib/bootstrap.min.css" type="text/css" rel="stylesheet">
    <!-- Swipe core CSS -->
    <link href="../dist/css/swipe.min.css" type="text/css" rel="stylesheet">
    <!-- Favicon -->
    <link href="../dist/img/favicon.png" type="image/png" rel="icon">
</head>

<body class="start">
    <main>
        <div class="layout">
            <div class="main order-md-2">
                <div class="start">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="content">
                                <h1>Welcome!</h1>
                                <p>Your account has been successfully created. You can now log in.</p>
                                <a href="sign-in.php" class="btn button">Sign In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Layout -->
    </main>
    <script src="dist/js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
</body>

</html>
