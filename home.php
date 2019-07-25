<?php

session_start();
$servername="localhost";
$serverusername="root";
$password="";
$database_name="research_conclave";
$error;
	
try{
	$conn= new PDO("mysql:host=$servername; dbname=$database_name", $serverusername);

	$conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	//trying to login
	//intialising the values
	$username = $password = $usernameErr = $passwordErr =$error = "";
	//validating the form inputs

	if (isset($_POST['signup'])) {
		session_start();
		$_SESSION["username"]=$username;
		header("location:signup.php");
	}


	if (isset($_POST['submit'])) {
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			//check if username is empty
			if (empty(test_input($_POST["username"]))) {
				$usernameErr="Enter Username";
			}
			else{
				$username=test_input($_POST["username"]);
			}

			//check if password is empty
			if (empty(test_input($_POST["password"]))) {
				$passwordErr="Enter Password";
			}
			else{
				$password=test_input($_POST["password"]);
			}

		}
			
		if (empty($usernameErr) && empty($passwordErr)) {


			$stmt= $conn->query("SELECT * FROM `users` WHERE `username`='$username' AND `password`='$password';");
		
			if ($stmt->rowCount()==1) {

				$users=$stmt->fetchAll();
				print_r($users);
				foreach ($users as $result) {
					$designation=$result[4];
					if ($result[4]=="Student_Convener") {
						session_start();
						$_SESSION["username"]=$username;
						$_SESSION["designation"]=$designation;
						header("location:studentc_home.php");
					}
					else if($result[4]=="Faculty_Convener"){
						session_start();
						$_SESSION["username"]=$username;
						$_SESSION["designation"]=$designation;
						header("location:facultyc_home.php");
					}
					else if($result[4]=="Reviewer(O)" || $result[4]=="Reviewer(P)"){
						session_start();
						$_SESSION["username"]=$username;
						$_SESSION["designation"]=$designation;
						header("location:review_home.php");
					}
					else{
						session_start();
						$_SESSION["username"]=$username;
						$_SESSION["designation"]=$designation;
						header("location:participant_home.php");
					}
				}
			}
			else{
				$error="username or password is incorrect";
			}
			
		}
	}	



}
catch(PDOException $e){
	echo "Error: ".$e->getMessage() ;
}
	

function test_input($data){
	$date= trim($data);
	$data=stripcslashes($data);
	$data= htmlentities($data);
	return $data;
	}



?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>Research_Conclave_Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	
</head>
<style>
::placeholder {
  color: #3A3838;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
 color: #3A3838;
}

::-ms-input-placeholder { /* Microsoft Edge */
 color: #3A3838;
}
</style>
<body align = center background="iitguwahati.jpg">
	<h1>RESEARCH CONCLAVE IITG</h1>
	<div>
		<form class ="box" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" >
		<h2>Login Form</h2>
		
		<input type ="text" name="username" placeholder="Username">
		<br><span><?php echo $usernameErr; ?></span><br>
		
		<input type="password" name="password" placeholder="Password">
		<br><span><?php echo $passwordErr; ?></span><br><br>

		<input  type="submit" name="submit" value="Login">
		<br><span><?php echo $error; ?></span><br>
		<h3>If you are not a mem</h3>
		<input type="submit" name="signup" value="Signup"></input>	

	</form>
	</div>
	
	
	
 
<?php $conn=null; ?>
</body>
</html>