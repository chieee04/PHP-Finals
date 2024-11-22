<?php
    session_start();
    $_SESSION['CURR_PAGE'] = 'subject'
  ?>
<?php 
include '../../functions.php'; 
dashboardguard();
$logoutPage = '../logout.php';
require '../partials/header.php';
require '../partials/side-bar.php';
if(isset($_POST['btn'])){
    $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $code =sanitize($con, $_POST['subject_code']);
    $name =sanitize($con, $_POST['subject_name']);

    $err = [];

    if(empty($code))
        $err[] = "Lastname is required!";
    if(empty($name))
        $err[] = "Lastname is required!";
    if (empty($err)) {
        $strSql = "
                INSERT INTO subjects(subject_code,subject_name) VALUES ('$code','$name')
                    ";
    }
    closeCon();
}
?>

<div class="col-md-9 col-lg-10">
    <h3 class="text-left mb-5 mt-5">Add A New Subject</h3>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>
        
    <!-- Form to Add a New Subject -->
    <div class="card p-4 mb-5">
        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code">
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name">
            </div>
            <button name="btn" type="submit" class="btn btn-primary btn-sm w-100">Add Subject</button>
        </form>
    </div>

    <!-- Subject List Table -->
    <div class="card p-4">
        <h3 class="card-title text-center">Subject List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <!-- Subject records will be listed here -->
                <?php 
                // Ensure the function returns an array
                $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                $strSql = "SELECT * FROM subjects ORDER BY subject_code, subject_name";
                $recPerson = getRec($con, $strSql);

                if (!empty($recPerson)) {
                    foreach ($recPerson as $key => $value) {
                        // Check if $value is an array before accessing keys
                        if (is_array($value)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($value['subject_code']) . '</td>';
                            echo '<td>' . htmlspecialchars($value['subject_name']) . '</td>';
                            echo '<td>';
                            echo    '<a href="edit.php?k=' . htmlspecialchars($value['id']) . '" class="btn btn-success">Edit</a>';
                            echo    '<a href="delete.php?k=' . htmlspecialchars($value['id']) . '" class="btn btn-danger">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            // If $value is not an array, print an error message
                            echo '<tr><td colspan="4">Error: Data is not in the expected format.</td></tr>';
                        }
                    }
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<?php
require '../partials/footer.php';






?>
