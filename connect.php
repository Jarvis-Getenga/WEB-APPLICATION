<?php
include("connection.php");

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
        echo "Please fill in all fields";
        exit;
    }

    // Check if recipe image is uploaded
    if ($recipe_image["error"] != 0) {
        echo "Error uploading image";
        exit;
    }

    // Upload recipe image
    $image_path = "uploads/" . basename($recipe_image["name"]);
    move_uploaded_file($recipe_image["tmp_name"], $image_path);

    // Insert recipe into database
    $sql = "INSERT INTO recipes (RECIPE_ID, RECIPE_NAME, INGREDIENTS, RECIPE_IMAGE, USER_ID, CATEGORY_ID) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssii", $recipe_id, $recipe_name, $ingredients, $image_path, $user_id, $category_id);
    $stmt->execute();

    // Check if recipe is inserted successfully
    if ($stmt->affected_rows > 0) {
        echo "Recipe added successfully";
    } else {
        echo "Error adding recipe";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 50px;
            max-height: 50px;
        }
        .links {
            text-align: center;
            margin-bottom: 20px;
        }
        .links a {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Recipes</h2>
        
        <h3>Do you want to view, edit, or add recipes?</h3>
        <div class="links">
            <a href="view_recipes.php">View Recipes</a>
            <a href="edit_recipe.php">Edit Recipes</a>
            <a href="user recipe form.html">Add Recipe</a>
        </div>

        <?php
        // Display all recipes
        $sql = "SELECT * FROM recipes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>All Recipes</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Recipe ID</th><th>Recipe Name</th><th>Ingredients</th><th>Image</th><th>User ID</th><th>Category ID</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>". $row["RECIPE_ID"]. "</td>";
                echo "<td>". $row["RECIPE_NAME"]. "</td>";
                echo "<td>". $row["INGREDIENTS"]. "</td>";
                echo "<td><img src='". $row["RECIPE_IMAGE"]. "' width='50' height='50'></td>";
                echo "<td>". $row["USER_ID"]. "</td>";
                echo "<td>". $row["CATEGORY_ID"]. "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No recipes found</p>";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
