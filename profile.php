<?php
include("connection.php");
include("sidemenu.php");

session_start();

$profileSql = sprintf("SELECT users.userid, users.name, users.profile_pic, users.bio, users.follower_count, users.following_count
        FROM users 
        WHERE userid = '%s'",
        $conn->real_escape_string($_SESSION["user_id"]));

$profile = $conn->query($profileSql);

$postsSql = sprintf("SELECT p.image_url AS post_pic, p.caption
        FROM posts p
        JOIN users u ON p.user_id = u.userid
        WHERE userid = '%s'
        ORDER BY p.created_at DESC
        LIMIT 1000",
        $conn->real_escape_string($_SESSION["user_id"]));

$posts = $conn->query($postsSql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="Styles/profile.css">
</head>
<body>
    <header>
        <?php include("head.php");
        ?>
    </header>
    <div class="profile_info">
        <?php
            if ($profile->num_rows > 0) {
                // Output data for the user
                while ($row = $profile->fetch_assoc()) {
                    echo "<div class='userInfo'>";
                    echo "  <img class='profile-pic' src='{$row['profile_pic']}' alt='Profile Picture'>";
                    echo "  <div class='user_name'>";
                    echo "      <p class='userid'>{$row['userid']}</p><br>";
                    echo "      <p class='name'>{$row['name']}</p><br>";
                    echo "  </div>";
                    echo "</div>";
                    echo "<p class='bio'>{$row['bio']}</p><br>";
                    echo "<div class='stats'>";
                    echo "  <p class='bio'>{$row['follower_count']} followers</p>";
                    echo "  <p class='bio'>{$row['following_count']} following</p>";
                    echo "</div>";
                }
            }
            echo "<script>console.log('Profile shown');</script>";
        ?>
    </div>
    <div class="images">
        <?php
            if ($posts->num_rows > 0) {
                echo "<script>console.log('Posts found');</script>";
                // Output data for each post
                while ($row = $posts->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<div class='post-image-container'>";
                    echo "<img class='post-pic' src='{$row['post_pic']}' alt='Post Image'><br>";
                    echo "</div>";
                    echo "<p class='caption'>{$row['caption']}</p>";
                    echo "</div>";
                }
            }
            echo "<script>console.log('Posts shown');</script>";
        ?>
    </div>
</body>
</html>