<?php 
require_once __DIR__ . "/../../controllers/UserController.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $countryID = $_POST['CountryID'] ?? '';

    echo $firstName . " " . $email . " " . $password . " " . $countryID;
    
    $signUpResult = UserController::signUp($firstName, $email,$password,$countryID);
    // You can handle success or error messages here
    if ($signUpResult === true) {
        // Success
        print_r($_SESSION['UserData']);
        echo "\n\n" . $_SESSION['UserID'];
    } elseif (is_array($signUpResult)) {
        // Handle validation errors
        foreach ($signUpResult as $error) {
            echo $error . "<br>";
        }
    } else {
        echo "Error during sign-up.";
    }
}else{
    echo "ERORR4";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Choose Username – Swipe</title>
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
            <!-- Start of Username Page -->
            <div class="main order-md-2">
                <div class="start">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="content">
                                <h1>Choose a Username</h1>
                                <form class="signup" method="POST" action="Done.php">
                                    <!-- Redirect to final page -->
                                    <div class="form-group">
                                        <input type="text" id="inputUsername" name="UserName" class="form-control"
                                            placeholder="Username" required>
                                        <button class="btn icon"><i class="material-icons">person_outline</i></button>
                                    </div>
                                    <button type="submit" class="btn button">Done</button>
                                    <!-- Final submission button -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Username Page -->
        </div> <!-- Layout -->
    </main>
    <script src="dist/js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
</body>

</html>