<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . $_SESSION['username'];
echo " | <a href='logout.php'>Logout</a>";

if ($_SESSION['user_type'] == 'Admin') {
    header("Location: admin_dashboard.php");
    exit();
} else if ($_SESSION['user_type'] == 'RecipeOwner') {
    header("Location: recipe_owner_dashboard.php");
    exit();
}
?>
