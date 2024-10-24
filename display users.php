<?php
include("connection.php");
// Select all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Check if there are any users
if ($result->num_rows > 0) {
    // Display users in an HTML table
    echo "<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>";
    echo "<table id='users-table'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Image</th><th>Edit</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row["USER_ID"]. "</td>";
        echo "<td>". $row["USERNAME"]. "</td>";
        echo "<td>". $row["EMAIL"]. "</td>";
        echo "<td><img src='". $row["Image"]. "' width='50' height='50'></td>";
        echo "<td><a href='edit_user.php?id=". $row["USER_ID"]. "'>Edit</a></td>";
        echo "</tr>";
    }
    echo "</table>";

    // Pagination
    $total_rows = $result->num_rows;
    $rows_per_page = 10;
    $total_pages = ceil($total_rows / $rows_per_page);
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=". $i. "'>". $i. "</a>";
    }
    echo "</div>";
} else {
    echo "No users found";
}

// Close connection
$conn->close();
?>

<!-- Edit user functionality -->
<?php
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
    $sql = "SELECT * FROM users WHERE USER_ID = $user_id";
    $result = $conn->query($sql);
    $user_data = $result->fetch_assoc();

    if (isset($_POST["update"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $image_url = $_POST["image_url"];

        $sql = "UPDATE users SET username = ?, email = ?, image_url = ? WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $image_url, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "User updated successfully";
        } else {
            echo "Error updating user";
        }

        $stmt->close();
    }
?>

<!-- Edit user form -->
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="display users.css">
    <title>Document</title>
 </head>
 <body>
 <h3>DO YOU WANT TO VIEW RECIPES <a href="view_recipes.php"></a></h3>
 <form action="" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user_data["username"]; ?>"><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user_data["email"]; ?>"><br>
    <label for="image_url">Image URL:</label>
    <input type="text" id="image_url" name="image_url" value="<?php echo $user_data["image_url"]; ?>"><br>
    <input type="submit" name="update" value="Update">
</form>

 </body>
 </html>
 
 


<?php
}
?>