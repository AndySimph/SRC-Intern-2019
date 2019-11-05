<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="mystyles.css" type="text/css">
</head>
<body>
<?PHP
	include("../../srcintranet.cred.php");
	$conn = new mysqli('localhost', $u, $p, "intranet");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>
<?
	//Page To display specific department information
	$spec_frame=$_GET['sep_dept'];
	echo "<table style='width:100%' border='0' padding='20px'>";
	$totals=array(0,0,0);
	$dire_perf=array();
	$caption_ctr=0;
	$i=0;
	$sql="SELECT alp.id, alp.prodDate as prodDate, alp.dept as dept, ad.deptName as deptName, alp.laborHrs as laborHrs, alp.prodHrs as prodHrs, alp.postHrs as postHrs
		FROM `andy_labor_performance` alp LEFT JOIN andy_dept ad ON alp.dept = ad.deptNum where alp.dept = '".$spec_frame."' order by prodDate asc";
	$result=mysqli_query($conn,$sql);
	while ($row=mysqli_fetch_assoc($result)) {
		if ($caption_ctr==0) {
			echo "<caption><b>".$row['deptName']."</b><br>(Production Hours / Labor Hours) * 100 = Direct Performance</caption>";
			echo "<tr align='center'><th>ID</th><th>Date</th><th>Production Hours</th><th></th><th>Labor Hours</th><th></th><th>Direct Performance</th>";
			$caption_ctr++;
		}
		echo "<tr align='center'><td>".$row['id']."</td><td>".$row['prodDate']."</td>";
		echo "<td>".$row['prodHrs']."</td>";
		$totals[0]+=$row['prodHrs'];
		echo "<td>/</td>";
		echo "<td>".$row['laborHrs']."</td>";
		$totals[1]+=$row['laborHrs'];
		echo "<td> * 100 = </td>";
		if ($row['laborHrs']!=0) {
			array_push($dire_perf, round((($row['prodHrs']/$row['laborHrs'])*100), 2));
			echo "<td>".$dire_perf[$i]."</td>";
		} else { 
			array_push($dire_perf, 0);
			echo "<td>".$dire_perf[$i];
			echo " (Labor Hours is 0)</td>";	
		}
		$totals[2]+=$dire_perf[$i];	
		$i++;
	}	
	echo "</tr><tr align='center'><th colspan='2'>Total:</th><td>".$totals[0]."</td><td></td><td>".$totals[1]."<td></td><td>".$totals[2]."</td></tr>";
?>
</body>
</html>
