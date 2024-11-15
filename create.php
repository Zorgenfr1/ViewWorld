<?php
    include("connection.php");
    include("sidemenu.php");

    session_start();

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    echo"{$_SESSION["user_id"]}";

    if(isset($_POST['Create'])){
        $fileName = $_FILES['image']['name'];
        $fileTempName = $_FILES['image']['tmp_name'];
        $fileType = mime_content_type($fileTempName);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileDestination = 'images/'. $fileName;
        if (in_array($fileType, $allowedTypes) && in_array($fileExtension, $allowedExtensions)){
            if (move_uploaded_file($fileTempName, $fileDestination)) {
                echo "File uploaded and moved successfully!";

                $sql = $conn->prepare("INSERT INTO posts (user_id, image_url, caption, likes_count, comments_count, created_at) 
                        VALUES
                        (?, ?, ?, 0, 0, NOW())");
                $image_url = 'http://localhost:8080/ViewWorld/' . $fileDestination;
                $user_id = $_SESSION["user_id"];
                $caption = $_POST["caption"];


                $sql->bind_param("sss", $user_id, $image_url, $caption);

                if ($sql->execute()) {
                    echo "Record inserted successfully!";
                    header("Location: profile.php");
                } else {
                    echo "Error: " . $sql->error;
                }

            } else {
                echo "File upload failed.";
            }
        }
        
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="styles/create.css">
</head>
<body>
    <header>
        <?php
        include("head.php");
        ?>
    </header>
    <div class="create">
        <form action="create.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" id="image" accept="image/*">
            <label for="image"><img src="images/camera.svg" alt="">Choose a picture</label>
            <div class="textarea-submit">
                <textarea name="caption" placeholder="Create a catchy caption"></textarea>
                <button class="submit-button" type="submit" name="Create" value="Create"><img class="logo-submit" src="images/uploadlogo.png" alt="">Create</button>
            </div>
        </form>
    </div>
</body>
</html>