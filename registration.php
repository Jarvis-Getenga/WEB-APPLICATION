<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'LOGIN');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $conn->real_escape_string($_POST['user_type']);

    $sql = "INSERT INTO users (username, password, user_type) VALUES ('$username', '$password', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. ";
        if ($user_type == 'RecipeOwner') {
            header('Location: connect.php');
        } elseif ($user_type == 'Admin') {
            header('Location: display users.php');
        } else {
            header('Location: index.html');
        }
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LOG IN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
            text-align: center;
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
        .container form select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .container form input[type="submit"] {
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .container form input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .container form button {
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-decoration: none;
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        .container form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Registration</h1>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type">
                <option value="RecipeOwner">Recipe Owner</option>
                <option value="Admin">Admin</option>
            </select>

            <button style="color: white; text-decoration: none;">Login here</button>
        </form>
    </div>
</body>
</html>
