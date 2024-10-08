<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/sign-in.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $users = file('../data/users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($users as $user) {
        list($existing_username) = explode('|', $user);
        if ($existing_username === $username) {
            $error = "Username already exists.";
            break;
        }
    }

    if (!isset($error)) {
        $file = fopen('../data/users.txt', 'a');
        fwrite($file, "$username|$password\n");
        fclose($file);
        header('Location: users.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create User</title>
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">

    <div class="container">

        <!-- Create User Form -->
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-8 col-md-10">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row -->
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create New User</h1>
                            </div>
                            <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                            <form class="user" action="" method="POST">
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-user" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Create User
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="users.php">Back to Manage Users</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- JavaScript Libraries -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

</body>
</html>
