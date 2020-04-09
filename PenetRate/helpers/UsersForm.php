<?php

include_once 'db-config.php';
include_once 'Input.php';
include_once '../helpers/session.php';


if (isset($_POST['formID'])) {
    $formID = Input::str($_POST['formID']);
    $db = new DBController();
    $conn = $db->connect();

    //Create User :: Admin Operation
    if ($formID == 'Create') {
        if (IsAdmin()) {
            if (isset($_POST['fullname']) && isset($_POST['phone']) && isset($_POST['position']) && isset($_POST['role']) && isset($_POST['email']) && isset($_POST['password'])) {
                $form = $_POST;
                try {
                    $fullname = Input::str($form['fullname']);
                    $phone = Input::str($form['phone']);
                    $position = Input::str($form['position']);
                    $role = Input::str($form['role']);
                    $email = Input::str($form['email']);
                    $password = Input::ValidPassword($form['password']);

                    $stmt = $conn->prepare('SELECT * FROM Users WHERE Email = ?');
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $data = "Exists";
                    } else {

                        $stmt = $conn->prepare("INSERT INTO Users (Fullname,Phone,Position,UserRole,Email,UserPassword) values (?,?,?,?,?,?)");
                        $stmt->bind_param('ssssss', $fullname, $phone, $position, $role, $email, $password);
                        if ($stmt->execute()) {
                            $data = "Created";
                        }
                    }
                    echo $data;
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            } else {
                header("Location: ../users/login.html");
            }
        }
    }
    //Register User :: Regular Operation
    else if ($formID == 'Register') {
        if (isset($_POST['fullname']) && isset($_POST['phone']) && isset($_POST['position']) && isset($_POST['email']) && isset($_POST['password'])) {
            $form = $_POST;
            try {
                $fullname = Input::str($form['fullname']);
                $phone = Input::str($form['phone']);
                $position = Input::str($form['position']);
                $email = Input::str($form['email']);
                $password = Input::ValidPassword($form['password']);

                $stmt = $conn->prepare('SELECT * FROM Users WHERE Email = ?');
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $data = "Exists";
                } else {

                    $stmt = $conn->prepare("INSERT INTO Users (Fullname,Phone,Position,Email,UserPassword) values (?,?,?,?,?)");
                    $stmt->bind_param('sssss', $fullname, $phone, $position, $email, $password);
                    if ($stmt->execute()) {
                        $data = "Register";
                    }
                }
                echo $data;
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            header("Location: ../users/login.html");
        }
    }
    //Login User :: Regular Operation
    else if ($formID == 'Login') {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $form = $_POST;
            try {
                $email = Input::str($form['email']);
                $password = Input::ValidPassword($form['password']);

                $stmt = $conn->prepare('SELECT * FROM Users WHERE Email = ? AND UserPassword = ?');
                $stmt->bind_param('ss', $email, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    //Prevent Session Fixation
                    session_regenerate_id();
                    $session_row = $result->fetch_assoc();
                    $_SESSION['UserID'] =  $session_row["UserID"];
                    $data = "Login";
                } else {
                    $data = "NoLogin";
                }
                echo $data;
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            header("Location: ../users/login.html");
        }
    }
    //Edit Your Profile :: Regular Operation after login
    else if ($formID == 'Edit') {
        if (checkLoggedIn()) {
            if (isset($_POST['fullname']) && isset($_POST['phone']) && isset($_POST['position']) && isset($_POST['email']) && isset($_POST['current_password']) && isset($_POST['new_password'])) {
                $form = $_POST;
                try {
                    $fullname = Input::str($form['fullname']);
                    $phone = Input::str($form['phone']);
                    $position = Input::str($form['position']);
                    $email = Input::str($form['email']);


                    if ($form['current_password'] != "NOPASSWORD") {
                        $current_password = Input::ValidPassword($form['current_password']);
                        $new_password = Input::ValidPassword($form['new_password']);
                        $stmt = $conn->prepare('SELECT * FROM Users WHERE UserID = ? AND UserPassword = ?');
                        $stmt->bind_param('ss', $_SESSION['UserID'], $current_password);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            //Password_Correct -> Upate also password
                            $stmt = $conn->prepare("UPDATE users set Fullname = ?,Phone = ?,Position = ?,Email = ?,UserPassword = ? WHERE UserID = ?");
                            $stmt->bind_param('ssssss', $fullname, $phone, $position, $email, $new_password, $_SESSION['UserID']);
                            if ($stmt->execute()) {
                                $data = "EditUpdated";
                            } else {
                                $data = "ErrorUpdated";
                            }
                        } else {
                            $data = "PasswordError";
                        }
                    } else {
                        //Update Without password
                        $stmt = $conn->prepare("UPDATE users set Fullname = ?,Phone = ?,Position = ?,Email = ? WHERE UserID = ?");
                        $stmt->bind_param('sssss', $fullname, $phone, $position, $email, $_SESSION['UserID']);
                        if ($stmt->execute()) {
                            $data = "EditUpdated";
                        } else {
                            $data = "ErrorUpdated";
                        }
                    }
                    echo $data;
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            } else {
                header("Location: ../users/login.html");
            }
        }
    }
    //Edit Other User Profile :: Admin Operation
    else if ($formID == 'UserEdit') {
        if (IsAdmin()) {
            if (isset($_POST['UserChangeID']) && isset($_POST['fullname']) && isset($_POST['phone']) && isset($_POST['position']) && isset($_POST['role']) && isset($_POST['email'])) {
                $form = $_POST;
                try {
                    $UserChangeID = $form['UserChangeID'];
                    $fullname = Input::str($form['fullname']);
                    $phone = Input::str($form['phone']);
                    $position = Input::str($form['position']);
                    $role = Input::str($form['role']);
                    $email = Input::str($form['email']);

                    $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
                    $stmt->bind_param('s', $_POST['UserChangeID']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $session_row = $result->fetch_assoc();
                        $main_email = $session_row["Email"];
                        $can_con = true;
                        if ($main_email != $email) {
                            $stmt = $conn->prepare('SELECT * FROM Users WHERE Email = ?');
                            $stmt->bind_param('s', $email);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                $data = "Exists";
                                $can_con = false;
                                echo $data;
                            }
                        }
                        if ($can_con) {
                            //Update User
                            $stmt = $conn->prepare("UPDATE users set Fullname = ?,Phone = ?,Position = ?,UserRole = ?,Email = ? WHERE UserID = ?");
                            $stmt->bind_param('ssssss', $fullname, $phone, $position, $role, $email, $UserChangeID);
                            if ($stmt->execute()) {
                                $data = "EditUpdated";
                            } else {
                                $data = "ErrorUpdated";
                            }
                            echo $data;
                        }
                    } else {
                        header("Location: ../users/login.html");
                        exit;
                    }
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            } else {
                header("Location: ../users/login.html");
            }
        }
    }
} else {
    header("Location: ../users/login.html");
}
