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
	<table>
		<form id='frmEdit' method='GET' Action='index.php' target='_parent'>
		<input type='hidden' name='Action' value='edit/add'>
			<td align='center'><br>
			Department:<br>
				<select name="edit_dept" width="75%">
					<option value=''></option>
					<?
						$sql="SELECT distinct(deptNum), deptName FROM `andy_dept` order by deptNum asc";
						$result=mysqli_query($conn,$sql);
						while ($row=mysqli_fetch_assoc($result)) {
							echo "<option value=".$row['deptNum'];
							echo ">".$row['deptNum']." ".$row['deptName']."</option>";
						}
					?>
				</select>
			</td>
			<td align='center'><br>
				Date: (mm/dd/yyyy)<br>
				<input type="text" name="edit_date" maxlength="10">
			<br></td>
			<td align='center'><br>
				Labor Hours:<br>
				<input type="number" name="edit_labor" maxlength="5">
			<br></td>
			<td align='center'><br>
				Production Hours:<br>
				<input type="number" name="edit_prod" maxlength="5">
			<br></td>
			<td align='center'><br>
				Post Hours:<br>
				<input type="number" name="edit_post" maxlength="5">
			<br></td>
			<td align='center'><br>
				<input type="submit" value="Submit">
			</td>
		</form>
	</table>
</body>
</html>