<?php 
require_once __DIR__ . "/../../models/Country.php";


$Allcountry = new Country();
$countries = $Allcountry->getAllCountries();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Sign Up – Swipe</title>
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
            <!-- Start of Sign Up -->
            <div class="main order-md-2">
                <div class="start">
                    <div class="container">
                        <div class="col-md-12">
                            <div class="content">
                                <h1>Create Account</h1>
                                <div class="third-party">
                                    <button class="btn item bg-blue">
                                        <i class="material-icons">pages</i>
                                    </button>
                                </div>
                                <form class="signup" action="UserName.php" method="POST">
                                    <div class="form-parent">
                                        <div class="form-group">
                                            <input type="text" id="inputFirstName" name="firstName" class="form-control"
                                                placeholder="First Name" required>
                                            <button class="btn icon"><i
                                                    class="material-icons">person_outline</i></button>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" id="inputEmail" name="email" class="form-control"
                                                placeholder="Email Address" required>
                                            <button class="btn icon"><i class="material-icons">mail_outline</i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" id="inputPassword" name="password" class="form-control"
                                            placeholder="Password" required>
                                        <button class="btn icon"><i class="material-icons">lock_outline</i></button>
                                    </div>
                                    <!-- Country Selection -->
                                    <div class="form-group">
                                        <select id="inputCountry" name="CountryID" class="form-control" required>
                                            <option value="" disabled selected>Select Country</option>
                                            <?php
                                                foreach ($countries as $c) {
                                                    echo '<option value="' . htmlspecialchars($c['CountryID']) . '">' . htmlspecialchars($c['CountryName']) . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn button">Sign Up</button>
                                    <div class="callout">
                                        <span>Already a member? <a href="sign-in.php">Sign In</a></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Sign Up -->
            <!-- Start of Sidebar -->
            <div class="aside order-md-1">
                <div class="container">
                    <div class="col-md-12">
                        <div class="preference">
                            <h2>Welcome Back!</h2>
                            <p>To keep connected with your friends please login with your personal info.</p>
                            <a href="sign-in.php" class="btn button">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Sidebar -->
        </div> <!-- Layout -->
    </main>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="dist/js/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script>
    window.jQuery || document.write('<script src="dist/js/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="dist/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
</body>

</html>