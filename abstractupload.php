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
$error="Please note that only .pdf files are accepted";

//abstract details
$title;
$type;
$pdf1;
$pdf2;
session_start();
	$designation= $_SESSION["designation"];
	//check for empty
	if (empty($_SESSION["username"]) || $designation!="Participant" ) {
		header("location: home.php");
		exit;
	}
	$username=$_SESSION["username"];
	
	if (isset($_POST['home'])) {
	  	session_start();
	  	$_SESSION["username"]=$username;
	  	header("location:participant_home.php");
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
		if(isset($_POST["submit"])){
			$title=$_POST["title"];
			$type=$_POST["type"];
			$file=$_FILES["file"]["tmp_name"];
			
			//checking database
			$stmt= $conn->query("SELECT * FROM `submissions` WHERE `user_id`='$user_id'  AND `type`='$type';");
			
			if ($stmt->rowCount()!=0) {
				$error="Already uploaded";
			}
			else if (empty($title)) {
				$error="Title cannot be empty";
			}
			else if ($file==""){
				$error="A file must be selected";
			}

			else{
					$file=addslashes(file_get_contents($_FILES["file"]["tmp_name"]));
					$stmt= $conn->query("INSERT INTO `submissions` (`submission_id`, `user_id`, `name`, `email`, `title`, `type`, `file`, `reviewer1`, `reviewer2`, `score1`, `score2`, `status`) VALUES (NULL, '$user_id', '$name', '$email', '$title', '$type', '$file', NULL, NULL, NULL, NULL, 'NULL');");
				
			}
			//----------------------

				//code to be written for file name

			//----------------------



		}




	}	
	catch(PDOException $e){
		echo "Error: ".$e->getMessage() ;
	}




?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome bro</title>
</head>
<style type="text/css">
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
	.button{
		border: 0;
		background-color: none;
		display: block;
		margin-left: 45%;
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
	<div  style="background-color:#4CAD15">
		<br><h1>Submit Abstract</h1><br>
	</div>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
	  	<input class="button1" type="submit" name="home" value="HOME">
	</form>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data" >

		<?php echo $error; ?><br><br>
		Name: <?php echo strtoupper("$name ") ?><br><br>
		Email:<?php echo ("$email ") ?><br><br>
		Title:<br><textarea cols="60" rows="1" name="title" ></textarea><br>
		Event:<select name="type">
					<option value="Poster Presentation">Poster Presentation</option>
					<option value="Oral Presentation">
					Oral Presentation</option>		
				</select><br><br>
		Upload File : <input type="File" name="file" accept="application/pdf"><br><br>
		<input class="button" type="Submit" name="submit" value="submit"><br>



		<?php
			$stmt= $conn->query("SELECT * FROM `submissions` WHERE `user_id`='$user_id';");
			$users=$stmt->fetchAll();
			foreach ($users as $result) {
				$pdf1=$result["file"];
				
		?>
		<h2><?php echo $result["type"];?></h2>
		<br>
		<object data="data:application/pdf;base64,<?php echo base64_encode($pdf1) ?>" type="application/pdf" style="height:800px;width:80%"></object>
		<br><br><br>
		
		<?php
			}
		 ?>

		
	</form>
</body>

</html>
