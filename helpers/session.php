<?php

include_once 'db-config.php';
session_start();
function checkLoggedIn()
{
	$db = new DBController();
	$conn = $db->connect();

	if (!isset($_SESSION['UserID'])) {
		header("Location: ../users/login.html");
		exit;
		return false;
	} else {
		$user_check = $_SESSION['UserID'];
		$user_check = mysqli_real_escape_string($conn, $user_check);
		$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
		$stmt->bind_param('s', $user_check);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			$session_row = $result->fetch_assoc();
			$_SESSION['Fullname'] = $session_row["Fullname"];
			$_SESSION['UserRole'] = $session_row["UserRole"];
			$_SESSION['session_row'] = $session_row;

			return true;
		} else {
			header("Location: ../users/login.html");
			exit;
			return false;
		}
	}
}


function IsAdmin()
{
	if (!isset($_SESSION['UserRole'])) {
		header("Location: ../users/login.html");
		exit;
		return false;
	} else {
		if ($_SESSION['UserRole'] == 'Admin') {
			return true;
		} else {
			header("Location: ../users/login.html");
			exit;
			return false;
		}
	}
}



function checkLoggedInMenu()
{
	$db = new DBController();
	$conn = $db->connect();

	if (!isset($_SESSION['UserID'])) {
		header("Location: users/login.html");
		exit;
		return false;
	} else {
		$user_check = $_SESSION['UserID'];
		$user_check = mysqli_real_escape_string($conn, $user_check);
		$stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
		$stmt->bind_param('s', $user_check);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			$session_row = $result->fetch_assoc();
			$_SESSION['Fullname'] = $session_row["Fullname"];
			$_SESSION['UserRole'] = $session_row["UserRole"];
			$_SESSION['session_row'] = $session_row;

			return true;
		} else {
			header("Location: users/login.html");
			exit;
			return false;
		}
	}
}

function IsAdminMenu()
{
	if (!isset($_SESSION['UserRole'])) {
		header("Location: users/login.html");
		exit;
		return false;
	} else {
		if ($_SESSION['UserRole'] == 'Admin') {
			return true;
		} else {
			header("Location: users/login.html");
			exit;
			return false;
		}
	}
}
