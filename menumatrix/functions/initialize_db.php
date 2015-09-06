<?php

echo 'initalzing database...';

$servername = "localhost";
$username = "root";
$password = "";

$database="MenuMatrix";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Create database

$sql = "DROP DATABASE IF EXISTS ".$database;

if ($conn->query($sql) === TRUE) {
    echo "Database dropeed successfully \n";
} else {
    echo "Error dropiing database: " . $conn->error . "\n";
}


$sql = "CREATE DATABASE IF NOT EXISTS ".$database;

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully \n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

if (!mysqli_select_db($conn, $database)) {
    throw new Exception("Database: ". $database . " not selected", 1);

}


function makeQuery($querystring, $errormsg, $successmsg, $connection){
	if ($connection->query($querystring) === TRUE) {
	    echo "<p>".$successmsg."</p><br>";
	} 

	else {
	    echo "<p>".$errormsg."</p><br>";
	}
}





$createtable = "CREATE TABLE Account(
account_id INT(8) AUTO_INCREMENT PRIMARY KEY,
supplier_name VARCHAR(128) NOT NULL,
supplier_notes VARCHAR(255) NOT NULL,
is_deleted BOOLEAN NOT NULL
)";

makeQuery($createtable, "failed to create table due to: " . $conn->error, "Account table created", $conn);





$createtable = "CREATE TABLE Supplier(
supplier_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
account_id INT(8) NOT NULL,
FOREIGN KEY (account_id) REFERENCES Account(account_id),
supplier_name VARCHAR(128) NOT NULL,
supplier_notes VARCHAR(255) NOT NULL,
is_deleted BOOLEAN NOT NULL
)";


makeQuery($createtable, "failed to create Supplier table due to: " . $conn->error, "Supplier table created", $conn);



$createtable = "CREATE TABLE Ingredient(
ingredient_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
account_id INT(8) FOREIGN KEY REFERENCES Account(account_id),
ingredient_name VARCHAR(128) NOT NULL,
default_unit VARCHAR(64) NOT NULL,
is_deleted BOOLEAN NOT NULL,
months_available VARCHAR(128),
)";

makeQuery($createtable, "failed to create Ingredient table due to: " . $conn->error, "Ingredient table created", $conn);


$createtable = "CREATE TABLE Recipe(
recipe_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
account_id INT(8) FOREIGN KEY REFERENCES Account(account_id),
recipe_name VARCHAR(128) NOT NULL,
portions VARCHAR(128),
recipe_method TEXT,
is_deleted BOOLEAN NOT NULL
)";

makeQuery($createtable, "failed to create Recipe table due to: " . $conn->error, "Recipe table created", $conn);




$createtable = "CREATE TABLE Recipe_Ingredient(
recipe INT(8) FOREIGN KEY REFERENCES Recipe(recipe_id),
ingredient INT(8) FOREIGN KEY REFERENCES Ingredient(ingredient_id),
ingredient_qty INT(4) NOT NULL,
)";

makeQuery($createtable, "failed to create Recipe_Ingredient table due to: " . $conn->error, "Recipe_Ingredient table created", $conn);

$createtable = "CREATE TABLE Event(
event_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
account_id INT(8) FOREIGN KEY REFERENCES Account(account_id),
event_name VARCHAR(128) NOT NULL,
no_of_guests INT(6),
event_date DATE,
comments TEXT,
is_deleted BOOLEAN NOT NULL
)";

makeQuery($createtable, "failed to create Event table due to: " . $conn->error, "Event table created", $conn);

$createtable = "CREATE TABLE Event_Recipe(
recipe INT(8) FOREIGN KEY REFERENCES Recipe(recipe_id),
ingredient INT(8) FOREIGN KEY REFERENCES Event(event_id),
quantity INT(4) NOT NULL,
)";


makeQuery($createtable, "failed to create Event_Recipe table due to: " . $conn->error, "Event_Recipe table created", $conn);

$createtable = "CREATE TABLE Menu(
menu_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
account_id INT(8) FOREIGN KEY REFERENCES Account(account_id),
notes TEXT,
is_deleted BOOLEAN NOT NULL
)";

makeQuery($createtable, "failed to create Menu table due to: " . $conn->error, "Menu table created", $conn);


$createtable = "CREATE TABLE Menu_Recipe(
recipe INT(8) FOREIGN KEY REFERENCES Recipe(recipe_id),
menu INT(8) FOREIGN KEY REFERENCES Menu(menu_id),
quantity INT(4) NOT NULL,
)";

makeQuery($createtable, "failed to create Menu_Recipe table due to: " . $conn->error, "Menu_Recipe table created", $conn);

$createtable = "CREATE TABLE User(
user_id INT(8) PRIMARY KEY,
account_id INT(8) FOREIGN KEY REFERENCES Account(account_id),
email VARCHAR(256),
pasword_hash VARCHAR(256) NOT NULL,
reset_token VARCHAR(256) NOT NULL,
is_deleted BOOLEAN NOT NULL,
privileges VARCHAR(256)
)";

makeQuery($createtable, "failed to create User table due to: " . $conn->error, "User table created", $conn);



$conn->close();


?>