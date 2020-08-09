<?php

include '../helpers/session.php';
checkLoggedIn();

if (isset($_POST["id"])) {
    $db = new DBController();
    $conn = $db->connect();
    $stmt = $conn->prepare("DELETE FROM scans WHERE ScanID = ? AND UserID = ?");
    $stmt->bind_param('ii', $_POST["id"], $_SESSION['UserID']);
    if ($stmt->execute()) {
        echo 'Deleted';
    } else {
        header("Location: ../users/login.html");
    }
} else {
    header("Location: ../users/login.html");
}
