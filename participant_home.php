<?php

$username;
$designation;
$name;

$servername="localhost";
$serverusername="root";
$password="";
$database_name="research_conclave";
$error;
session_start();
	$designation= $_SESSION["designation"];
	//check for empty
	if (empty($_SESSION["username"]) || $designation!="Participant" ) {
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
		}

		if (isset($_POST['file'])) {
			session_start();
			$_SESSION["username"]=$username;
			header("location:abstractupload.php");
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
	<script type="text/javascript" >
  			
  		function uploadfile(){
			window.location.replace("abstractupload.php");

		}
		function signout(){
			window.location.replace("signout.php");
		}
  	</script>
</head>
<style type="text/css">
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
	<div style="background-color:#4CAD15">
		<br><h1>Hi!! <?php echo htmlspecialchars($_SESSION["username"]); ?>Lets Start</h1><br>
	</div><br>
	<button class="button"onclick="signout()">Signout</button><br>
	<?php 
		echo strtoupper("$name ---  ");
		echo strtoupper(" $designation");
	?><br><br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		Upload File:<input class="button" type="submit" name="file" value="upload">
	</form>

	
	<?php
		//to see the notices

		$stmt= $conn->query("SELECT * FROM `notices` ");
		$users=$stmt->fetchAll();
		foreach ($users as $result) {
			$pdf1=$result["file"];

			
	?>
	<div>
	<h4><?php echo $result["event"];?></h4>
	<h4><?php echo $result["name"];?></h4>
	<br>
	<img src="data:image/jpeg;base64,<?php echo base64_encode($pdf1) ?>" style="height:400px;width:50%">
	<br><br>
	<h4 ><?php echo $result["description"];?></h4><br>
	</div>
	
	<?php
		}
	 ?>

	
</body>

</html>