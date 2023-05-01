<?php
//start session!
$sessionlength = $_POST["sessionlength"];
$sessionrotations = $_POST["sessionrotations"];
$pass = $_POST["pass"];
$time = floor(microtime(true) * 1000);
//PHP DB
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'bnkr';
$table = 'sessiondata';
$con = mysqli_connect($db_host,$db_user,$db_password,$db_db);
//write to table
$query = "UPDATE $table
          SET `time` = $time, `sessionrotations` = $sessionrotations, `sessionlength` = $sessionlength
          WHERE `ID` = '1'";
mysqli_query($con , $query);
header("location: http://localhost:8888/BNKR/control.php");
mysqli_close($con);
?>
