<?php

	class DB_Functions {

		private $db;

		function __construct() {
			require_once 'DB_Connect.php';
			$this->db = new DB_Connect();
		}

		function __destruct() {
			
		}

		public function storeUser($firstname, $surname, $email, $password) {
			$uuid = uniqid('', true);
			$hash = $this->hashSSHA($password);
			$encrypted_password = $hash["encrypted"];
			$salt = $hash["salt"];
			$result = mysql_query("INSERT INTO users(unique_id, firstname, surname, email, encrypted_password, salt, created_at) VALUES('$uuid', '$firstname', '$surname', '$email', '$encrypted_password', '$salt', NOW())");

			if ($result) {

				$uid = mysql_insert_id();
				$result = mysql_query("SELECT * FROM users WHERE uid = $uid");
				return mysql_fetch_array($result);
			} else {
				return false;
			}
		}

		public function storeUserExamsRecord($email, $subject_name, $exam_result_page, $score, $percentage, $time_spent) {

			$result = mysql_query("INSERT INTO user_exams_records(email, subject_name, exam_result_page, score, percentage, time_spent, date_taken) VALUES('$email', '$subject_name', '$exam_result_page', '$score', '$percentage', '$time_spent', NOW())");
			
			if ($result) {
 
				$record_id = mysql_insert_id();
				$result = mysql_query("SELECT * FROM user_exams_records WHERE record_id = $record_id");
				return mysql_fetch_array($result);
			} else {
				return false;
			}
		}

		public function storeComplaints($record_id, $questionNo, $feedtext, $subject_name, $user_email) {

			$result = mysql_query("INSERT INTO questions_complaints(record_id, questionNo, feedtext, subject_name, user_email, date) VALUES('$record_id', '$questionNo', '$feedtext', '$subject_name', '$user_email', NOW())");

			if ($result) {
 
				$com_id = mysql_insert_id();
				$result = mysql_query("SELECT * FROM questions_complaints WHERE com_id = $com_id");
				return mysql_fetch_array($result);
			} else {
				return false;
			}
		}

		public function storeFeedback($user_email, $feedback_content) {

			$result = mysql_query("INSERT INTO feedbacks(user_email, feedback_content, feedback_date) VALUES('$user_email', '$feedback_content', NOW())");
			if ($result) {
 
				$feedback_id = mysql_insert_id();
				$result = mysql_query("SELECT * FROM feedbacks WHERE feedback_id = $feedback_id");
				return mysql_fetch_array($result);
			} else {
				return false;
			}
		}

		public function addNewCategory($name) {

			$result = mysql_query("INSERT INTO categories(name, time_created) VALUES('$name', NOW())");

			if ($result) {
 
				$cat_id = mysql_insert_id();
				$result = mysql_query("SELECT * FROM categories WHERE cat_id = $cat_id");

				return mysql_fetch_array($result);
			} else {
				return false;
			}
		}

		public function addNewQuestion($sub_id, $qtext, $anshint, $answer, $option1, $option2, $option3, $option4) {

			$result = mysql_query("INSERT INTO questions(sub_id, qtext, anshint) VALUES('$sub_id', '$qtext', '$anshint')");
			if ($result) {
				$id = mysql_insert_id();

				$result1 = mysql_query("INSERT INTO answers(id, iscorrect, anstext) VALUES('$id', 1, '$answer')");
				$result2 = mysql_query("INSERT INTO answers(id, iscorrect, anstext) VALUES('$id', 0, '$option1')");
				$result3 = mysql_query("INSERT INTO answers(id, iscorrect, anstext) VALUES('$id', 0, '$option2')");
				$result4 = mysql_query("INSERT INTO answers(id, iscorrect, anstext) VALUES('$id', 0, '$option3')");
				$result5 = mysql_query("INSERT INTO answers(id, iscorrect, anstext) VALUES('$id', 0, '$option4')");
				
				$result = mysql_query("SELECT * FROM answers WHERE id = $id");

				return mysql_fetch_array($result);
			} else {
				return false;
			}
		}



		public function getUserByEmailAndPassword($email, $password) {
			$result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());

			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0) {
				$result = mysql_fetch_array($result);
				$salt = $result['salt'];
				$encrypted_password = $result['encrypted_password'];
				$hash = $this->checkhashSSHA($salt, $password);

				if ($encrypted_password == $hash) {
					return $result;
				}
			} else {
				return false;
			}
		}

		public function isUserExisted($email) {
			$result = mysql_query("SELECT email from users WHERE email = '$email'");
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		public function hashSSHA($password) {

			$salt = sha1(rand());
			$salt = substr($salt, 0, 10);
			$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
			$hash = array("salt" => $salt, "encrypted" => $encrypted);
			return $hash;
		}

		public function checkhashSSHA($salt, $password) {

			$hash = base64_encode(sha1($password . $salt, true) . $salt);

			return $hash;
		}
		
		
		public function validateEmail($email) {
			$isValid = true;
			$atIndex = strrpos($email, "@");
			if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		}
		else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
		  if ($localLen < 1 || $localLen > 64)
			{
				$isValid = false;
			}
		  else if ($domainLen < 1 || $domainLen > 255) {
			 $isValid = false;
			}
		  else if ($local[0] == '.' || $local[$localLen-1] == '.') {
			 $isValid = false;
			}
		  else if (preg_match('/\\.\\./', $local)) {
			 $isValid = false;
			}
		  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			 $isValid = false;
			}
		  else if (preg_match('/\\.\\./', $domain)) {
			 $isValid = false;
			}
		  else if
			(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
					 str_replace("\\\\","",$local))) {
			 if (!preg_match('/^"(\\\\"|[^"])+"$/',
				 str_replace("\\\\","",$local))) {
				$isValid = false;
				}
			}
		  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
		  {
			 $isValid = false;
		  }
		}
		return $isValid;
	}		
}

	
?>