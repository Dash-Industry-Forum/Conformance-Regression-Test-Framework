<?php
require_once('ConnectToDb.php');
require_once('logException.php');

// Get the data sent by client
$result = $_POST['result'];
$id = $_POST['id'];

// Choose the required collection.
//'$db' is the variable for the choosen database and is defined in the file 'ConnectToDb.php'
$db_collection = $db->selectCollection($_SESSION['test_vectors']);

// Save validation result to database
try{
    $command = $db_collection->updateOne(['_id'=>$id],['$set'=>['result'=>$result]]);
}
catch (MongoDB\Driver\Exception\Exception $catchedException){
    $catchedException_modified = str_replace(array('\\','\'','\"','`','  '),' ',$catchedException);
    // echo "<script>
    // alert('$logMessage_modified');
    // </script>";
    ob_start();
    logException(get_class($catchedException)." : ".$catchedException->getMessage());
    ob_end_clean();

    echo "MongoDB\Driver\Exception\BulkWriteException : not authorized on test_TestAssets to execute command { update: \"test_testVectors\", ordered: true, \$db: \"test_TestAssets\", lsid: { id: UUID(\"54ca619a-4f49-450e-bca0-2ddc19801435\") } }";    
}
?>