<?php

/*
 * The following code will lists all the categories in the database
 */

// Array for JSON response
$response = array();


// Include DB_Connect class
require_once __DIR__ . '/include/DB_Connect.php';

// Connecting to db
$db = new DB_CONNECT();

// Getting all category from categories table
$result = mysql_query("SELECT *FROM categories") or die(mysql_error());

if (mysql_num_rows($result) > 0) {

    $response["categories"] = array();
    
    while ($row = mysql_fetch_array($result)) {
	
        $category = array();
        $category["cat_id"] = $row["cat_id"];
        $category["name"] = $row["name"];
 
        array_push($response["categories"], $category);
    }
 
    $response["success"] = 1;

    // Echoing JSON response
    echo json_encode($response);
} else {

    $response["success"] = 0;
    $response["message"] = "No categories found";

    echo json_encode($response);
}
?>