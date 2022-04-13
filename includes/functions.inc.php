<?php
//signup Page
function emptyInputSignup($name,$email,$username,$pwd,$pwdRepeat){
	$result;
	if(empty($name)||empty($email)||empty($username)||empty($pwd)||empty($pwdRepeat)){
		$result = true;
	}

	else{
		$result = false;
	}
	return $result;
}

function invalidUid($username){
	$result;
	if(!preg_match("/^[a-zA-Z0-9]*$/",$username)){
		$result = true;
	}

	else{
		$result = false;
	}
	return $result;
}

function invalidEmail($email){
	$result;
	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$result = true;
	}

	else{
		$result = false;
	}
	return $result;
}


function pwdMatch($pwd,$pwdRepeat){
	$result;
	if($pwd !== $pwdRepeat){
		$result = true;
	}

	else{
		$result = false;
	}
	return $result;
}

function uidExists($conn,$username,$email){
	$sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql)){
		header("location:../signup.php?error=stmtfailed1");
		exit();

	}
	mysqli_stmt_bind_param($stmt, "ss",$username, $email);
	mysqli_stmt_execute($stmt);

	$resultData=mysqli_stmt_get_result($stmt);


	if($row = mysqli_fetch_assoc($resultData)){
		return $row;

	}else{
		$result = false;
		return $result;
	}

	mysqli_stmt_close($stmt);

}


function createUser($conn,$name,$email,$username,$pwd){
	$hashedPwd = password_hash($pwd,PASSWORD_DEFAULT);



	//'$name','$email','$username','$pwd'
	$sql = "INSERT INTO users (usersName,usersEmail,usersUid,usersPwd) VALUES ('$name','$email','$username','$hashedPwd');";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql)){
		header("location:../signup.php?error=stmtfailed2");
		exit();

	}

	//$hashedPwd = password_hash($pwd,PASSWORD_DEFAULT);


	mysqli_stmt_bind_param($stmt, "ssss",$name,$email,$userName,$hashedPwd);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	
//This is the place where you put link to the site if the signup is done
	header("location:../login.php?status=success");
	exit();


}

//login
function emptyInputLogin($username,$pwd){
	$result;
	if(empty($username)||empty($pwd)){
		$result = true;
	}

	else{
		$result = false;
	}
	return $result;
}
function loginUser($conn, $username, $pwd){
	session_start();
	if($username == 'admin' && $pwd == '123'){
		$_SESSION["userid"] = '-1';
		$_SESSION["useruid"] = 'admin';
		header("location:../booked_flights.php");
		return true;
	}
	
	$uidExists = uidExists($conn, $username, $username);

	if($uidExists === false){
		header("location:../login.php?error=wronglogin");
		exit();
	}
	$pwdHashed = $uidExists["usersPwd"];
	$checkPwd = password_verify($pwd, $pwdHashed);

	if($checkPwd === false){
		header("location:../login.php?error=passwordverificationfailed");
		exit();
	}
	else if ($checkPwd === true){
		
		$_SESSION["userid"] = $uidExists["usersId"];
		$_SESSION["useruid"] = $uidExists["usersUid"];
		//if user enters correct login data 
		header("location:../index.php");
		exit();
	}
}