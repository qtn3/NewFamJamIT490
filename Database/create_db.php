<?php

$sqlhost = "localhost";
$sqluser = "richard";
$password = "pass";

// Create connection

$conn = new mysqli($sqlhost, $sqluser, $password);

if ($conn->connect_error){

	die("Failed to connect: " . $conn->connect_error);

}

$db = "CREATE DATABASE db";

if ($conn->query($db) === TRUE) {
	echo "Database created!";
}

else
{
	echo "Error creating database: " . $conn->error;
}


$conn->close();

?>
