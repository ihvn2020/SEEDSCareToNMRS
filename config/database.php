<?php

function connectDB($servername,$username,$password,$db){
    /* You can uncomment this section setup the connection parameters here and 
    $servername = 'localhost';
    $username = 'openmrs';
    $password = 'ck9RdGyz&jXR';
    $db = 'openmrs';
    $port_no = 3316;
    $servername = 'p:'.$servername.':'.$port_no;
*/
    $conn = mysqli_connect($servername,$username,$password,$db);

    if (mysqli_connect_errno()) {

        echo "<h4 class='alert alert-danger' style='text-align: center;'>Failed to connect to MySQL: " . mysqli_connect_error()."</h4>";
        
    }

    return $conn;
}

