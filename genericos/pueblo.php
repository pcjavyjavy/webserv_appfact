<?php
$provincia = $_GET['provincia'];

$servername = "localhost";
$username = $_GET['a'];
$password = $_GET['b'];
$dbname = $_GET['c'];

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
mysqli_set_charset("utf8");

$provincia = str_replace(":",",",$provincia);

mysqli_select_db($con,"ajax_demo");
$sql="SELECT DISTINCT Pueblo FROM CPS WHERE Provincia = '".$provincia."' ORDER BY Pueblo";
$result = mysqli_query($con,$sql);

$env_csv = "";

while($row = mysqli_fetch_array($result)) {
    $swap = str_replace(",",":",$row['Pueblo']);
    $env_csv .= $swap.",";
}
$env_csv = substr($env_csv, 0, -1);
print $env_csv;
mysqli_close($con);
?>
