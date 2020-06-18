<?php
// Logs exceptions to file $logFile
function logException($logMessage){
    // Use output buffer to save contents of debug function to a variable which will then be saved to a file
    ob_start();
    debug_print_backtrace();
    $backtrace = ob_get_clean();

    // Log error to file
    error_log(date("[d.m.Y l H:i:s]\n").$logMessage."\n".$backtrace."\n\n", 3, "logs/error.log");

    // Display error as prompt on webpage after replacing characters which cause problems when outputting
    $logMessage_modified = str_replace(array('\\','\'','\"','`','  '),' ',$logMessage);
    echo "<script>
    alert('$logMessage_modified');
    </script>";
}
?>