<!-- nav.php -->
<style>
    
    .navbar {
        display: inline-block;
    }

    .navbar li {
        display: inline-block;
        font-size: 22px;
    }

    .navbar li a {
        color: white;
        text-decoration: none;
        padding: 28px;
    }

    .navbar li a:hover,
    .navbar li a:active {
        /* color: rgb(0, 255, 34); */
        transition: color 0.9s ease;
        text-transform: uppercase;
        text-decoration: underline;
        font-size: 23px;
    }

    .navbar li.active a {
        /* color: cyan; */
        /* Highlight color */
        font-weight: bold;
        /* Bold to emphasize the active page */
        /* text-decoration: underline; */
        /* Optional underline to distinguish */
        text-transform: uppercase;

    }
</style>

<nav class="navbar">
    <div class="mid">
        <ul class="navbar-menu">
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readUsers.php' ? 'active' : ''; ?>">
                <a href="/Gym_Project/readUsers.php">Users</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readAttendance.php' ? 'active' : ''; ?>">
                <a href="/Gym_Project/readAttendance.php">Attendance</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readEquipment.php' ? 'active' : ''; ?>">
                <a href="/Gym_Project/readEquipment.php">Equipment</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readTimings.php' ? 'active' : ''; ?>">
                <a href="/Gym_Project/readTimings.php">Timings</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readPayments.php' ? 'active' : ''; ?>">
                <a href="/Gym_Project/readPayments.php">Payments</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'readPackages.php' ? 'active' : ''; ?>">
                <a href="/Gym_Project/readPackages.php">Packages</a>
            </li>
        </ul>
    </div>
</nav>