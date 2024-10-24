<?php
include("connection.php");

$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipes</title>
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
            max-width: 800px;
            width: 100%;
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>View Recipes</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Recipe ID</th>
                    <th>Recipe Name</th>
                    <th>Ingredients</th>
                    
                    <th>Category ID</th>
                    <th>Recipe Image</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["RECIPE_ID"]; ?></td>
                        <td><?php echo $row["RECIPE_NAME"]; ?></td>
                        <td><?php echo $row["INGREDIENTS"]; ?></td>
                        <td><?php echo $row["CATEGORY_ID"]; ?></td>
                        <td><img src="<?php echo $row["RECIPE_IMAGE"]; ?>" width="50" height="50"></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No recipes found</p>
        <?php endif; ?>
    </div>
</body>
</html>
