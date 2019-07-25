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
	if (empty($_SESSION["username"]) || ($designation!="Student_Convener"&& $designation!="Faculty_Convener") ) {
		header("location: home.php");
		exit;
	}
	$username=$_SESSION["username"];

	//home
	if (isset($_POST['home'])) {
	  	session_start();
	  	$_SESSION["username"]=$username;
	  
	 	 if ($designation=="Student_Convener") {
	    	header("location:studentc_home.php");
	  	}
	  	else{
	   		header("location:facultyc_home.php"); 
	  	}
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
			$description=$_POST["description"];

			if (empty($title)) {
				$error="Title cannot be empty";
			}
			elseif (empty($description)) {
				$error="A picture is neccessary";
			}
			else if ($file==""){
				$error="A file must be selected";
			}

			else{
					$file=addslashes(file_get_contents($_FILES["file"]["tmp_name"]));
					$stmt= $conn->query("INSERT INTO `notices` (`notice_id`, `event`, `name`, `file`, `description`) VALUES (NULL, '$type', '$title', '$file','$description');");
				
			}

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
		<br><h1>Add Notices</h1><br>
	</div>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
	  	<input class="button1" type="submit" name="home" value="HOME">
	</form>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" enctype="multipart/form-data" >

		<?php echo $error; ?><br><br>


		Event:<select name="type">
					<option value="Poster Presentation">Poster Presentation</option>
					<option value="Oral Presentation">
					Oral Presentation</option>		
				</select><br><br>
		Title:<br><textarea cols="40" rows="2" name="title" ></textarea><br><br>
		
		<img src="" style="display:none" height="200" width="50" id="image">

		Upload Pic : 
		<br><input type="file" onchange="showImages(this)" name="file" ><br><br>
		<script>
			function showImages(e)
			{
				if (e.files[0]) {
					var obj= new FileReader();
					obj.onload=function(e){
						var image= document.getElementByID("image");
						image.src=e.target.result;
						image.style.display="block";
					}
					obj.readAsDataURL(e.files[0]);
				}
			}
		</script>

		 Description:<br><textarea cols="40" rows="10" name="description" ></textarea><br>

		<input class="button" type="Submit" name="submit" value="submit"><br>
		
	</form>
</body>

</html>
