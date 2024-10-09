<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertical Navbar</title>
    <style>
        .navbar {
            display: flex;
            flex-direction: column;
            background-color: rgba(0, 0, 0, 0.8);
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            padding: 10px 0;
            transition: transform 0.3s ease;
            transform: translateX(0);
        }

        .navbar.hidden {
            transform: translateX(-100%);
        }

        .navbar-menu {
            list-style: disc;
            /* Add bullet points */
            padding-left: 20px;
            /* Indent bullets */
            margin-top: 10px;
            /* Add space before first item */
            margin-bottom: 0;
            /* No margin at bottom */
        }

        .navbar li {
            display: block;
            /* Set to block to occupy full width */
            font-size: 22px;
            margin: 0 0 10px;
            /* Space between items */
        }

        .navbar li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: color 0.3s ease;
        }

        .navbar li a:hover,
        .navbar li a:active {
            text-decoration: underline;
            /* Underline on hover or active */
        }

        .navbar li.active a {
            font-weight: bold;
            /* Bold for active link */
            text-transform: uppercase;
            /* Uppercase for active link */
        }

        /* Add responsiveness */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 20px 0;
            }

            .navbar li {
                font-size: 18px;
                margin: 10px 0;
            }
        }

        .toggle-button {
            position: fixed;
            left: 0;
            top: 1px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1100;
            transition: background 0.3s ease;
        }

        .toggle-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>
    <button class="toggle-button">☰</button>
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

    <script>
        // Get the navbar and toggle button elements
        const navbar = document.querySelector('.navbar');
        const toggleButton = document.querySelector('.toggle-button');

        // Add click event listener to the toggle button
        toggleButton.addEventListener('click', () => {
            // Toggle the 'hidden' class on the navbar
            navbar.classList.toggle('hidden');

            // Change the button text based on navbar visibility
            if (navbar.classList.contains('hidden')) {
                toggleButton.textContent = '☰'; // Show icon
            } else {
                toggleButton.textContent = '✖'; // Close icon
            }
        });
    </script>
</body>

</html>