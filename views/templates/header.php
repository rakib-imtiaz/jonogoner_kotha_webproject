<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jonogoner Kotha</title>
    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- jQuery and Bootstrap JS (Optional for Navbar) -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Header Section -->
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/logo.png" alt="Jonogoner Kotha Logo" height="40">
                    Jonogoner Kotha
                </a>
                <!-- Toggle Button for Mobile View -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="issues.php">Issues</a></li>
                        <li class="nav-item"><a class="nav-link" href="solutions.php">Solutions</a></li>
                        <li class="nav-item"><a class="nav-link" href="polls.php">Jonogoner Motamot</a></li>
                        <li class="nav-item"><a class="nav-link" href="petitions.php">Petitions</a></li>
                        <li class="nav-item"><a class="nav-link" href="emergency.php">Joruri Shongbad</a></li>
                        <!-- Check if user is logged in -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                    <?php echo $_SESSION['username']; ?>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="profile.php">Profile</a>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
