<?php

/*
 * The following code will lists all the subjects in the database
 */

$response = array();

require_once __DIR__ . '/include/DB_Connect.php';

// Connecting to db
$db = new DB_CONNECT();

// Getting all subject from subjects table
$result = mysql_query("SELECT *FROM subjects") or die(mysql_error());

if (mysql_num_rows($result) > 0) {

    $response["subjects"] = array();
    
    while ($row = mysql_fetch_array($result)) {
	
        $subject = array();
        $subject["sub_id"] = $row["sub_id"];
        $subject["subject_name"] = $row["subject_name"];
 
        array_push($response["subjects"], $subject);
    }
 
    $response["success"] = 1;

    // Echoing JSON response
    echo json_encode($response);
} else {

    $response["success"] = 0;
    $response["message"] = "No subject found";

    echo json_encode($response);
}
?>