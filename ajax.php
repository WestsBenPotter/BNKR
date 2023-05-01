<?php
//PHP DB
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'bnkr';
$table = 'sessiondata';
//connect
$con = mysqli_connect($db_host,$db_user,$db_password,$db_db);
//query
$query = "SELECT `time`, `sessionrotations`, `sessionlength`, `ID` FROM $table";
$result = mysqli_query($con , $query);
//assign
$sessionrotations = '';
$sessionlength = '';
$time = '';
foreach($result as $item){
  if($item["ID"] == '1'){
    $sessionrotations = $item["sessionrotations"];
    $sessionlength = $item["sessionlength"];
    $time = $item["time"];
  };
};
//work out number to display:
//take the set time and calc the difference between that and a projected time
//start with inteverval
if($sessionlength == 50){
    $projectedtime = 60; //60 second round
    $breaktime = 10; //cooloff time
}
if($sessionlength == 40){
    $projectedtime = 60; //60 second round
    $breaktime = 20; //cooloff time
}
if($sessionlength == 20){
    $projectedtime = 30; //60 second round
    $breaktime = 10; //cooloff time
}
//work out a finish time number in ms
$projectedtime = $projectedtime * intval($sessionrotations);
$endtime = $projectedtime * 1000 + $time;
$timetogo = floor(($endtime - floor(microtime(true) * 1000))/1000);
//work out the display number:
if($timetogo > 0){//work out if negative
  if($timetogo > 60){
    $displaynumber = $timetogo - floor(($timetogo / 60))*60;
  }else{
    $displaynumber = $timetogo;
  };
}else{
  $displaynumber = 0;
}
?>
<html>
  <head>
  </head>
  <body>
    set time: <span id="settime"><?php echo $time; ?></span><br />
    end time: <span id="settime"><?php echo $endtime; ?></span><br />
    current time: <span id="currenttime"><?php echo floor(microtime(true) * 1000);?></span><br />
    seconds to go: <span id="secondstogor"><?php echo $timetogo; ?></span><br />
    display number: <span id="displaynumber"><?php echo $displaynumber; ?></span><br />
    session length: <span id="sessionlength"><?php echo $sessionlength; ?></span><br />
    session rotations: <span id="sessionrotations"><?php echo $sessionrotations; ?></span><br />
    session cooldown: <span id="sessioncooldown"><?php echo $breaktime; ?></span>
  </body>
</html>
