<?php

//user details
$username;
$designation;
$name;
$email;
$user_id;

//server details
$servername="localhost";
$serverusername="root";
$password="";
$database_name="research_conclave";
$error="";

//each abstract details

session_start();
	$designation= $_SESSION["designation"];
	//check for empty
	if (empty($_SESSION["username"]) || ($designation!="Reviewer(P)" && $designation!="Reviewer(O)")) {
		header("location: home.php");
		exit;
	}
	$username=$_SESSION["username"];
	
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
		if (isset($_POST['home'])) {
			session_start();
			$_SESSION["username"]=$username;
			header("location:review_home.php");
		}

		//file uplaod
		if(isset($_POST["submit"])){
			$score=$_POST["score"];
			$id=$_POST["id"];
			$num=$_POST["num"];

			if (empty($_POST["score"])) {
				$error="Score is not given";	
			}
			elseif($score <0){
				$error="score cannot be negative number";
			}
			elseif ($score>10) {
				$error="score should lie between 0 and 10";
			}
			else if($num=="rev1") {
				$stmt=$conn->query("UPDATE `submissions` SET `score1` = '$score' WHERE `submissions`.`submission_id` ='$id' ;");
				$error="Successfully Allocated";
			}
			else{
				$stmt=$conn->query("UPDATE `submissions` SET `score2` = '$score' WHERE `submissions`.`submission_id` ='$id' ;");
				$error="Successfully Allocated";
			}

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
		<br><h1>Allocate</h1><br>
	</div><br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		<input class="button1" type="submit" name="home" value="HOME">
	</form>
	<?php echo $error; ?><br><br>
	<?php 
		$stmt= $conn->query("SELECT * FROM `submissions` WHERE `reviewer1`= '$user_id' AND `status`='Accepted';");
		$users=$stmt->fetchAll();
		foreach ($users as $result) {
			if (empty($result["score1"])) {

	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" >

		
		 Title:<?php echo $result["title"]."-------" ?>
		<input type="hidden" name="id" value="<?php echo $result[0]; ?>">
		<input type="hidden" name="num" value="<?php echo "rev1"; ?>">
		Score:<input type="text" name="score">
		<input type="Submit" name="check" value="Check abstract">
		<input type="Submit" name="submit" value="Submit"><br><br>
		<?php 
			if (isset($_POST["check"])) {
		?>
			<object data="data:application/pdf;base64,<?php echo base64_encode($result["file"]) ?>" type="application/pdf" style="height:800px;width:80%"></object>
		<?php
			}
		?>
		
	</form>

	<?php
			}
		}
	 ?>



	 <?php 
		$stmt= $conn->query("SELECT * FROM `submissions` WHERE `reviewer2`= '$user_id' AND `status`='Accepted';");
		$users=$stmt->fetchAll();
		foreach ($users as $result) {
			if (empty($result["score2"])) {

	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" >

		
		 Title:<?php echo $result["title"]."-------" ?>
		<input type="hidden" name="id" value="<?php echo $result[0]; ?>">
		<input type="hidden" name="num" value="<?php echo "rev2"; ?>">
		Score:<input type="text" name="score">
		<input type="Submit" name="check" value="Check abstract">

		<input type="Submit" name="submit" value="Submit"><br><br>
		<?php 
			if (isset($_POST["check"])) {
		?>
			<object data="data:application/pdf;base64,<?php echo base64_encode($result["file"]) ?>" type="application/pdf" style="height:800px;width:80%"></object>
		<?php
			}
		?>
		
	</form>

	<?php
			}
		}
	 ?>


</body>

</html>
