<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MenuMaster";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection Failed");
}
$sql = "CREATE TABLE FoodOrders (
    OrderID INT PRIMARY KEY AUTO_INCREMENT,
    PersonName VARCHAR(100),
    EmailAddress VARCHAR(100),
    PhoneNumber VARCHAR(20),
    MenuSelection VARCHAR(255),
    DeliveryAddress VARCHAR(255),
    PreferredDeliveryTime DATETIME,
    PaymentInformation VARCHAR(255),
    AdditionalComments TEXT,
    SpecialInstructions TEXT
)";
if (mysqli_query($conn, $sql) === TRUE) {
    echo "table FoodOrders Created Sucessfully ";
} else {
    echo "Error creating table FoodOrders";
}
$sql = "CREATE TABLE EventBookings (
    BookingID INT PRIMARY KEY AUTO_INCREMENT,
    FullName VARCHAR(100),
    EmailAddress VARCHAR(100),
    PhoneNumber VARCHAR(20),
    EventType VARCHAR(50),
    DateAndTime DATETIME,
    NumberOfGuests INT,
    FoodPreferences VARCHAR(255),
    SpecialRequests TEXT
)";
if (mysqli_query($conn, $sql) === TRUE) {
    echo "table EventBookings Created Sucessfully ";
} else {
    echo "Error creating table EventBookings";
}
?>