<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leo Fitness</title>
    <link rel="icon" href="image/icon.png" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhai+2:wght@500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <header class="header">
        <!-- left box for logo -->
        <div class="left">
            <img src="image/logo.png" />
            Leo Fitness
        </div>

        <!-- mid box for navigation -->
        <div class="mid">
            <ul class="navbar">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="/about.html">About us</a></li>
                <li><a href="/contact.html">Contact us</a></li>
            </ul>
        </div>

        <!-- right box for buttons  -->
        <div class="right">
            <button class="btn" id="followBtn">Follow us</button>
            <button class="btn" id="emailBtn">Email</button>
            <div id="socialLinks" style="display: none;">
                <a href="https://www.facebook.com">Facebook</a> |
                <a href="https://www.instagram.com">Instagram</a> |
                <a href="https://www.twitter.com">Twitter</a>
            </div>
            <div id="emailLink" style="display: none;">
                <a href="mailto:info@leofitness.com">info@leofitness.com</a>
            </div>
        </div>

        <div class="container">
            <h1>Join the best GYM of Lahore!</h1>
            <form id="signupForm" action="submit.php" method="POST">
                <div class="form-group">
                    <input type="text" id="Name" name="name" placeholder="Enter your Name" required />
                </div>
                <div class="form-group">
                    <input type="text" id="Age" name="age" placeholder="Enter your Age" required />
                </div>
                <div class="form-group">
                    <input type="text" id="Gender" name="gender" placeholder="Enter your Gender" required />
                </div>
                <div class="form-group">
                    <input type="email" id="Email" name="email" placeholder="Enter your Email" required />
                </div>
                <button class="btn" type="submit">Submit</button>
            </form>
            
        </div>

        <div class="centerleft">
            "Building Strong Bodies, Cultivating Strong Minds"
        </div>
    </header>

    <footer class="footer">
        <p>
            © 2024 Leo Fitness. All rights reserved. | Designed by
            [FHSIZ]
        </p>
    </footer>

    <!-- <script src="main.js"></script> -->
</body>
</html>
