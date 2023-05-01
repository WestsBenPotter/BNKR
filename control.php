<?php
//PHP DB
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'bnkr';
$table = 'sessiondata';
//connect
$con = mysqli_connect($db_host,$db_user,$db_password,$db_db);
//PASSWORD
$pass = $_POST['pass'];
$passquery = "SELECT `password` FROM `logindata` WHERE `ID` = 1";
$passqueryresult = mysqli_query($con , $passquery);
foreach($passqueryresult as $passresult){
  $checkagainstpassword = $passresult["password"];
};
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
?>

<!doctype html>
  <html>
  <head>
    <title>BNKR - Countdown Control</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="application-name" content="MyApp" />
    <meta name="apple-mobile-web-app-title" content="MyApp" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="apple-touch-icon" href="img/app-icon.png" />
    <script type="text/javascript" src="js/jquery-3.6.4.min.js"></script>
    <script type="text/javascript">
    $( document ).ready(function() {

    //get time:
    const d = new Date();
    let time = d.getTime();

    //ANIMATE ARROW function
    function doEvery500(){

      //get ajax data
      var response = '';
      $.ajax({
          type: "GET",
          url: "http://localhost:8888/ajax.php",
          async: false,
               success : function(text)
           {
               response = text;
           }
      });
      //readit
      var newresponse = String(response);
      //write it to the invisible div
      document.getElementById('hidden').innerHTML = newresponse;
      //read the specifics of each element
      time = $("#timer").html();
      seconds = $("#secondstogor").html();

      if(seconds > 0){
        $("#sessionrunning").slideDown(150);
        $("#submitStart").val('stop & restart');
      }else{
        $("#sessionrunning").slideUp(150);
        $("#submitStart").val('start');
      };
      document.getElementById('secondsleft').innerHTML = seconds;

    }
    doEvery500();
    setInterval(function() {
        doEvery500();
    }, 250);

  });
  </script>
  </head>
  <body>
    <div id="loading" style="display:none;"></div>
    <div id="hidden"></div>
    <div class="wrapper">
      <div class="content control">
        <?php
        //0. Order: Check if password: Write session, check for session, if no, check for password, if no, form
        $IP             = $_SERVER['REMOTE_ADDR'];
        $NowTimeSec     = $time = floor(microtime(true));
        $FutureTimeSec  = $NowTimeSec + 604800; //adding 7 days
        $table          = 'SQLSession';
        $sessionActive  = 'false';
        //1. Verify PASSWORD
        $passCheck      = password_verify($pass, $checkagainstpassword);
        if($passCheck){
          //2. Create a session for 7 days:
          $SessionQuery = 'INSERT INTO `SQLSession` (`IP`, `TimeStart`, `TimeEnd`)
VALUES ("'.$IP.'", "'.$NowTimeSec.'", "'.$FutureTimeSec.'")';
          mysqli_query($con , $SessionQuery);
        };
        //3. Check if a session exists
        $RowQuery = "SELECT * FROM `SQLSession` WHERE `IP` = '".$IP."' ORDER BY ID DESC LIMIT 1";
        $RowResult = mysqli_query($con , $RowQuery);
        if($RowResult->num_rows > 0){ //if there is a result
          foreach($RowResult as $result){
            if($result["TimeEnd"] > $NowTimeSec){ //if the future time is later than current time
              //valid session!
              $sessionActive  = 'true';
            };
          };
        };
        if($sessionActive  == 'true'){
        ?>
        <h1><img id="logo" src="img/logo.png" alt="BNKR Logo" width="25" class="control"/> Control</h1>
        <br />
        <div id="beginsession">
          <form id="beginsession" action="http://localhost:8888/startsession.php" method="post">
            <span class="label">interval length:</span><br />

            <select id="sessionlength" name="sessionlength">
              <option value="50" <?php if($sessionlength == '50'){echo 'selected="selected"';};?>>50/10</option>
              <option value="40" <?php if($sessionlength == '40'){echo 'selected="selected"';};?>>40/20</option>
              <option value="20" <?php if($sessionlength == '20'){echo 'selected="selected"';};?>>20/10</option>
            </select>
            <span class="label">interval rotations:</span><br />
            <select id="sessionrotations" name="sessionrotations">
              <option value="1" <?php if($sessionrotations == '1'){echo 'selected="selected"';};?>>1</option>
              <option value="2" <?php if($sessionrotations == '2'){echo 'selected="selected"';};?>>2</option>
              <option value="3" <?php if($sessionrotations == '3'){echo 'selected="selected"';};?>>3</option>
              <option value="4" <?php if($sessionrotations == '4'){echo 'selected="selected"';};?>>4</option>
              <option value="5" <?php if($sessionrotations == '5'){echo 'selected="selected"';};?>>5</option>
            </select>
            <input type="hidden" name="pass" value="<?php echo $pass; ?>"></input>
            <input type="submit" id="submitStart" value="Start" />
            <p id="sessionrunning" style="display:none;">session is currently runnning:<br /><span id="secondsleft"></span> seconds left</p>
          </form>
        </div>
      <?php }else{ ?>
        <form method="POST" action="?trylogin">
          <input type="password" name="pass" placeholder="password"></input><br/>
          <input type="submit" name="submit" value="Login"></input>
          </form>
      <?php }; ?>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/main.js"></script>
</html>
