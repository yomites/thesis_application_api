<?php

if (isset($_POST['tag']) && $_POST['tag'] != '') {

    $tag = $_POST['tag'];

    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    //Response Array
    $response = array("tag" => $tag, "success" => 0, "error" => 0);

    if ($tag == 'login') {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) {

            $response["success"] = 1;
            $response["uid"] = $user["unique_id"];
            $response["user"]["firstname"] = $user["firstname"];
			$response["user"]["surname"] = $user["surname"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            echo json_encode($response);
        } else {
               $response["error"] = 1;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
    } else if ($tag == 'register') {
        $firstname = $_POST['firstname'];
	 $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($db->isUserExisted($email)) {

            $response["error"] = 2;
            $response["error_msg"] = "User with this email address already exists";
            echo json_encode($response);
        } else if(!$db->validateEmail($email)) {
			$response["error"] = 3;
            $response["error_msg"] = "Invalid email format";
            echo json_encode($response);
		} else {

            $user = $db->storeUser($firstname, $surname, $email, $password);
            if ($user) {

                $response["success"] = 1;
                $response["uid"] = $user["unique_id"];
                $response["user"]["firstname"] = $user["firstname"];
				$response["user"]["surname"] = $user["surname"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
                echo json_encode($response);
            } else {

                $response["error"] = 1;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    } else if($tag == 'addquestion') {
				
				
				$sub_id= $_POST['sub_id'];
				$qtext= $_POST['qtext'];
				$anshint= $_POST['anshint'];
				$answer= $_POST['answer'];
				$option1= $_POST['option1'];
				$option2= $_POST['option2'];
				$option3= $_POST['option3'];
				$option4= $_POST['option4'];

				$addnewquestion = $db->addNewQuestion($sub_id, $qtext, $anshint, $answer, $option1, $option2, $option3, $option4);
				if ($addnewquestion) {
							$response["success"] = 1;
                			$response["addnewquestion"]["ansid"] = $addnewquestion["ansid"];
							$response["addnewquestion"]["id"] = $addnewquestion["id"];
                			$response["addnewquestion"]["iscorrect"] = $addnewquestion["iscorrect"];
							$response["addnewquestion"]["anstext"] = $addnewquestion["anstext"];

                			echo json_encode($response);
				} else {
							$response["error"] = 1;
                			$response["error_msg"] = "Error occured in saving category";
							echo json_encode($response);
				}
    } else if($tag == 'category') {
				
				
				$name= $_POST['name'];
				$addcategory = $db->addNewCategory($name);
				if ($addcategory) {
							$response["success"] = 1;
                			$response["addcategory"]["cat_id"] = $addcategory["cat_id"];
							$response["addcategory"]["name"] = $addcategory["name"];
                			$response["addcategory"]["time_created"] = $addcategory["time_created"];

                			echo json_encode($response);
				} else {
							$response["error"] = 1;
                			$response["error_msg"] = "Error occured in saving category";
							echo json_encode($response);
				}
	} else if($tag == 'summary') {
				
				$email = $_POST['email'];
				$subject_name = $_POST['subject_name'];
				$exam_result_page = $_POST['exam_result_page'];
				$score = $_POST['score'];
				$percentage = $_POST['percentage'];
				$time_spent = $_POST['time_spent'];
				$userrecord = $db->storeUserExamsRecord($email, $subject_name, $exam_result_page, $score, $percentage, $time_spent);
				if ($userrecord) {
							$response["success"] = 1;
                			$response["userrecord"]["record_id"] = $userrecord["record_id"];
                			$response["userrecord"]["email"] = $userrecord["email"];
							$response["userrecord"]["subject_name"] = $userrecord["subject_name"];
                			$response["userrecord"]["exam_result_page"] = $userrecord["exam_result_page"];
                			$response["userrecord"]["score"] = $userrecord["score"];
							$response["userrecord"]["percentage"] = $userrecord["percentage"];
                			$response["userrecord"]["time_spent"] = $userrecord["time_spent"];
                			$response["userrecord"]["date_taken"] = $userrecord["date_taken"];

                			echo json_encode($response);
				} else {
							$response["error"] = 1;
                			$response["error_msg"] = "Error occured in saving user record";
							echo json_encode($response);
				}

	} else if($tag == 'complaints') {
				
				$RecordID = $_POST['RecordID'];
				$QuestionNO= $_POST['QuestionNO'];
				$FeedText= $_POST['FeedText'];
				$SubjectName= $_POST['SubjectName'];
				$Email= $_POST['Email'];
				$complaints = $db->storeComplaints($RecordID , $QuestionNO, $FeedText, $SubjectName, $Email);
				if ($complaints) {
							$response["success"] = 1;
                			$response["complaints"]["com_id"] = $complaints["com_id"];
                			$response["complaints"]["record_id"] = $complaints["record_id"];
							$response["complaints"]["questionNo"] = $complaints["questionNo"];
                			$response["complaints"]["feedtext"] = $complaints["feedtext"];
                			$response["complaints"]["subject_name"] = $complaints["subject_name"];
							$response["complaints"]["user_email"] = $complaints["user_email"];
                			$response["complaints"]["date"] = $complaints["date"];

                			echo json_encode($response);
				} else {
							$response["error"] = 1;
                			$response["error_msg"] = "Error occured in saving user complaint";
							echo json_encode($response);
				}
	} else if($tag == 'feedback') {
				
				
				$Email= $_POST['Email'];
				$FeedbackText= $_POST['FeedbackText'];
				$Email= $_POST['Email'];
				$suggestion = $db->storeFeedback($Email, $FeedbackText);
				if ($suggestion) {
							$response["success"] = 1;
                			$response["suggestion"]["feedback_id"] = $suggestion["feedback_id"];
							$response["suggestion"]["user_email"] = $suggestion["user_email"];
							$response["suggestion"]["feedback_content"] = $suggestion["feedback_content"];
                			$response["suggestion"]["feedback_date"] = $suggestion["feedback_date"];

                			echo json_encode($response);
				} else {
							$response["error"] = 1;
                			$response["error_msg"] = "Error occured in saving user feedback";
							echo json_encode($response);
				}
	} else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}
?>
