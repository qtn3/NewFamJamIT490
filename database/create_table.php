<?php

$sqlhost = "localhost";
$sqluser = "richard";
$password = "pass";
$dbname = "db";
// Connection
$conn = new mysqli($sqlhost, $sqluser, $password, $dbname);

if ($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE users (id INT(200) NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(30) NOT NULL, password VARCHAR(30) NOT NULL, email VARCHAR(40) NULL)";

if ($conn->query($sql) === TRUE) {
	echo "Table created!";
}
else {
	echo "Error occured: " . $conn->error;
}

$conn->close();

?>
