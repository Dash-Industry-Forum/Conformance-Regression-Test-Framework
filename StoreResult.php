<?php

require('ConnectToDb.php');

// Get the data sent by client
$url = $_POST['url'];
$result = $_POST['result'];

// Choose the required collection.
//'$db' is the variable for the choosen database and is defined in the file 'ConnectToDb.php'
$db_collection = $db->testVectors;

$t = $db_collection->updateOne(['url'=>$url],['$set'=>['result'=>$result]]);

?>