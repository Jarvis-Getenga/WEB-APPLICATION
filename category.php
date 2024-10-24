<?php
include("connection.php");
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $category_name = $_POST["category_name"];
    $category_id = $_POST["category_id"];

    // Validate form data
    if (empty($category_name) || empty($category_id)) {
        echo "Please fill in all fields";
        exit;
    }

    // Check if category already exists
    $sql = "SELECT * FROM categories WHERE category_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Category already exists";
        exit;
    }

    // Insert category into database
    $sql = "INSERT INTO categories (category_id, category_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $category_id, $category_name);
    $stmt->execute();

    // Check if category is inserted successfully
    if ($stmt->affected_rows > 0) {
        echo "Category added successfully";
    } else {
        echo "Error adding category";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// Display all categories
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Categories</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Category ID</th><th>Category Name</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". $row["CATEGORY_ID"]. "</td>";
        echo "<td>". $row["CATEGORY_NAME"]. "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No categories found";
}

// Close connection
$conn->close();
?>

