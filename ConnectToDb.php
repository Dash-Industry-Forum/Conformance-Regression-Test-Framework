<?php

// For using the PHP MongoDB Library.
require 'vendor/autoload.php';

//Variables for database connection.
$database_name = "TestAssets";
$database_url = "localhost:27017";
$database_user = "";
$database_password = "";

// Connect to MongoDB Server.
$client = new \MongoDB\Client("mongodb://{$database_url}");

// Choose the required database.
$db = $client->$database_name;

?>
