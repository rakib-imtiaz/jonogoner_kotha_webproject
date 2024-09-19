<?php
session_start();
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the user exists
    $query = "SELECT * FROM User WHERE Email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // // Debug: print what we fetched from the database
        // echo "<pre>";
        // var_dump($user);
        // echo "</pre>";
        // Verify password
        if ($password == $user['password']) {
           
            $_SESSION['user'] = $user['Email'];
            header("Location: .././user/profile.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jonogoner Kotha - Login</title>
    <style>
        body,
        html {
            margin: 50px;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('/assets/images/image.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .login-container {
            transform: scale(.95);

            width: 400px;
            background-color: rgba(255, 255, 255, 0.9);
            /* Initial white background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.5s ease, background-color 2s ease, box-shadow 1s ease;
            /* Change background-color instead */
        }

        .login-container:hover {
            transform: scale(1.05);
            /* Enlarge the form slightly */
            background-image: linear-gradient(135deg, green, red);
            /* Gradient for Bangladesh flag */
            background-color: transparent;
            /* Transition to transparent to reveal the gradient */
            box-shadow: 0 0 20px 10px rgba(0, 255, 0, 0.7);
            color: white;
            /* Turn all text inside the container white */


        }

        .login-container:hover h1,
        .login-container:hover p,
        .login-container:hover a,
        .login-container:hover label {
            color: white;
            /* Apply white color to all text and input placeholders */
        }




        .login-container h1 {
            color: #006400;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            background-color: #006400;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #004d00;
        }

        .form-group a {
            display: block;
            margin-top: 10px;
            color: #006400;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .form-group a:hover {
            text-decoration: underline;
        }

        .social-login {
            margin-top: 20px;
        }

        .social-login button {
            width: 48%;
            padding: 10px;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 1%;
        }

        .social-login button.twitter {
            background-color: #1DA1F2;
            color: white;
        }

        .social-login button.google {
            background-color: #DB4437;
            color: white;
        }
    </style>
</head>

<body>

<?php include '../templates/header.php';?>
    <div class="login-container">
        <h1>Jonogoner Kotha</h1>

        <!-- Display error message if login fails -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Form submission should point to this same page with method POST -->
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">email:</label>
                <input style="color: black!important;" type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input style="color: black!important; " type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <div class="form-group">
                <a href="/index.php">Go Back</a>
            </div>
            <div class="form-group">
                <a href="#">Forgot Password?</a>
            </div>
        </form>

        <div class="social-login">
            <button class="twitter">Login with Twitter</button>
            <br>
            <br>
            <button class="google">Login with Google</button>
        </div>
    </div>
</body>



</html>