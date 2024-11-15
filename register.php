<?php
include("connection.php");
session_start();

// Allowed types and extensions for profile picture
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (isset($_POST['Register'])) {
    // Getting form input values
    $userid = $_POST['userid'];
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Validate if passwords match
    if ($password !== $repeat_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Process profile picture upload if provided
    $profile_pic_url = '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['profile_pic']['name'];
        $fileTempName = $_FILES['profile_pic']['tmp_name'];
        $fileType = mime_content_type($fileTempName);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileDestination = 'images/' . $fileName;

        // Check if file type and extension are allowed
        if (in_array($fileType, $allowedTypes) && in_array($fileExtension, $allowedExtensions)) {
            if (move_uploaded_file($fileTempName, $fileDestination)) {
                $profile_pic_url = 'http://localhost:8080/ViewWorld/' . $fileDestination;
                echo "Profile picture uploaded successfully!";
            } else {
                echo "Failed to upload profile picture.";
            }
        } else {
            echo "Invalid file type or extension for profile picture.";
            exit;
        }
    }

    // Prepare SQL statement to insert user data
    $sql = $conn->prepare("INSERT INTO users (userid, name, profile_pic, bio, follower_count, following_count, post_count, email, password_hash) 
                           VALUES (?, ?, ?, ?, 0, 0, 0, ?, ?)");

    // Bind parameters
    $sql->bind_param("ssssss", $userid, $name, $profile_pic_url, $bio, $email, $password_hash);

    // Execute the SQL statement
    if ($sql->execute()) {

        $_SESSION["user_id"] = $userid;
        $_SESSION["name"] = $name;

        echo "User profile created successfully!";
        // Redirect to profile page or login page
        header("Location: profile.php");
        exit;
    } else {
        echo "Error: " . $sql->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/register.css">
</head>
<body>
    <header>
        <?php include("head.php"); ?>
    </header>

    <div class="profile-info">
        <h2>Register</h2><br>
        <form action="register.php" method="POST" enctype="multipart/form-data" class="register-form">
            <div class="userInfo">
                
                <div class="profile-pic-and-name">
                    <input type="file" name="profile_pic" accept="image/*" id="profile_pic">
                    <label for="profile_pic" id="profile_pic_label">Choose a profile picture:</label><br>

                    <div class="name-info">
                        <div class="input-group">
                            <label for="userid">Userid:</label>
                            <input type="text" name="userid" value="@" required><br>
                        </div>
                        <div class="input-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" required><br>
                        </div>
                    </div>
                </div>

                <label for="bio">Bio:</label>
                <textarea name="bio" rows="4"></textarea><br>
                
                <label for="email">Email:</label><br>
                <input type="email" name="email" required><br>
                
                <label for="password">Password:</label><br>
                <input type="password" name="password" required><br>
                
                <label for="repeat_password">Repeat Password:</label><br>
                <input type="password" name="repeat_password" required><br>
                
                <button type="submit" name="Register">Register</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("profile_pic").addEventListener("change", function(event) {
            const label = document.getElementById("profile_pic_label");
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    label.style.backgroundImage = `url('${e.target.result}')`;
                    label.style.backgroundSize = "cover";
                    label.style.backgroundPosition = "center";
                    label.textContent = ""; // Optionally, remove the text inside the label
                };
                reader.readAsDataURL(file);
            }
        });
    </script>


</body>
</html>
