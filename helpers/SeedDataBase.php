<?php


include_once 'db-config.php';
include_once 'Input.php';
include_once '../helpers/session.php';


$db = new DBController();
$conn = $db->connect();
$fullname = array('Etai Yaffe', 'Noam Yaffe', 'Itay G', 'Sgaked T', 'Kamea t', 'Ido V');
$phone = array('0524704053', '0524704053', '0524704053', '0524704053', '0524704053', '0524704053');
$position = array('Company Employee', 'Other', 'Freelancer', 'Freelancer', 'Other', 'Company Employee');
$role = array('Admin', 'Admin', 'Admin', 'Admin', 'User', 'User');
$email = array('etai11@gmai.com', 'etaiyaffe379@gmai.com', 'etai1@gmai.com', 'etai2@gmai.com', 'etaiyaffe@gmai.com', 'etaiyaffe24234@gmai.com');
$password = array('Aa123456', 'Aa123456', 'Aa123456', 'Aa123456', 'Aa123456', 'Aa123456');


for ($i = 0; $i < count($fullname); $i++) {
    $stmt = $conn->prepare("INSERT INTO Users (Fullname,Phone,Position,UserRole,Email,UserPassword) values (?,?,?,?,?,?)");
    $stmt->bind_param('ssssss', $fullname[$i], $phone[$i], $position[$i], $role[$i], $email[$i], $password[$i]);
    if ($stmt->execute()) {
        $data = "Created";
    }
}
