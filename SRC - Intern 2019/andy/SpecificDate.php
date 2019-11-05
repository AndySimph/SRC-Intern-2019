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
	//Page to display specific date information
	$spec_date=$_GET['sep_date'];
	echo "<table style='width:100%' padding='20px'>";
	$caption_ctr=0;
	$sql="SELECT alp.id, alp.prodDate as prodDate, alp.dept as dept, ad.deptName as deptName, alp.laborHrs as laborHrs, alp.prodHrs as prodHrs, alp.postHrs as postHrs
		FROM `andy_labor_performance` alp LEFT JOIN andy_dept ad ON alp.dept = ad.deptNum where alp.prodDate = '".$spec_date."' order by id asc";
	$result=mysqli_query($conn,$sql);
	while ($row=mysqli_fetch_assoc($result)) {
		if ($caption_ctr==0) {
			echo "<caption><b>".$row['prodDate']."</b><br><br></caption>";
			echo "<tr align='left'><th>ID</th><th>Department</th><th>Production Hours</th><th>Labor Hours</th><th>Post hours</th><th>Direct Performance</th>";
			$caption_ctr++;
		}
		echo "<tr align='left'><td>".$row['id']."</td>";
		echo "<td>".$row['dept']." ".$row['deptName']."</td>";
		echo "<td>".$row['prodHrs']."</td>";
		echo "<td>".$row['laborHrs']."</td>";
		echo "<td>".$row['postHrs']."</td>";
		if ($row['laborHrs']!=0) {
			echo "<td>".round((($row['prodHrs']/$row['laborHrs'])*100), 2)."</td>";
		} else {
			echo "<td>0</td>";
		}
		echo "</tr>";
	}
?>
</body>
</html>
