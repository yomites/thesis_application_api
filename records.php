<?php

/*
 * The following code will get the list of user saved records from user_exams_record table.
 * Records are identified for the user by his or her email address.
 */

$response = array();

require_once __DIR__ . '/include/DB_Connect.php';

$db = new DB_CONNECT();

if (isset($_GET["email"])) {
    $email = $_GET['email'];

    // Getting a list of user exams record from user_exams_record table using user email
    $result = mysql_query("SELECT * FROM user_exams_records WHERE email = '$email'") 
	or die(mysql_error());

  if (mysql_num_rows($result) > 0) {
		
		$response["records"] = array();

     while ($row = mysql_fetch_array($result)) {

            $record= array();
            $record["record_id"] = $row["record_id"];
            $record["subject_name"] = $row["subject_name"];
			$record["exam_result_page"] = $row["exam_result_page"];
			$record["score"] = $row["score"];
            $record["percentage"] = $row["percentage"];
			$record["time_spent"] = $row["time_spent"];
			$record["date_taken"] = $row["date_taken"];

			array_push($response["records"], $record);
			}

            $response["success"] = 1;

            echo json_encode($response);
        } else {
 
            $response["success"] = 0;
            $response["message"] = "No record found";

            echo json_encode($response);
        }

}else {

    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);

}
?>