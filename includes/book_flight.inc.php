<?php
session_start();
if (isset($_POST["submit"]) && !empty($_SESSION['userid'])) {
	
	$airline = isEmpty($_POST['airline'], '');
	$first_name = isEmpty($_POST['first_name'], '');
	$last_name = isEmpty($_POST['last_name'], '');
	$gender = isEmpty($_POST['gender'], '');
	$depart = isEmpty($_POST['depart'], '');
	$arrival = isEmpty($_POST['arrival'], '');
	$trip_start = isEmpty($_POST['trip-start'], '');
	$dir_lay = isEmpty($_POST['dir_lay'], '');
	$description = isEmpty($_POST['description'], '');
	$userid = $_SESSION['userid'];
	require_once 'dbh.inc.php';
	
	$sql = "INSERT INTO `booked_flight`(`user_id`, `airline_id`, `first_name`, `last_name`, `gender`, `depart`, `arrival`, `trip-start`, `dir_lay`, `description`) VALUES (?,?,?,?,?,?,?,?,?,?)";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql)){
		header("location:../book.php`?error=stmtfailed1");
		exit();
	}
	
	mysqli_stmt_bind_param($stmt, "ssssssssss", $userid, $airline, $first_name, $last_name, $gender, $depart, $arrival, $trip_start, $dir_lay, $description);
	$result = mysqli_stmt_execute($stmt);
	
	var_dump($result);
	
	if($result){
		$_SESSION['status'] = 'Successfully Booked the Flight';
		header("location:../index.php");
		exit();
	}else{
		$_SESSION['status'] = 'Unable to book the flight';
		exit();
	}
}
else{
	header("location:../login.php");
	exit();
}

function isEmpty($string, $value = ''){
	return !empty($string) ? $string : $value;
}