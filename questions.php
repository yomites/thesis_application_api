<?php

/*
 * The following code will get the list of random questions under a subject
 * Questions under a particular subject area are identified by the subject id(sub_id)
 */

$response = array();

require_once __DIR__ . '/include/DB_Connect.php';

$db = new DB_CONNECT();

if (isset($_GET["sub_id"])) {
    $sub_id = $_GET['sub_id'];
	
/* The following select statement joins questions and answers table to select corresponding set of options 
 * for a question based on the id of that question. Only 5 questions are selected for the purpose of testing.
 */

$result = mysql_query("SELECT q.id QuestionId, q.qtext Question, q.anshint AnswerHint, MAX( 
CASE WHEN a.iscorrect =1
THEN a.anstext
END ) Option1, MAX( 
CASE WHEN a.iscorrect =0
AND rn =1
THEN a.anstext
END ) Option2, MAX( 
CASE WHEN a.iscorrect =0
AND rn =2
THEN a.anstext
END ) Option3, MAX( 
CASE WHEN a.iscorrect =0
AND rn =3
THEN a.anstext
END ) Option4,
MAX( 
CASE WHEN a.iscorrect =0
AND rn =4
THEN a.anstext
END ) Option5,
MAX( 
CASE WHEN a.iscorrect =1
THEN a.anstext
END ) Answer
FROM questions q
INNER JOIN (

SELECT a.id, a.anstext, a.iscorrect, a.ansid, @row := 
CASE 
WHEN @prevQ = id
AND iscorrect =0
THEN @row +1
ELSE 0 
END rn, @prevA := ansid, @prevQ := id
FROM answers a
CROSS JOIN (

SELECT @row :=0, @prevA :=0, @prevQ :=0
)r
ORDER BY a.id, a.ansid
)a ON q.id = a.id
WHERE q.sub_id =$sub_id
GROUP BY q.qtext
ORDER BY RAND( ) 
LIMIT 5") 
	or die(mysql_error());
	
  if (mysql_num_rows($result) > 0) {
		
		$response["questions"] = array();

     while ($row = mysql_fetch_array($result)) {

			$question["q_id"] = $row["QuestionId"];
            $question["qtext"] = $row["Question"];
			$question["anshint"] = $row["AnswerHint"];
			$question["option_1"] = $row["Option1"];
			$question["option_2"] = $row["Option2"];
			$question["option_3"] = $row["Option3"];
			$question["option_4"] = $row["Option4"];
			$question["option_5"] = $row["Option5"];
			$question["answer"] = $row["Answer"];
			array_push($response["questions"], $question);
			}
            $response["success"] = 1;

            echo json_encode($response);
        } else {
		
            $response["success"] = 0;
            $response["message"] = "No question found";

            echo json_encode($response);
        }

}else {

    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    echo json_encode($response);

}
?>