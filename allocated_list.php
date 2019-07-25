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
$rev1name="";
$rev2name="";

session_start();
  $designation= $_SESSION["designation"];
  //check for empty
  if (empty($_SESSION["username"]) || ($designation!="Student_Convener"&& $designation!="Faculty_Convener") ) {
    header("location: home.php");
    exit;
  }
  $username=$_SESSION["username"];

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

  } 
  catch(PDOException $e){
    $error=$e->getMessage() ;
  }




?>

<!DOCTYPE html>
<html>
<head>
  <title>Welcome bro</title>
  <style>
  table {
   width: 50%;
   color: #588c7e;
   font-family: monospace;
   font-size: 25px;
   text-align: left;
     } 
  th {
   background-color: #588c7e;
   color: white;
    padding: 20px;
    }
  tr:nth-child(even) {background-color: #f2f2f2}
  tr:nth-child(odd) {background-color: #ABB1A8}
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
</head>
<body align="center" bgcolor="#ACEF84">
  <div style="background-color:#4CAD15">
    <br><h1>List of Submissions along with the alloted Reviewers</h1><br>
  </div>
  <?php echo $error; ?><br>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
      <input class="button1"type="submit" name="home" value="HOME">
  </form>

  <table align="center">
    <tr>
      <th>Submission Id</th>
      <th>Title</th>
      <th>Participant Name</th>
      <th>Reviewer1</th>
      <th>Reviewer2</th>
    </tr>

    <?php 

      $stmt= $conn->query("SELECT * FROM `submissions`;");
      $total=$stmt->rowCount();
      $completed=0;
      $users=$stmt->fetchAll();
      foreach ($users as $result) {
        
        if ($result["status"]!="NULL") {
           $completed=$completed+1; 

           $rev1=$result["reviewer1"];
           $rev2=$result["reviewer2"];
           
           
           $stmt1=$conn->query("SELECT * FROM `users` WHERE `user_id`='$rev1';");
           $users1=$stmt1->fetchAll();
           
           $stmt2=$conn->query("SELECT * FROM `users` WHERE `user_id`='$rev2';");
           $users2=$stmt2->fetchAll();
           
           foreach ($users1 as $result1) {
             $rev1name=$result1["name"];
           }
           foreach ($users2 as $result2) {
             $rev2name=$result2["name"];
           } 

           echo "<tr><td>".$result[0]."</td><td>".$result["title"]."</td><td>".$result["name"]."</td><td>".$rev1name."</td><td>".$rev2name."</td></tr>";
        }
      }
    
    ?>

  </table><br><br>

  <h2>Total No of Submission:<?php echo $total ?></h2>
  <h2>Total No of Allocated Submission:<?php echo $completed ?></h2>
</body>

</html>
