<?php
include "database.php"; 

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// User Class
class User {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Register new user and assign package
    public function registerUser($data) {
        if ($this->validate($data)) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert user data into the Users table
            $sql = "INSERT INTO Users (fname, lname, phone, email, username, password, userType) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [
                $data['fname'],
                $data['lname'],
                $data['phone'],
                $data['email'],
                $data['username'],
                $hashedPassword,
                $data['UserType']
            ]);

            $userID = $this->db->getLastInsertId();

            // Insert the user's package into UserPackage table
            $sql = "INSERT INTO UserPackage (UserID, packageID) VALUES (?, ?)";
            $this->db->query($sql, [
                $userID,
                $data['PackageName']
            ]);

            return "Signup successful and package assigned!";
        } else {
            throw new Exception("Please fill out all required fields.");
        }
    }

    // Validate form data
    private function validate($data) {
        return isset($data['fname'], $data['lname'], $data['phone'], $data['email'], $data['username'], $data['password'], $data['UserType'], $data['PackageName']);
    }
}

// Fetch packages for the dropdown
$db = new Database();
$sql = "SELECT PackageID, PackageName, PackagePrice FROM Packages";
$packages = $db->query($sql);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $user = new User($db);
        $message = $user->registerUser($_POST);
        echo "<div class='alert alert-success' role='alert'>{$message}</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
    }
}

$db->close();
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
                    <option value="member">Member</option>
                    <option value="worker">Worker</option>
                    <option value="janitor">Janitor</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="PackageName" class="form-label">Select Package</label>
                <select class="form-control" id="PackageName" name="PackageName" required>
                    <option value="">Select a Package</option>

                    <?php
                    if (!empty($packages)) {
                        foreach ($packages as $row) {
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
        </form>

        <div class="mt-3">
            <a href="login.php" class="btn btn-primary">Login</a>
        </div>

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
