<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session
print_r($_SESSION); // Uncomment this to debug session details

// Include the database configuration file
include 'config.php';

class Database
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function query($sql)
    {
        return $this->mysqli->query($sql);
    }

    public function close()
    {
        $this->mysqli->close();
    }
}

class User
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function login($username, $password)
    {
        // Sanitize the inputs to prevent SQL injection
        $username = $this->db->query("SELECT '".$username."' as username")->fetch_assoc()['username'];
        $sql = "SELECT ID, password FROM Users WHERE username = '$username'";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Set session variables for user ID and username
                $_SESSION['userid'] = $row['ID'];
                $_SESSION['username'] = $username;

                // Redirect to user dashboard or another page
                header("Location: userDashboard.php");
                exit();
            } else {
                return "Invalid password.";
            }
        } else {
            return "User not found.";
        }
    }

    public function getUserById()
    {
        // Ensure the session userid is set and is an integer
        if (isset($_SESSION['userid']) && is_numeric($_SESSION['userid'])) {
            $userId = intval($_SESSION['userid']); // Cast to integer to ensure it’s safe to use in the query
    
            // Query to fetch user details using session userid
            $sql = "SELECT ID, fname, lname, phone, email, username 
                    FROM Users 
                    WHERE ID = $userId";
    
            $result = $this->db->query($sql);
    
            // Return the user data if found
            if ($result && $result->num_rows > 0) {
                return $result;
            }
        }
    
        return null; // Return null if session userid is not set, invalid, or user not found
    }
}

class UserListView
{
    private $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function render()
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Details</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>My Details</h2>
                <div>
                    <a href="userDashboard.php" class="btn btn-info">Go Back</a>
                </div>
            </div>

            <?php if ($this->users && $this->users->num_rows > 0) { 
                $row = $this->users->fetch_assoc(); // Since only one user is fetched
            ?>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $row['fname'] ?></td>
                            <td><?= $row['lname'] ?></td>
                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td>
                                <a href="editSpecificUser.php?id=<?= $row['ID'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="alert alert-warning" role="alert">
                    No user details found.
                </div>
            <?php } ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    }
}

// Initialize Database connection
$db = new Database($mysqli);

// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $user = new User($db);
    $loginResult = $user->login($_POST['username'], $_POST['password']);
    if ($loginResult) {
        echo $loginResult; // Display any login error messages
    }
} else {
    // Fetch the logged-in user's data from the database
    $user = new User($db);
    $users = $user->getUserById();

    // Display user details
    $view = new UserListView($users);
    $view->render();
}

// Close the database connection
$db->close();
?>
