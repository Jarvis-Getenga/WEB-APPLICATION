<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'RecipeOwner') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'RecipeDB');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe_name = $_POST['recipe_name'];
    $category_id = $_POST['category_id'];
    $user_id = $_SESSION['user_id'];
    $ingredients = explode(',', $_POST['ingredients']);
    $steps = explode('.', $_POST['steps']);
    
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO Recipes (recipe_name, image_path, user_id, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $recipe_name, $image_path, $user_id, $category_id);
    $stmt->execute();
    $recipe_id = $stmt->insert_id;

    foreach ($ingredients as $ingredient) {
        $stmt = $conn->prepare("INSERT INTO Ingredients (recipe_id, ingredient_name) VALUES (?, ?)");
        $stmt->bind_param("is", $recipe_id, trim($ingredient));
        $stmt->execute();
    }

    $step_number = 1;
    foreach ($steps as $step) {
        $stmt = $conn->prepare("INSERT INTO Steps (recipe_id, step_number, step_description) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $recipe_id, $step_number, trim($step));
        $stmt->execute();
        $step_number++;
    }

    echo "Recipe added successfully!";
    header("Location: view_recipes.php");
}
?>
