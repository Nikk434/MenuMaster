<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Food Order Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        select {
            height: 40px;
        }

        textarea {
            height: 100px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
        }
        img{
            height: 50%;
            width: 50%;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Restaurant Food Order Form</h2>
        <form method="post" action="confirm.php">
            <input type="text" placeholder="Person Name" name="person_name" required>
            <input type="email" placeholder="Email Address" name="email" required>
            <input type="text" placeholder="Phone Number" name="phone" required>

            <!-- Display dishes and checkboxes for selection -->
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
                echo "<h1>Menu</h1>";
                while ($category_row = $result_categories->fetch_assoc()) {
                    $category = $category_row['DishCategory'];

                    // Query for dishes in this category
                    $query_dishes = "SELECT DishName, DishPrice FROM menu WHERE DishCategory = ?";
                    $stmt = $conn->prepare($query_dishes);
                    $stmt->bind_param("s", $category);
                    $stmt->execute();
                    $result_dishes = $stmt->get_result();
                    // echo "<center>";
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
                    // echo "</center>";
            
                }
            } else {
                echo "<p>No categories found</p>";
            }

            // Close database connection
            $conn->close();
            ?>

            <textarea placeholder="Delivery Address" name="delivery_address" required></textarea>
            Preferred Delivery Time
            <input type="datetime-local" placeholder="Preferred Delivery Time" name="preferred_delivery_time">
            <br>
            Payment <br>
            <center>
            <img src="UPI.jpeg" >
            </center>
            <textarea placeholder="Additional Comments/Questions" name="additional_comments"></textarea>
            <textarea placeholder="Special Instructions" name="special_instructions"></textarea>
            <input type="submit" name="submit" value="Place Order">
        </form>
    </div>

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
    // if (isset($_POST['submit'])) {
    //      ?>
    //
    <script type="text/javascript">
        //         window.location = "confirm.php";
        //     </script>
    //
    <?php
    // }
    ?>
</body>

</html>