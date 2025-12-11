<?php
//connecting to the database on the server

// click buttons to fetch data to print out the data
//slide 43 used to fetch then print out the data

$servername = 'sql1.njit.edu';
$username = "";
$password = "";
$dbname = "";
$conn = mysqli_connect($servername, $username, $password, $dbname); 
if(mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}





?>

