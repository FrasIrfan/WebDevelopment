<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

require_once 'database.php';
require_once 'userClass.php';

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

                <?php if ($this->users && count($this->users) > 0) {
                    $row = $this->users[0];
                    
                    // var_dump($row['ID']);
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
                                <td><?= htmlspecialchars($row['fname']) ?></td>
                                <td><?= htmlspecialchars($row['lname']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td>
                                    <a href="<?php echo 'editSpecificUser.php?id=' . $row['ID']; ?>" class="btn btn-primary btn-sm">Edit</a>
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
$db = new Database();

// Check if the user is logging in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $user = new User($db, null); // User's ID not set for login, so pass null
    $loginResult = $user->login($_POST['username'], $_POST['password']);
    if ($loginResult) {
        echo $loginResult; // Display any login error messages
    }
} else {
    // Fetch the logged-in user's data from the database
    if (isset($_SESSION['userid'])) {
        $user = new User($db, $_SESSION['userid']);
        $users = [$user->getUserDetails()]; // Modify this to fetch user details correctly
        // var_dump($users);

        // Display user details
        $view = new UserListView($users);
        $view->render();
    } else {
        echo "User not logged in.";
    }
}

// Close the database connection
$db->close();
?>