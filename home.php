<?php
include("connection.php");

session_start();

// SQL query to select post information along with user profile picture
$sql = sprintf("SELECT p.post_id, p.user_id, p.image_url AS post_pic, p.caption, p.likes_count AS like_count, p.comments_count AS comment_count, u.profile_pic AS profile_pic
        FROM posts p
        JOIN users u ON p.user_id = u.userid
        WHERE userid != '%s'
        ORDER BY p.created_at DESC
        LIMIT 1000",
        $conn->real_escape_string($_SESSION["user_id"]));

$result = $conn->query($sql);

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View World</title>
    <link rel="stylesheet" href="styles/home.css">
</head>
<body>
    <header>
        <?php include("head.php");
        ?>

    </header>
    <div class="welcome">
        <h1>View World</h1>

        <?php if (isset($_SESSION["user_id"])): ?>
            <p>Welcome back <?php echo"{$_SESSION["name"]}"?></p>
        <?php else: ?>
            <p>Press here to <a href="login.php">Log In</a></p>
        <?php endif; ?>
    </div>

    <?php
    include("sidemenu.php");
    ?>

    <div class="home">
        <?php
        // Check if there are results and display them
        if ($result->num_rows > 0) {
            // Output data for each post
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<div class='userInfo'>";
                echo "<img class='profile-pic' src='{$row['profile_pic']}' alt='Profile Picture'>";
                echo "<p class='userid'><a href='userprofile.php?show_user={$row['user_id']}'>{$row['user_id']}</a></p><br>";
                echo "</div>";
                echo "<img class='post-pic' src='{$row['post_pic']}' alt='Post Image'><br>";
                echo "<img class='ui-logo' src='http://localhost:8080/ViewWorld/images/heart.png' alt=''>";
                echo "<p class='info'>{$row['like_count']} likes</p>";
                echo "<p class='caption'>{$row['caption']}</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
