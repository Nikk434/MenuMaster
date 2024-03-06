<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dishes</title>
    <style>
        .category {
            display: flex;
            font-size: 20px;
        }

        .category div {
            margin-right: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Menu</h1>
    <form method="post" action="">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "menumaster";

        // Establish database connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query for distinct categories
        $query_categories = "SELECT DISTINCT DishCategory FROM menu";
        $result_categories = $conn->query($query_categories);

        if ($result_categories->num_rows > 0) {
            while ($category_row = $result_categories->fetch_assoc()) {
                $category = $category_row['DishCategory'];

                // Query for dishes in this category
                $query_dishes = "SELECT DishName, DishPrice FROM menu WHERE DishCategory = ?";
                $stmt = $conn->prepare($query_dishes);
                $stmt->bind_param("s", $category);
                $stmt->execute();
                $result_dishes = $stmt->get_result();

                echo "<div class='category'>";
                echo "<div><h2>$category</h2></div>";
                echo "<table>";
                echo "<tr><th>Dish Name</th><th>Dish Price (Rs)</th><th>Select</th></tr>";

                // Display dishes for this category
                while ($dish_row = $result_dishes->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $dish_row['DishName'] . "</td>";
                    echo "<td>" . $dish_row['DishPrice'] . "</td>";
                    echo "<td><input type='checkbox' name='selected_dishes[]' value='" . $dish_row['DishName'] . "'></td>";
                    echo "</tr>";
                }

                echo "</table></div>";
            }
            echo "<input type='submit' name='submit' value='Confirm Selection'>";
        } else {
            echo "No categories found";
        }

        // Close database connection
        $conn->close();
        ?>
    </form>

    <?php
    // Process form submission
    if (isset($_POST['submit'])) {
        if (isset($_POST['selected_dishes'])) {
            $selected_dishes = $_POST['selected_dishes'];
            $total_price = 0;

            echo "<script>alert('Selected Dishes:\\n";
            foreach ($selected_dishes as $dish) {
                echo "$dish\\n";
                // Query dish price
                $conn = new mysqli($servername, $username, $password, $dbname);
                $query_price = "SELECT DishPrice FROM menu WHERE DishName = ?";
                $stmt = $conn->prepare($query_price);
                $stmt->bind_param("s", $dish);
                $stmt->execute();
                $result_price = $stmt->get_result();
                if ($result_price->num_rows > 0) {
                    $row = $result_price->fetch_assoc();
                    $total_price += $row['DishPrice'];
                }
                $conn->close();
            }
            echo "\\nTotal Price: Rs $total_price');</script>";
        } else {
            echo "<p>No dishes selected</p>";
        }
    }
    if (isset($_POST['submitform'])) {
        ?>
        <script type="text/javascript">
            window.location = "confirm.html";
        </script>
        <?php
    }
    ?>
</body>

</html>