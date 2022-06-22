<?php

/*
 * The following code will get the list of subjects under a category
 * Subjects under a particular category are identified by the category id(cat_id)
 */

// Array for JSON response
$response = array();

require_once __DIR__ . '/include/DB_Connect.php';

$db = new DB_CONNECT();

// Checking for post data
if (isset($_GET["cat_id"])) {
    $cat_id = $_GET['cat_id'];

    // Getting list of subjects from subjects table using cat_id
    $result = mysql_query("SELECT * FROM subjects WHERE cat_id = $cat_id") 
	or die(mysql_error());

  if (mysql_num_rows($result) > 0) {
		
		$response["subjects"] = array();

     while ($row = mysql_fetch_array($result)) {

            $subject = array();
            $subject["sub_id"] = $row["sub_id"];
            $subject["subject_name"] = $row["subject_name"];
	     $subject["timeforexam"] = $row["timeforexam"];
			array_push($response["subjects"], $subject);
			}
			
            $response["success"] = 1;

            echo json_encode($response);
        } else {
 
            $response["success"] = 0;
            $response["message"] = "No subject found";

            echo json_encode($response);
        }

}else {

    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);

}
?>