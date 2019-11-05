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
<br>
	<table>
		<form id='frmDel' method='GET' Action='index.php' target='_parent' onsubmit="return confirm('Are you sure you want to delete this id?')">
		<input type='hidden' name='Action' value='delete'>
			<td align='center'>
			ID to Remove:<br>
				<select name="del_id"autofocus>
					<option value=''></option>
					<?
						$sql="SELECT id FROM `andy_labor_performance` order by id asc";
						$result=mysqli_query($conn,$sql);
						while ($row=mysqli_fetch_assoc($result)) {
							echo "<option value=".$row['id'];
							echo ">".$row['id']."</option>";
						}
					?>
				</select>
			</td>
			<td align='center'><br>
				<input type="submit" value="Submit" />
			</td>
		</form>
		<td width='35%' align='middle'><h1>Or</h1></td>
		<form id='dateDel' method='Get' Action='index.php' target='_parent' onsubmit="return confirm('Are you sure you want to delete this date?')">
			<td align='center'>
			Date to remove: (mm/dd/yyyy)<br>
			<input type="text" name="date_rmv" maxlength="10"><br>
			</td>
			<td align='center'><br>
				<input type="submit" value="Submit"/>
			</td>
		</form>
	</table
</body>
</html>
