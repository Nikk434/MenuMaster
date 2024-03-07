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

    $person_name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $event_type = $_POST['eventType'];
    $event_datetime = $_POST['dateTime'];
    $num_guests = $_POST['guests'];
    $food_preferences = $_POST['foodPreferences'];
    $special_requests = $_POST['specialRequests'];

    $stmt = $conn->prepare("INSERT INTO eventbookings (FullName,EmailAddress,PhoneNumber,EventType,DateAndTime,NumberOfGuests,FoodPreferences,SpecialRequests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $person_name, $email, $phone, $event_type, $event_datetime, $num_guests, $food_preferences,$special_requests);

    if ($stmt->execute()) {
        $order_id = $conn->insert_id;

        $stmt->close();

        // Success message with JavaScript redirection
        echo "<script>alert('Thank you for your request. We will get back to you with confirmation.'); window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('Error placing order. Please try again.');</script>";
    }

    $conn->close();
}
?>
