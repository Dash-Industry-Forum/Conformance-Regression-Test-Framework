<?php
// Logs exceptions to file $logFile
function logException($logMessage){
    error_log(date("[d.m.Y l H:i:s]\n").$logMessage."\n\n", 3, "logs/error.log");
}
?>