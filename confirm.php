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

        $stmt->close();

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

    $conn->close();
}
?>