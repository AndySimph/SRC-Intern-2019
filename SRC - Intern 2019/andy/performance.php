<?
	include("../../srcintranet.cred.php");
	$conn = new mysqli('localhost', $u, $p, "intranet");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Labor Performance</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="../assets/img/icon.ico" type="image/x-icon"/>
	<!-- CSS Files -->
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/azzara.min.css">
	<!-- Fonts and icons -->
	<script src="../assets/js/plugin/webfont/webfont.min.js"></script>
	<!--   Core JS Files   -->
	<script src="../assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="../assets/js/core/popper.min.js"></script>
	<script src="../assets/js/core/bootstrap.min.js"></script>
	<!-- jQuery UI -->
	<script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="../assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	<!-- Chart JS -->
	<script src="../assets/js/plugin/chart.js/chart.min.js"></script>
	<!-- Bootstrap Toggle -->
	<script src="../assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
	<!-- jQuery Scrollbar -->
	<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
	<!-- Azzara JS -->
	<script src="../assets/js/ready.min.js"></script>
	<!-- Azzara DEMO methods, don't include it in your project! -->
	<!--<script src="../assets/js/setting-demo.js"></script>-->
	<script>
		WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['../assets/css/fonts.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

</head>
<body>

<canvas id="multipleLineChart"></canvas>

<?	
	$colors=array("red", "blue", "green", "yellow", "pink", "purple", "orange", "brown");
	$depts=array("03","04","06","08","09","10","11","13");
?>	
	<script>
		var multipleLineChart = document.getElementById('multipleLineChart').getContext('2d');
		var myMultipleLineChart = new Chart(multipleLineChart, {
			type: 'line',
			data: {
<?
			$sql = "SELECT distinct(proddate) FROM `labor_performance` order by proddate asc";
			$result = mysqli_query($conn,$sql);
			$rowcount=mysqli_num_rows($result);
			echo '			    labels: [';
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				echo '"'.$row["proddate"].'"';
				if ($i != $rowcount - 1){
					echo ', ';
				}
				$i++;
			}
			echo "],\n";

			echo "				datasets: [\n";
			$i = 0;
			$arraylength = count($depts);
			while($i < $arraylength){
				$sql = "select prodDate, dept, deptName, laborHrs, laborCost, prodHrs, postHrs, stdRate, month, year from labor_performance where dept = '".$depts[$i]."' order by prodDate asc";
				$result = mysqli_query($conn,$sql);
				$rowcount=mysqli_num_rows($result);
				$r = 0;
				while($row = mysqli_fetch_assoc($result)){
					if ($r == 0){
						echo '{'."\n";
						echo '					label: "'.$row["deptName"].'",'."\n";
						echo '					borderColor: "'.$colors[$i].'",'."\n";
						echo '					pointBorderColor: "#FFF",'."\n";
						echo '					pointBackgroundColor: "'.$colors[$i].'",'."\n";
						echo '					pointBorderWidth: 2,'."\n";
						echo '					pointHoverRadius: 4,'."\n";
						echo '					pointHoverBorderWidth: 1,'."\n";
						echo '					pointRadius: 4,'."\n";
						echo '					backgroundColor: "transparent",'."\n";
						echo '					fill: true,'."\n";
						echo '					borderWidth: 2,'."\n";
						echo '					data: [';
					}
					if ($row['laborHrs'] != 0){
						$pct = round(($row['prodHrs'] / $row['laborHrs'])*100,2);
						echo $pct;
					} else {
						echo '0';
					}
					if ($r != $rowcount - 1){
						echo ', ';
					} else {
						echo "]\n";
						if ($i != $arraylength -1){
							echo "				},";
						}else{
							echo "				}]";
						}
					}
					$r++;
				}
				$i++;
			}
			?>
			},
			options : {
				responsive: true, 
				maintainAspectRatio: false,
				legend: {
					position: 'top',
				},
				tooltips: {
					bodySpacing: 4,
					mode:"nearest",
					intersect: 0,
					position:"nearest",
					xPadding:10,
					yPadding:10,
					caretPadding:10
				},
				layout:{
					padding:{left:15,right:15,top:15,bottom:15}
				}
			}
		});

	</script>
</body>
</html>