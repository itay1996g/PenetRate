<?php

include_once 'db-config.php';
include_once 'Input.php';
include_once '../helpers/session.php';


if (isset($_POST['findingID'])) {
    $findingID = Input::str($_POST['findingID']);
    $db = new DBController();
    $conn = $db->connect();
    $description = 'Error';
    $payload = 'Error';

    try {
        $stmt = $conn->prepare("SELECT description FROM findings_bank WHERE findingID = ?");
        $stmt->bind_param('s', $findingID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $description =  $row["description"];
        } else {
            $description = "Error";
        }

        $stmt = $conn->prepare("SELECT payload FROM payloadgenerator WHERE findingID = ?");
        $stmt->bind_param('s', $findingID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $payload =  $row["payload"];
        } else {
            $payload = "Error";
        }

        if ($description != 'Error' && $payload != 'Error') {

            echo  $payload . 'SPLITHERE' . $description;
        } else {
            echo 'Error';
        }



        echo $description;
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
} else {
    header("Location: ../users/login.html");
}
