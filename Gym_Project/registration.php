<?php
include "config.php";  // Using database connection file here

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Fetch packages for the  dropdown
$sql = "SELECT PackageID , PackageName, PackagePrice FROM Packages";
$storedata = $mysqli->query($sql);

if (!$storedata) {
    echo "Error fetching data: " . $mysqli->error;
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize variables and check for each field's presence
    $fname = isset($_POST['fname']) ? $_POST['fname'] : null;
    $lname = isset($_POST['lname']) ? $_POST['lname'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $userType = isset($_POST['UserType']) ? $_POST['UserType'] : null;
    $packageID = isset($_POST['PackageName']) ? $_POST['PackageName'] : null; // Get selected package ID


    // Ensure required fields are filled
    if ($fname && $lname && $phone && $email && $username && $password && $userType !== null && $packageID !== null) {
        // SQL to insert data
        $sql = "INSERT INTO Users (fname, lname, phone, email, username, password, userType) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare statement with mysqli
        $statement = $mysqli->prepare($sql);

        if ($statement === false) {
            echo "Error preparing statement: " . $mysqli->error;
        } else {
            // Bind parameters and execute statement
            $statement->bind_param(
                "sssssss",
                $fname,       // String
                $lname,       // String
                $phone,       // String
                $email,       // String
                $username,    // String
                $password,    // String
                $userType,    // String (ENUM)
            );
            // Execute statement
            if ($statement->execute()) {
                $UserID = $mysqli->insert_id; // Get the ID of the newly inserted user

                // Now insert the package into the userpackage table
                $sql = "INSERT INTO UserPackage (UserID, packageID) VALUES (?, ?)";
                $statement = $mysqli->prepare($sql);

                if ($statement === false) {
                    echo "Error preparing statement: " . $mysqli->error;
                } else {
                    $statement->bind_param("ii", $UserID, $packageID);

                    if ($statement->execute()) {
                        echo "<div class='alert alert-success' role='alert'>";
                        echo "Signup successful and package assigned!";
                        echo "</div>";
                    } else {
                        echo "Error: " . $statement->error;
                    }

                }
            } else {
                echo "Error: " . $statement->error;
            }

            $statement->close();
        }
    } else {
        echo "Please fill out all required fields.";
    }
}

$mysqli->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Including bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Register as new user</h2>
        <br>
        <form method="POST" action="registration.php">
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname">
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="UserType" class="form-label">User Type</label>
                <select name="UserType" id="UserType" class="form-control">
                    <option value="">Select a type</option>
                    <!-- <option value="owner">Owner</option> -->
                    <option value="member">Member</option>
                    <!-- <option value="trainer">Trainer</option> -->
                    <option value="worker">Worker</option>
                    <option value="janitor">Janitor</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="PackageName" class="form-label">Select Package</label>
                <select class="form-control" id="PackageName" name="PackageName" required>
                <option value="">Select a Package</option>

                    <?php
                    if ($storedata->num_rows > 0) {
                        while ($row = $storedata->fetch_assoc()) {
                    ?>
                            <option value="<?= $row['PackageID']; ?>">
                                <?= $row['PackageName'] . " " . $row['PackagePrice'] . "Rs"; ?>
                            </option>
                    <?php
                        }
                    }
                    ?>

                </select>
            </div>


            <button type="submit" class="btn btn-primary">Register</button>
            
            <button href="login.php" class="btn btn-primary">Login</button>
            
        </form>


        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Go Back</a>
        </div>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>