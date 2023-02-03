<?php
  $serverhost = "localhost";
  $username = "valtteri";
  $password = "MiinuspallO03";
  $dbname = "homeAutomation";

$conn = new mysqli($serverhost, $username, $password, $dbname);
if($conn->connect_error)
{
    die("Ei yhteyttä:" . $conn->connect_error);
}
else
{
    //echo "servu linjoil". "<br>";
}

$query = "SELECT * FROM tempSensor ORDER BY aikaleima DESC LIMIT 48";
$result = mysqli_query($conn, $query);
$chart_data = '';

while($row = mysqli_fetch_array($result))
{
    $chart_data .= "{ aikaleima:'".$row["aikaleima"]."', mittausarvo:".$row["mittausarvo"]."}, ";
}

$chart_data = substr($chart_data, 0, -2);
$query_log = "SELECT * FROM tempSensor ORDER BY aikaleima DESC LIMIT 48";
$result_log = mysqli_query($conn, $query_log);
$log_data = '';

while($row_log = mysqli_fetch_array($result_log))
{
    $log_data .= "aikaleima:'".$row_log["aikaleima"]."', mittausarvo: ".$row_log["mittausarvo"].", ";
}

$queryMIN = "SELECT MIN(mittausarvo) AS mittausarvo FROM tempSensor ORDER BY aikaleima DESC LIMIT 48";
$queryMAX = "SELECT MAX(mittausarvo) AS mittausarvo FROM tempSensor ORDER BY aikaleima DESC LIMIT 48";
$queryAVG = "SELECT AVG(mittausarvo) AS mittausarvo FROM tempSensor ORDER BY aikaleima DESC LIMIT 48";
$resultMIN = mysqli_query ($conn, $queryMIN );
$resultMAX = mysqli_query ($conn, $queryMAX );
$resultAVG = mysqli_query ($conn, $queryAVG );
$minValue = '';
$maxValue = '';
$avgValue = '';

while($rowMin = mysqli_fetch_assoc($resultMIN))
{
	  $minValue .= "minimiarvo: ".$rowMin["mittausarvo"].", ";
}

while($rowMax = mysqli_fetch_array($resultMAX))
{
	  $maxValue .= "maximiarvo: ".$rowMax["mittausarvo"].", ";
}

while($rowAvg = mysqli_fetch_array($resultAVG))
{
	  $avgValue .= "keskiarvo: ".$rowAvg["mittausarvo"].", ";
}

?>

<!DOCTYPE html>
<html>
  <head>
    <!-- <meta http-equiv="refresh" content="2"> Remember Delete -->
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
  </head>
  
  <body>
    <div class="container">
      <h2 class="text">Lämpömittari 1 graafi</h2>
      <br />
      <div id="chart"></div>
    </div>

    <div class="log">
      <div class="log-text">
        <?php echo $log_data; ?>
      </div>
      <h3 class="log-t" >Log</h3>
    </div>

    <h3 class="MKM" aling="center">Minimi - Maximi - Keskiarvo</h3>

    <div class="Max-Avg-Min">
      <?php
	    echo $minValue . "<br>";
	    echo $maxValue . "<br>";
	    echo $avgValue . "<br>";
      ?>
    </div>

  </body>
</html>

<script>
  Morris.Line
  ({
    element : 'chart',
    data:[<?php echo $chart_data; ?>],
    xkey: 'aikaleima',
    ykeys:['mittausarvo'],
    labels:['mittausarvo'],
    hideHover:'auto',
    stacked:true
  });
</script>
