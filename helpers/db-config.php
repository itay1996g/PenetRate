<?php
class DBController
{

    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "penetrate";

    // ----------- [ Creating connection ] ---------------

    public function connect()
    {
        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->db) or die("Database connection error." . $conn->connect_error);
        mysqli_set_charset($conn, "utf8");
        return $conn;
    }

    // ---------- [ Closing connection ] -----------------

    public function close($conn)
    {
        $conn->close();
    }


    // Encode HTML in order to prevent XSS//
    public function noHTML($input, $encoding = 'UTF-8')
    {
        return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
    }

}
