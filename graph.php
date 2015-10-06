<?
include("header.php");

$month = $_GET['month'];
$year = $_GET['year'];
$farmaco = urldecode($_GET['farmaco_graf']);

$sql = "SELECT * FROM `registro` WHERE `nombre` LIKE '$farmaco' AND `month` = '$month' AND `year` = '$year' ORDER BY `entrada` ASC";
//echo $sql;die();
$result = mysqli_query($conn, $sql);
//echo mysqli_affected_rows($conn);

$index = 0;
if(mysqli_num_rows($result) == 0){
  ?><p align="middle">No existen datos en la fecha seleccionada para <?=$farmaco?> </p><?
  include("footer.php");
  die();
}

while($block = mysqli_fetch_assoc($result)){
$cantidad[$index] = $block['cantidad'];
$fecha[$index] = strtotime($block['salida']); //AMOUNT OF SECONDS, tabla con los dias del mes donde se ha recogido info
$index ++;
}

?>

<div style="width: 50%; height: 30%;">
<canvas id="myChart"></canvas>
</div>

<script>
var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
var options = {
    barValueSpacing : 5,
    barStrokeWidth : 2,
    barDatasetSpacing : 10,
}
var barChartData = {
	labels : [<?get_x_axis(strtotime($month . "/1" . "/" .$year), strtotime($month . "/". date("t", strtotime($month . "/" . $year)) ."/". $year))?>],//desde el inicio del mes al fin del mes.
	datasets : [
		{
			fillColor : "rgba(255, 0, 0, 0.7)",
			strokeColor : "rgba(151,187,205,0.8)",
			highlightFill : "rgba(151,187,205,0.75)",
			highlightStroke : "rgba(151,187,205,1)",
			data : [<?get_y_axis(strtotime($month . "/1" . "/" .$year), strtotime($month . "/". date("t", strtotime($month . "/" . $year)) ."/". $year), $fecha, $cantidad)?>]
		}
	]

}
window.onload = function(){
  // Get context with jQuery - using jQuery's .get() method.
  var ctx = $("#myChart").get(0).getContext("2d");
  // This will get the first returned node in the jQuery collection.
  var myNewChart = new Chart(ctx);
	window.myBar = new Chart(ctx).Bar(barChartData, options, {
		responsive : true
	});
}

</script>

<?
include("footer.php");
?>