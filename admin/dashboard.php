<?php
session_start();

// Enable error reporting during development (remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/sign-in.php');
    exit;
}

// Fetch data for dashboard widgets
$users = file('../data/users.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$pets = file('../data/pets.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Count the number of users and pets
$userCount = count($users);
$petCount = count($pets);

// Since there are no registration dates, we cannot process user registrations over time.
// You can either remove the chart or modify it to display different data.
// For this example, we'll remove the "User Registrations Over Time" chart.

// Process pet types for the pie chart
$petTypes = [];

foreach ($pets as $pet) {
    $petData = explode('|', $pet);
    if (isset($petData[2])) {
        $petType = trim($petData[2]);
        if ($petType !== '') {
            if (!isset($petTypes[$petType])) {
                $petTypes[$petType] = 0;
            }
            $petTypes[$petType]++;
        }
    }
}

$petTypeLabels = array_keys($petTypes);
$petTypeCounts = array_values($petTypes);

// Encode data as JSON
$petTypeLabelsJson = json_encode($petTypeLabels);
$petTypeCountsJson = json_encode($petTypeCounts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Custom fonts and styles for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'topbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Dashboard Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Users Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pets Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Pets</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $petCount; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-paw fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add more cards as needed -->

                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Since we cannot display the User Registrations Over Time chart without registration dates,
                             you may choose to remove this chart or display alternative data -->

                        <!-- For this example, we'll remove the chart and display only the Pet Types Distribution chart -->

                        <!-- Pie Chart -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Pet Types Distribution</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4">
                                        <canvas id="petChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- You can add another chart or content here if desired -->

                    </div>

                    <!-- Additional content rows can be added here -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- JavaScript Libraries -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Chart.js -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page-level custom scripts -->
    <script>
    // Data from PHP
    var petTypeLabels = <?php echo $petTypeLabelsJson; ?>;
    var petTypeCounts = <?php echo $petTypeCountsJson; ?>;

    // Pie Chart for Pet Types
    var ctx = document.getElementById("petChart");
    var petChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: petTypeLabels,
            datasets: [{
                data: petTypeCounts,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#2e59d9', '#17a673', '#4e73df'],
            }],
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
            },
        },
    });
    </script>

</body>
</html>
