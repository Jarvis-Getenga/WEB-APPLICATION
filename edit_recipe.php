<?php
include("connection.php");

// Initialize variables
$recipe_id = $recipe_name = $ingredients = $recipe_image = $user_id = $category_id = "";
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $recipe_id = $_POST["recipe_id"];
    $recipe_name = $_POST["recipe_name"];
    $ingredients = $_POST["ingredients"];
    $recipe_image = $_FILES["recipe_image"];
    $user_id = $_POST["user_id"];
    $category_id = $_POST["category_id"];

    // Validate form data
    if (empty($recipe_id) || empty($recipe_name) || empty($ingredients) || empty($user_id) || empty($category_id)) {
        $message = "Please fill in all fields";
    } elseif ($recipe_image["error"] != 0) {
        $message = "Error uploading image";
    } else {
        // Check if recipe exists
        $check_sql = "SELECT * FROM recipes WHERE RECIPE_ID = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $recipe_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Recipe exists, update recipe details
            $row = $result->fetch_assoc();
            $existing_image = $row["RECIPE_IMAGE"];

            // Upload new recipe image if provided
            if (!empty($recipe_image["name"])) {
                $image_path = "uploads/" . basename($recipe_image["name"]);
                move_uploaded_file($recipe_image["tmp_name"], $image_path);
            } else {
                $image_path = $existing_image;
            }

            // Update recipe in database
            $update_sql = "UPDATE recipes SET RECIPE_NAME = ?, INGREDIENTS = ?, RECIPE_IMAGE = ?, USER_ID = ?, CATEGORY_ID = ? WHERE RECIPE_ID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssii", $recipe_name, $ingredients, $image_path, $user_id, $category_id, $recipe_id);
            $update_stmt->execute();

            if ($update_stmt->affected_rows > 0) {
                $message = "Recipe updated successfully";
            } else {
                $message = "Error updating recipe";
            }
        } else {
            $message = "Recipe not found";
        }


    }
} else {
    // Check if recipe ID is provided via GET parameter
    if (isset($_GET["recipe_id"])) {
        $recipe_id = $_GET["recipe_id"];

        // Fetch recipe details from database
        $fetch_sql = "SELECT * FROM recipes WHERE RECIPE_ID = ?";
        $fetch_stmt = $conn->prepare($fetch_sql);
        $fetch_stmt->bind_param("i", $recipe_id);
        $fetch_stmt->execute();
        $result = $fetch_stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $recipe_name = $row["RECIPE_NAME"];
            $ingredients = $row["INGREDIENTS"];
            $user_id = $row["USER_ID"];
            $recipe_image = $_FILES["recipe_image"];
            $category_id = $row["CATEGORY_ID"];
        } else {
            $message = "Recipe not found";
        }

        // Close statement
        $fetch_stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .message {
            text-align: center;
            margin-bottom: 10px;
            color: #d9534f;
        }
        .success {
            text-align: center;
            margin-bottom: 10px;
            color: #5cb85c;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"], textarea, input[type="file"], input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Recipe</h2>
        <?php if (!empty($message)): ?>
            <div class="<?php echo strpos($message, 'successfully') !== false ? 'success' : 'message'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="user_id">Recipe ID:</label>
            <input type="number" id="recipe_id" name="recipe_id" value="<?php echo $recipe_id; ?>" required>    
       
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" id="recipe_name" name="recipe_name" value="<?php echo $recipe_name; ?>" required>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" required><?php echo $ingredients; ?></textarea>
            <label for="recipe_image">Recipe Image:</label>
            <input type="file" id="recipe_image" name="recipe_image">
            <label for="user_id">User ID:</label>
            <input type="number" id="user_id" name="user_id" value="<?php echo $user_id; ?>" required>
            <label for="category_id">Category ID:</label>
            <input type="number" id="category_id" name="category_id" value="<?php echo $category_id; ?>" required>
            <input type="submit" value="Update Recipe">
        </form>
    </div>
</body>
</html>
