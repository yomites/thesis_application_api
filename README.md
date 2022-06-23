# thesis_application_api
Thesis server side implementation.

config.php file contains the database configuration variables. 
It is gitignored for this reason. The content of the file is as follows:

<?php

/**
 * Database configuration variables
 */
define("DB_HOST", "host address");
define("DB_USER", "username");
define("DB_PASSWORD", "password");
define("DB_DATABASE", "database name");
?>

DB_Connect.php takes care of opening and closing connection to the database.

DB_Functions.php contains all the php functions needed for database 
management in this project.

all_subjects.php lists all the subjects from the subjects table.

get_all_categories.php was used to retrieve all the subject categories 
found on the categories table.

get_subjects.php retrieves the list of subjects under a category

questions.php gets the list of randomly selected questions from a subject.

records.php takes care of saving and listing the user exam records.

index.php file was used to handle all the POST requests and determining 
what needed to be done with these requests.
