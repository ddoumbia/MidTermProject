<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../auth/sign-in.php');
    exit;
}

// Handle Create, Edit, Delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pets = file('../data/pets.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (isset($_POST['create'])) {
        $petName = trim($_POST['petName']);
        $petDescription = trim($_POST['petDescription']);
        $petType = trim($_POST['petType']);

        // Validate inputs
        if ($petName === '' || $petDescription === '' || $petType === '') {
            $error = "All fields are required.";
        } else {
            $file = fopen('../data/pets.txt', 'a');
            fwrite($file, "$petName|$petDescription|$petType\n");
            fclose($file);
            header('Location: pets.php');
            exit;
        }
    }

    if (isset($_POST['delete'])) {
        $petNameToDelete = $_POST['petName'];
        $newPets = array_filter($pets, function ($pet) use ($petNameToDelete) {
            $petData = explode('|', $pet);
            $petName = isset($petData[0]) ? $petData[0] : '';
            return $petName !== $petNameToDelete;
        });
        file_put_contents('../data/pets.txt', implode("\n", $newPets) . "\n");
    }

    if (isset($_POST['edit'])) {
        $originalPetName = $_POST['originalPetName'];
        $newPetName = trim($_POST['petName']);
        $newPetDescription = trim($_POST['petDescription']);
        $newPetType = trim($_POST['petType']);

        // Validate inputs
        if ($newPetName === '' || $newPetDescription === '' || $newPetType === '') {
            $error = "All fields are required.";
        } else {
            $newPets = array_map(function ($pet) use ($originalPetName, $newPetName, $newPetDescription, $newPetType) {
                $petData = explode('|', $pet);
                $petName = isset($petData[0]) ? $petData[0] : '';
                if ($petName === $originalPetName) {
                    return "$newPetName|$newPetDescription|$newPetType";
                }
                return $pet;
            }, $pets);
            file_put_contents('../data/pets.txt', implode("\n", $newPets) . "\n");
            header('Location: pets.php');
            exit;
        }
    }
}

$pets = file('../data/pets.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Pets</title>
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

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Manage Pets</h1>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <!-- Create Pet Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Create New Pet</h6>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" class="user">
                                <div class="form-group">
                                    <input type="text" name="petName" class="form-control form-control-user" placeholder="Pet Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="petDescription" class="form-control form-control-user" placeholder="Description" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="petType" class="form-control form-control-user" placeholder="Type (e.g., Dog, Cat)" required>
                                </div>
                                <button type="submit" name="create" class="btn btn-primary btn-user btn-block">
                                    Create Pet
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Pets Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Existing Pets</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="petsTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Pet Name</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pets as $pet): ?>
                                            <?php
                                            $petData = explode('|', $pet);
                                            $petName = isset($petData[0]) ? $petData[0] : '';
                                            $petDescription = isset($petData[1]) ? $petData[1] : '';
                                            $petType = isset($petData[2]) ? $petData[2] : '';

                                            // Skip incomplete entries
                                            if ($petName === '' || $petDescription === '' || $petType === '') {
                                                continue;
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($petName); ?></td>
                                                <td><?php echo htmlspecialchars($petDescription); ?></td>
                                                <td><?php echo htmlspecialchars($petType); ?></td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-sm btn-primary editBtn"
                                                            data-pet-name="<?php echo htmlspecialchars($petName); ?>"
                                                            data-pet-description="<?php echo htmlspecialchars($petDescription); ?>"
                                                            data-pet-type="<?php echo htmlspecialchars($petType); ?>">
                                                        Edit
                                                    </button>
                                                    <!-- Delete Form -->
                                                    <form action="" method="POST" style="display:inline;">
                                                        <input type="hidden" name="petName" value="<?php echo htmlspecialchars($petName); ?>">
                                                        <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Pet Modal -->
                    <div class="modal fade" id="editPetModal" tabindex="-1" role="dialog" aria-labelledby="editPetModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <form action="" method="POST">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editPetModalLabel">Edit Pet</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                  <input type="hidden" name="originalPetName" id="originalPetName">
                                  <div class="form-group">
                                      <label for="editPetName">Pet Name</label>
                                      <input type="text" name="petName" id="editPetName" class="form-control" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="editPetDescription">Description</label>
                                      <input type="text" name="petDescription" id="editPetDescription" class="form-control" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="editPetType">Type</label>
                                      <input type="text" name="petType" id="editPetType" class="form-control" required>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                              </div>
                          </form>
                        </div>
                      </div>
                    </div>

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
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page-level custom scripts -->
    <script>
    $(document).ready(function() {
        // Edit button click handler
        $('.editBtn').on('click', function() {
            var petName = $(this).data('pet-name');
            var petDescription = $(this).data('pet-description');
            var petType = $(this).data('pet-type');

            $('#originalPetName').val(petName);
            $('#editPetName').val(petName);
            $('#editPetDescription').val(petDescription);
            $('#editPetType').val(petType);

            $('#editPetModal').modal('show');
        });
    });
    </script>

</body>
</html>
