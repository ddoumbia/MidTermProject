<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $passwordInput = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($passwordInput)) {
        $error = "All fields are required.";
    } else {
        // Check if username already exists
        $users = file('../data/users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $userExists = false;
        foreach ($users as $user) {
            $userData = explode('|', $user);
            $existing_username = $userData[0];
            if ($existing_username === $username) {
                $userExists = true;
                break;
            }
        }

        if ($userExists) {
            $error = "Username already exists.";
        } else {
            $password = password_hash($passwordInput, PASSWORD_DEFAULT);
            $registrationDate = date('Y-m-d'); // Get the current date

            // Save the new user to users.txt
            $file = fopen('../data/users.txt', 'a');
            if ($file === false) {
                $error = "Unable to open users.txt for writing.";
            } else {
                $writeResult = fwrite($file, "$username|$password|$registrationDate\n");
                if ($writeResult === false) {
                    $error = "Unable to write to users.txt.";
                } else {
                    // Registration successful
                    fclose($file);
                    header('Location: sign-in.php');
                    exit;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <!-- Custom fonts and styles for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">

    <div class="container">

        <!-- Sign Up Form -->
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row -->
                <div class="row">
                    <!-- Optional: Add an image or remove this column -->
                    <!-- <div class="col-lg-5 d-none d-lg-block bg-register-image"></div> -->
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                            <form class="user" action="" method="POST">
                                <div class="form-group">
                                    <input type="email" name="username" class="form-control form-control-user" placeholder="Email Address" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="sign-in.php">Already have an account? Sign In!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- End of container -->

    <!-- JavaScript Libraries -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>
</html>
