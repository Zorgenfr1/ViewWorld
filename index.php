<?php
include("connection.php");

session_start();

// SQL query to select user information
$sql = "SELECT userid, name, profile_pic, bio, follower_count, following_count, post_count FROM users LIMIT 1000";
$result = $conn->query($sql);

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profiles</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        img {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
<h1>
    View World
</h1>

<?php if (isset($_SESSION["user_id"])): ?>
    <p>You are logged in</p>
<?php else: ?>
    <p><a href="login.php">Log In</a></p>
<?php endif; ?>

<h1>User Profiles</h1>

<?php
// Check if there are results and display them
if ($result->num_rows > 0) {
    // Start HTML table
    echo "<table>";
    echo "<tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Profile Picture</th>
            <th>Bio</th>
            <th>Follower Count</th>
            <th>Following Count</th>
            <th>Post Count</th>
          </tr>";

    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['userid']}</td>
                <td>{$row['name']}</td>
                <td><img src='{$row['profile_pic']}' alt='{$row['name']}'></td>
                <td>{$row['bio']}</td>
                <td>{$row['follower_count']}</td>
                <td>{$row['following_count']}</td>
                <td>{$row['post_count']}</td>
              </tr>";
    }

    // End HTML table
    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}

// Close the database connection
$conn->close();
?>

</body>
</html>
