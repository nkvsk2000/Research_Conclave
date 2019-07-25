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
	if (empty($_SESSION["username"]) || $designation!="Faculty_Convener" ) {
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
		if (isset($_POST['allocate'])) {
			session_start();
			$_SESSION["username"]=$username;
			header("location:faculty_approval.php");
		}
		if (isset($_POST['allocated_list'])) {
			session_start();
			$_SESSION["username"]=$username;
			header("location:allocated_list.php");
		}

		if (isset($_POST['graded_list'])) {
			session_start();
			$_SESSION["username"]=$username;
			header("location:graded_list.php");
		}

		if (isset($_POST['addnotice'])) {
			session_start();
			$_SESSION["username"]=$username;
			header("location:Add_Notices.php");
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
  			
  		function allocate(){
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
		<br><h1>Hi!! <?php echo htmlspecialchars($_SESSION["username"]); ?> Lets Start</h1><br>
	</div>
	<br><br><button class="button" onclick="signout()">Signout</button>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		 <br><input class="button" type="submit" name="allocate" value="Approval">
	</form>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		 <br><input class="button" type="submit" name="allocated_list" value="Check Allocated List">
	</form>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		 <br><input class="button" type="submit" name="graded_list" value="Check Graded List">
	</form>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		 <br><input class="button" type="submit" name="addnotice" value="Add Notices">
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
	<h4 width:50%><?php echo $result["description"];?></h4><br>
	</div>
	
	<?php
		}
	 ?>


</body>

</html>