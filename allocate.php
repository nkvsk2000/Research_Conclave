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
$error="";

//each abstract details

session_start();
	$designation= $_SESSION["designation"];
	//check for empty
	if (empty($_SESSION["username"]) || $designation!="Student_Convener" ) {
		header("location: home.php");
		exit;
	}
	$username=$_SESSION["username"];
	
	if (isset($_POST['home'])) {
	  	session_start();
	  	$_SESSION["username"]=$username;
	  	header("location:studentc_home.php");
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
			$Reviewer1=$_POST["rev1"];
			$Reviewer2=$_POST["rev2"];
			$id=$_POST["id"];

			if ($_POST["rev1"]==0 || $_POST["rev2"]==0) {
				$error="Reviewers are not alloted";	
			}
			else if ($Reviewer1== $Reviewer2){
				$error="You should allocate two different reveiewers to each abstract";
			}
			else {
				$stmt=$conn->query("UPDATE `submissions` SET `reviewer1` = '$Reviewer1', `reviewer2` = '$Reviewer2', `status` = 'Allocated' WHERE `submissions`.`submission_id` ='$id' ;");
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
<body align="center">

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
		 <input type="submit" name="home" value="HOME">
	</form>


	<h1>Allocate</h1>
	<?php echo $error; ?><br><br>
	<?php 
		$stmt= $conn->query("SELECT * FROM `submissions` WHERE `status`= 'NULL';");
		$users=$stmt->fetchAll();
		foreach ($users as $result) {
			

	?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" >

		
		Name: <?php echo $result["name"]."------" ?> Event:<?php echo $result["type"]."------" ?> Title:<?php echo $result["title"]."-------" ?>
		<input type="hidden" name="id" value="<?php echo $result[0]; ?>">
		
		Reviewer1:<select name="rev1">
					<option selected="selected">Choose one</option>
    			    <?php
    			    	if ($result["type"]=="Poster Presentation") {
    			    		$designation="Reviewer(P)";
    			    	}
    			    	else{
    			    	 	$designation="Reviewer(O)";
    			    	}

    			    	$stmt1= $conn->query("SELECT * FROM `users` WHERE `designation`= '$designation';");
						$users1=$stmt1->fetchAll();
						foreach ($users1 as $result1) {
        			?>
        			<option value="<?php echo strtolower($result1[0]); ?>"><?php echo $result1[1]; ?></option>
        			<?php
       					 }
        			?>	
				</select>
          <?php echo "---------"; ?>

		Reviewer2:<select name="rev2">
					<option selected="selected">Choose one</option>
    			    <?php
    			    	if ($result["type"]=="Poster Presentation") {
    			    		$designation="Reviewer(P)";
    			    	}
    			    	else {$designation="Reviewer(O)";}

    			    	$stmt1= $conn->query("SELECT * FROM `users` WHERE `designation`= '$designation';");
						$users1=$stmt1->fetchAll();
						foreach ($users1 as $result1) {
        			?>
        			<option value="<?php echo strtolower($result1[0]); ?>"><?php echo $result1[1]; ?></option>
        			<?php
       					 }
        			?>	
				</select>
			<?php echo "---------"; ?>
		<input type="Submit" name="submit" value="Allocate"><br><br><br>		
	</form>

	<?php
		}
	 ?>

</body>

</html>
