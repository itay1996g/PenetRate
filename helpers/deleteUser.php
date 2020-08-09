<?php

include '../helpers/session.php';
checkLoggedIn();
IsAdmin();

if (isset($_POST["id"])) {
    $db = new DBController();
    $conn = $db->connect();

    $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->bind_param('i', $_POST["id"]);
    if ($stmt->execute()) {
        echo 'Deleted';
    } else {
        header("Location: ../users/login.html");
    }
} else {
    header("Location: ../users/login.html");
}
