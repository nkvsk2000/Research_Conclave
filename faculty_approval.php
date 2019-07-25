<?php

//user details
$username;
$designation;
$name;
$email;

//server details
$servername="localhost";
$serverusername="root";
$password="";
$database_name="research_conclave";
$error="Select one. Accept / Reject";

//each abstract details

session_start();
	$designation= $_SESSION["designation"];
	//check for empty
	if (empty($_SESSION["username"]) || $designation!="Faculty_Convener" ) {
		header("location: home.php");
		exit;
	}
	$username=$_SESSION["username"];
	

	if (isset($_POST['home'])) {
		session_start();
		$_SESSION["username"]=$username;
		header("location:facultyc_home.php");
	}
	//database conncetion
	try{
		$conn= new PDO("mysql:host=$servername; dbname=$database_name", $serverusername);
		$conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt= $conn->query("SELECT * FROM `users` WHERE `username`='$username';");
		$users=$stmt->fetchAll();
		foreach ($users as $result) {
			$name=$result[1];
			$email=$result[5];
			$user_id=$result[0];
		}

		//file uplaod
		if(isset($_POST["yes"])){
			$id=$_POST["id"];
			$stmt=$conn->query("UPDATE `submissions` SET `status` = 'Accepted' WHERE `submissions`.`submission_id` ='$id' ;");
			$error="Successfull";
		}
		else if (isset($_POST["no"])){
			$id=$_POST["id"];
			$stmt=$conn->query("UPDATE `submissions` SET `status` = 'NULL' WHERE `submissions`.`submission_id` ='$id' ;");
			$error="Successfull";
		}




	}	
	catch(PDOException $e){
		$error=$e->getMessage() ;
	}




?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome bro</title>
</head>
<style>
  .button1{
    border: 0;
    background-color: none;
    display: block;
    margin-left: 90%;
    text-align: center
    font-size: 30px;
    border: 2px solid #2B7009;
    padding: 6px 8px;
    width: 140px;
    outline: none;
    color: black;
    border-radius: 12px;
    transition: 0.25s;
    cursor: pointer;
  }
 </style>
<body align="center" bgcolor="#ACEF84">
	<div style="background-color:#4CAD15">
		<br><h1>Approve</h1><br>
	</div><br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		<input class="button1" type="submit" name="home" value="HOME">
	</form>
	<?php echo $error; ?><br><br>
	<?php 
		$stmt= $conn->query("SELECT * FROM `submissions` WHERE `status`= 'Allocated';");
		$users=$stmt->fetchAll();
		foreach ($users as $result) {
			

	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" >

		
		Name: <?php echo $result["name"]."------" ?> Event:<?php echo $result["type"]."------" ?> Title:<?php echo $result["title"]."-------" ?> Reviewer1: <?php echo $result["reviewer1"]."------" ?>Reviewer2: <?php echo $result["reviewer2"]."-------" ?>
		<input type="hidden" name="id" value="<?php echo $result[0]; ?>">
		
		<input type="Submit" name="yes" value="Accept"> <input type="Submit" name="no" value="Reject"> <br>		
	</form>

	<?php
		}
	 ?>

</body>

</html>
