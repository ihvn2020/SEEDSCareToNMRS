<?php
/* You can uncomment this section setup the connection parameters here and 
    $servername = 'localhost';
    $username = 'openmrs';
    $password = 'ck9RdGyz&jXR';
    $db = 'openmrs';
    $port_no = 3316;
*/
function connectDB($servername,$username,$password,$db,$port_no){
    
    $conn = mysqli_connect($servername,$username,$password,$db,$port_no);

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    return $conn;
}

