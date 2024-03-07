<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "menumaster";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $person_name = $_POST['person_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $delivery_address = $_POST['delivery_address'];
    $preferred_delivery_time = $_POST['preferred_delivery_time'];
    $additional_comments = $_POST['additional_comments'];
    $special_instructions = $_POST['special_instructions'];

    $stmt = $conn->prepare("INSERT INTO foodorders (PersonName,EmailAddress,PhoneNumber,DeliveryAddress,PreferredDeliveryTime,AdditionalComments,SpecialInstructions) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $person_name, $email, $phone, $delivery_address, $preferred_delivery_time, $additional_comments, $special_instructions);

    if ($stmt->execute()) {
        $order_id = $conn->insert_id;

        if (isset($_POST['selected_dishes'])) {
            $selected_dishes = $_POST['selected_dishes'];
            $total_price = 0;

            echo "<script>alert('Selected Dishes:\\n";
            foreach ($selected_dishes as $dish) {
                echo "$dish\\n";
                // Query dish price
                $query_price = "SELECT DishPrice FROM menu WHERE DishName = ?";
                $stmt_price = $conn->prepare($query_price);
                $stmt_price->bind_param("s", $dish);
                $stmt_price->execute();
                $result_price = $stmt_price->get_result();
                if ($result_price->num_rows > 0) {
                    $row = $result_price->fetch_assoc();
                    $total_price += $row['DishPrice'];
                }
            }
            echo "\\nTotal Price: Rs $total_price');</script>";
            
            // Success message
            echo "<script>alert('Order placed successfully.');</script>";
            ?>
            <script type="text/javascript">
                window.location = "index.html";
            </script>
            <?php
        } else {
            echo "<script>alert('Error placing order. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Error placing order. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
