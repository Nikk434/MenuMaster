<?php
$servername = "localhost";
$username= "root";
$password = "";
$dbname= "";

$conn = mysqli_connect($servername,$username,$password,$dbname);
if(!$conn)
{
    die("Connection Failed");
}
$sql = "Create database MenuMaster";
if(mysqli_query($conn,$sql) === TRUE)
{
    echo "Database MenuMaster Created Sucessfully ";
}
else
{
    echo "Error creating database";
}
?>