<?php
include("connection.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipe_name = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];
    $recipe_image = $_FILES['recipe_image']['name'];
    $user_id = $_POST['user_id'];
    $category_id = $_POST['category_id'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($recipe_image);

    if (move_uploaded_file($_FILES['recipe_image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO recipes (recipe_name, ingredients, recipe_image, user_id, category_id) VALUES ('$recipe_name', '$ingredients', '$target_file', '$user_id', '$category_id')";

        if ($conn->query($sql) === TRUE) {
            echo "New recipe added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>
