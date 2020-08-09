<?php

include_once 'db-config.php';
include_once 'Input.php';
include_once '../helpers/session.php';


if (isset($_POST['formID'])) {
    $formID = Input::str($_POST['formID']);
    $db = new DBController();
    $conn = $db->connect();

    //Contact Us :: Regular Operation
    if ($formID == 'ContactUS') {
        if (isset($_POST['email']) && isset($_POST['message'])) {
            $form = $_POST;
            try {
                $email = Input::str($form['email']);
                $message = Input::str($form['message']);
                $stmt = $conn->prepare("INSERT INTO contactus (email,message) values (?,?)");
                $stmt->bind_param('ss', $email, $message);
                if ($stmt->execute()) {
                    $data = "Created";
                } else {
                    $data = "Error";
                }

                echo $data;
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            header("Location: ../users/login.html");
        }
    }
} else {
    header("Location: ../users/login.html");
}
