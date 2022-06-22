<?php
class DB_Connect {

    // Constructor
    function __construct() {
        $this->connect();
    }

    // Destructor
    function __destruct() {
         $this->close();
    }

    // Connecting to database
    public function connect() {
        require_once 'include/config.php';
        $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        mysql_select_db(DB_DATABASE);

        return $con;
    }

    // Closing connection to the database
    public function close() {
        mysql_close();
    }

}

?>
