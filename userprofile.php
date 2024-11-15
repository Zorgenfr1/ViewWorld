<?php
include("connection.php");
session_start();
include("sidemenu.php");

if (!isset($_GET['show_user'])) {
    echo "User not specified.";
    exit();
}

$show_user = $conn->real_escape_string($_GET['show_user']);

// Query to fetch user profile information
$profileSql = sprintf("SELECT userid, name, profile_pic, bio, follower_count, following_count 
                       FROM users 
                       WHERE userid = '%s'", 
                       $show_user);
$profile = $conn->query($profileSql);

// Query to fetch user's posts
$postsSql = sprintf("SELECT image_url AS post_pic, caption 
                     FROM posts 
                     WHERE user_id = '%s' 
                     ORDER BY created_at DESC 
                     LIMIT 1000", 
                     $show_user);
$posts = $conn->query($postsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <header>
        <?php include("head.php");
        ?>

    </header>
    <div class="profile_info">
        <?php
            if ($profile->num_rows > 0) {
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
            else{
                echo "<p>User not found.</p>";
            }
        ?>
    </div>
    <div class="images">
        <?php
            if ($posts->num_rows > 0) {
                while ($row = $posts->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<div class='post-image-container'>";
                    echo "<img class='post-pic' src='{$row['post_pic']}' alt='Post Image'><br>";
                    echo "</div>";
                    echo "<p class='caption'>{$row['caption']}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No posts found.</p>";
            }
        ?>
    </div>
</body>
</html>
