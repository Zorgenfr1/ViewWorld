<?php
    $is_invalid = false;

    // Database configuration
    $servername = "localhost";
    $username = "root"; // Default username for XAMPP
    $password = "=5Kf&RolYB"; // Use the correct password here if you've set one
    $dbname = "ViewWorld";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST["login"])){
        $sql = sprintf("SELECT * FROM users WHERE userid = '%s'",
                        $conn->real_escape_string($_POST["userid"]));

        $result = $conn->query($sql);

        $user = $result->fetch_assoc();

        if ($user) {
            if(password_verify($_POST["password"], $user["password_hash"])){
                session_start();

                $_SESSION["user_id"] = $user["userid"];
                $_SESSION["name"] = $user["name"];

                header("location: home.php");
                exit;
            }
            else{
                $is_invalid = true;
            }
        }
        else{
            $is_invalid = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <header>
        <?php include("head.php"); ?>
    </header>
    <div class="login">
        <h1>Log In</h1>

        <?php if ($is_invalid): ?>
            <em>Invalid login <a href="register.php">register here</a></em>
        <?php endif; ?>

        <form method="post">

            <label>Userid:</label><br>
            <input type="text" name="userid"><br>

            <label>Password:</label><br>
            <input type="password" name="password"><br>

            <input type="submit" value="LogIn" name="login">
        </form>
    </div>
</body>
</html>