<?php
session_start();

require_once('ConnectToDb.php');
require_once('logException.php');

$url = $_POST['url'];

try{
    // Find the test vector from the database having the provdied URL
    $db_collection = $db->selectCollection($_SESSION['test_vectors']);
    $test_vector = $db_collection->findOne(['url'=>$url]);

    // Save values for each profile flag
    $dvb = $test_vector['dvb'];
    $hbbtv = $test_vector['hbbtv'];
    $cmaf = $test_vector['cmaf'];
    $dashIf = $test_vector['dashIf'];
    $ctaWave = $test_vector['ctaWave'];
}
catch(MongoDB\Driver\Exception\Exception $catchedException){
    logException(get_class($catchedException)." : ".$catchedException->getMessage());
}

// If the value is something other than 1 or 0, save it as 0
if($dvb != '1' && $dvb != '0'){
    $dvb = '0';
}
if($hbbtv != '1' && $hbbtv != '0'){
    $hbbtv = '0';
}
if($cmaf != '1' && $cmaf != '0'){
    $cmaf = '0';
}
if($dashIf != '1' && $dashIf != '0'){
    $dashIf = '0';
}
if($ctaWave != '1' && $ctaWave != '0'){
    $ctaWave = '0';
}

// Save the flags as an array and send response back to client
$flagsArray = array("dvb"=> $dvb, "hbbtv"=> $hbbtv, "cmaf"=> $cmaf,"dashIf"=> $dashIf, "ctaWave"=>$ctaWave);
echo json_encode($flagsArray);
?>