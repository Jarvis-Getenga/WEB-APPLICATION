<?php
include("connection.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_id = $_POST["user-id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $user_image = $_FILES["user_image"];

    // Validate form data
    if (empty($user_id) || empty($username) || empty($password) || empty($email)) {
        $message = "Please fill in all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email";
    } elseif ($user_image["error"] != 0) {
        $message = "Error uploading image";
    } else {
        // Upload user image
        $image_path = "uploads/" . basename($user_image["name"]);
        move_uploaded_file($user_image["tmp_name"], $image_path);

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO users (USER_ID, username, password, email, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $username, $password_hash, $email, $image_path);
        $stmt->execute();

        // Check if user is inserted successfully
        if ($stmt->affected_rows > 0) {
            $message = "User registered successfully. <a href='login.php'>Click here to login</a>";
        } else {
            $message = "Error registering user";
        }

         // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
        }
        .container form label {
            margin-bottom: 5px;
        }
        .container form input[type="text"],
        .container form input[type="password"],
        .container form input[type="email"],
        .container form input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .container form input[type="submit"] {
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        .container form input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .message {
            margin-top: 20px;
            color: #d9534f;
        }
        .success {
            color: #5cb85c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="user-id">User ID:</label>
            <input type="text" id="user-id" name="user-id" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type">
                <option value="user">User</option>
                <option value="recipe_owner">Recipe Owner</option>
                <option value="admin">Admin</option>
            </select><br>

            <label for="user_image">Upload Image:</label>
            <input type="file" id="user_image" name="user_image" required>

            <input type="submit" value="Register">
        </form>
        <h2>If you have already registered, <a href="registration.php">log in here</a>.</h2>
    </div>
</body>
</html>
