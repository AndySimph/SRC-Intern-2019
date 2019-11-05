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
	//Adding or Editing Data
	$error='';
	$nDept=$_GET['edit_dept'];
	$nDate=$_GET['edit_date'];
	$nLabor=$_GET['edit_labor'];
	$nProd=$_GET['edit_prod'];
	$nPost=$_GET['edit_post'];
	$myregex = '~^\d{2}/\d{2}/\d{4}$~';
	$found=0;
	if ($nDept==null && $ndate==null && $nLabor==null && $nProd==null && $nPost==null) {
		$nStart=true;
	} else {
		$nStart=false;
	}	
	if (($nDept!='') && ((filter_var($nDate,FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=> $myregex)))) &&
		((is_numeric($nLabor)) && ($nLabor>=0)) && ((is_numeric($nProd)) && ($nProd>=0)) && ((is_numeric($nPost)) && ($nPost>=0)))) {
		$sql="SELECT dept, prodDate FROM `andy_labor_performance` order by dept asc;";
		$result=mysqli_query($conn,$sql);
		while ($row=mysqli_fetch_assoc($result)) {
			if ($row['dept']==$nDept && $row['prodDate']==$nDate) {
				$found=1;
			}
		}
		if ($found==0) {
			$sql="INSERT INTO `andy_labor_performance` (prodDate, dept, laborHrs, prodHrs, postHrs)
				VALUES ('".$nDate."', '".$nDept."', '".$nLabor."', '".$nProd."', '".$nPost."');";
			$result=mysqli_query($conn,$sql);
			$error='Success';
		} else {
			$sql="UPDATE `andy_labor_performance`
				SET  laborHrs='".$nLabor."', prodHrs='".$nProd."', postHrs='".$nPost."'
				WHERE dept = '".$nDept."' and prodDate='".$nDate."';"; 	
			$result=mysqli_query($conn,$sql);
			$error='Update';
		}
	} else {
		if ($nDept==='') {
			if ($error=='') {
				$error=$error."Department";
			} else {
				$error=$error.", Department";
			}
		}
		if (!(filter_var($nDate,FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=> $myregex))))) {
			if ($error=='') {
				$error=$error."Date";
			} else {
				$error=$error.", Date";
			}
		}
		if (!(is_numeric($nLabor)) || ($nLabor<0)) {
			if ($error=='') {
				$error=$error."Labor Hours";
			} else {
				$error=$error.", Labor Hours";
			}
		}
		if (!(is_numeric($nProd)) || ($nProd<0)) {
			if ($error=='') {
				$error=$error."Production Hours";
			} else {
				$error=$error.", Production Hours";
			}
		}
		if (!(is_numeric($nPost)) || ($nPost<0)) {
			if ($error=='') {
				$error=$error."Post Hours";
			} else {
				$error=$error.", Post Hours";
			}
		}
	}
	if (($error!='' && $error!='Success' && $error!='Update' && $nStart!=true) || ($nDept==='' && $nDate==='' && $nLabor==='' && $nProd==='' && $nPost==='')) {
		echo "<div class='alert alert-danger'><center> Invalid Data in ".$error."!<center/></div>";
	} else if ($error=='Success') {
		echo "<div class='alert alert-success'><center><strong>Success!</strong> Data added</center></div>";
	} else if ($error=='Update') {
		echo "<div class='alert alert-info'><center><strong>Success!</strong> Data Updated</center></div>";
	} 

	//Deleting Data
	$delete_id=$_GET['del_id'];
	if ($delete_id!=null) {
		$sql="DELETE FROM `andy_labor_performance` WHERE id=".$delete_id;
		$result=mysqli_query($conn,$sql);
		if ($result==true) {
			echo "<div class='alert alert-success'><center><strong>Success!</strong> Date Successfully deleted</center></div>";;
		}
	} else if ($delete_id==='') {
		echo "<div class='alert alert-danger'><center>Invalid ID</center></div>";
	}
	$date_del=$_GET['date_rmv'];
	$match=false;
	if ($date_del!=null) {
		if (filter_var($date_del,FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=> $myregex)))) {
			$sqldate="SELECT distinct(prodDate) FROM `andy_labor_performance` order by prodDate asc";
			$result=mysqli_query($conn,$sqldate);
			while ($row=mysqli_fetch_assoc($result)) {
				if ($row['prodDate']==$date_del) {
					$match=true;
				}
			}
			if ($match==true) {
				$sql="DELETE FROM `andy_labor_performance` WHERE prodDate = '".$date_del."';";
				$result=mysqli_query($conn,$sql);
				if ($result==true) {
					echo "<div class='alert alert-success'><center><strong>Success!</strong> Date Successfully deleted</center></div>";
				}
			} else {
				echo "<div class='alert alert-danger'><center>There is no matching date</center></div>";
			}
		} else {
			echo "<div class='alert alert-danger'><center>".$date_del." is an invalid Date!</center></div>";
		}
	} else if ($date_del===''){
		echo "<div class='alert alert-danger'><center>The Date field is empty!</center></div>";
	}
	
	//Variables for Departments
	$sortMethod=$_GET['sorton'];
	if (is_null($sortMethod)) {
		$sortMethod='deptNum asc';
	}
	$sql="SELECT deptNum, deptName FROM `andy_dept` order by ".$sortMethod.";";			//switches order by dept num
	$result=mysqli_query($conn,$sql);
	$i=0;
	while ($row=mysqli_fetch_assoc($result)) {
		$all_depts[$i]=$row["deptNum"];
		$i++;
	}
	$reqDept=$_GET['dept'];
  	if ($reqDept=='' || $reqDept[0]=='' || $reqDept[(count($reqDept)-1)]=='' || $reqDept==$all_depts) {
		$depts=$all_depts;
		$all_used=1;
	} else {
		$depts=$reqDept;
		if (count($reqDept)>count($all_depts)) {
			array_pop($depts);
		}
	}
	
	//Variables for Report Types
	$reqType=$_GET['report'];
	if ($reqType!='') {
		$rptType=$reqType;
	} else {
		$rptType="Direct Performance";
	}
	
	//Variables for Dates and the method of how they are displayed
	$date=array(null);
	$method_Type=0;
	$radioDaily="checked";
	if ($_GET['Method']=='Daily') {
		$radioDaily="checked";
		$method_Type=0;
	}
	$radioMTD="";
	if ($_GET['Method']=='MTD') {
		$radioMTD="checked";
		$method_Type=1;
	}

?>
	<h1 align='center'>Andy's Page</h1>
	<table style="width:100%" border="0">
		<tr><td width="30%">
			<table style="width:100%">
				<form id='frmFilter' method='GET' Action='index.php'>
					<td>
						Report Type:<br>
						<select name='report' id="report">
							<option value='Direct Performance'	<?if($_GET['report']=="Direct Performance"){echo selected;}?>	>Direct Performance</option>
							<option value='laborHrs'			<?if($_GET['report']=="laborHrs"){echo selected;}?>				>Labor Hours</option>
							<option value='prodHrs'				<?if($_GET['report']=="prodHrs"){echo selected;}?>				>Production Hours</option>
							<option value='postHrs'				<?if($_GET['report']=="postHrs"){echo selected;}?>				>Post Hours</option>
						</select>
					</td>
					
					<td>
						Date Type:<br>
						<input type="radio" name="Method" value="Daily" <?echo $radioDaily; ?> 	>Daily<br>
						<input type="radio" name="Method" value="MTD" 	<?echo $radioMTD; ?> 	>MTD
					<br></td>
					
					<td>
						Department:<br>
						<select name="dept[]" multiple size=5>
							<option value='' <?if($reqDept=="" || $reqDept[0]=='' || $all_used==1) {echo selected;}?> >All</option>
							<?
								$sql="SELECT distinct(deptNum), deptName FROM `andy_dept` order by deptNum asc";
								$result=mysqli_query($conn,$sql);
								$i=0;
								while ($row=mysqli_fetch_assoc($result)) {
									echo "<option value=".$row['deptNum'];
									for ($d=0;$d<=count($depts);$d++) {
										if ($depts[$d]==$row['deptNum'] && $all_used!=1) {
											echo " selected ";
										}
									}
									echo ">".$row['deptNum']." ".$row['deptName']."</option>";
								}
							?>
						</select>
					</td>
					<td align='center'>
						<input type="submit" value="Submit">
					</td>
				</form>
			</table>
		</td>
		<td width="15%">
			<a href='editdata.php?' target='changedata' class='button button1'><b>Add/Edit Data</b></a>
			<a href='del_data.php?' target='changedata' class='button button2'><b>Delete Data</b></a>
		</td>
		<td>
			<iframe src='' name='changedata' width='100%' scrolling='no' frameborder='0'></iframe>
		</td>
		</tr>
	</table>
	<br>
<?
	echo "<table style='width:100%'><tr><th>Dept Number:";
	//Sort Links
	if ($all_used==1) {
		echo "<a href='index.php?report=".$_GET['report']."&Method=".$_GET['Method'];
		for ($i=0;$i<count($reqDept);$i++) {
			echo "&dept%5B%5D=".$reqDept[$i];
		}
		echo "&sorton=deptNum asc'><img src='https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-up-01-512.png' width='15' height='15' border='0'></a>";
		echo "<a href='index.php?report=".$_GET['report']."&Method=".$_GET['Method'];
		for ($i=0;$i<count($reqDept);$i++) {
			echo "&dept%5B%5D=".$reqDept[$i];
		}
		echo "&sorton=deptNum desc'><img src='https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-down-01-512.png' width='15' height='15' border='0'></a>";
	}
	echo "</th><th>Dept Name: ";
	if ($all_used==1) {
		echo "<a href='index.php?report=".$_GET['report']."&Method=".$_GET['Method'];
		for ($i=0;$i<count($reqDept);$i++) {
			echo "&dept%5B%5D=".$reqDept[$i];
		}
		echo "&sorton=deptName asc'><img src='https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-up-01-512.png' width='15' height='15' border='0'></a>";
		echo "<a href='index.php?report=".$_GET['report']."&Method=".$_GET['Method'];
		for ($i=0;$i<count($reqDept);$i++) {
			echo "&dept%5B%5D=".$reqDept[$i];
		}
		echo "&sorton=deptName desc'><img src='https://cdn3.iconfinder.com/data/icons/faticons/32/arrow-down-01-512.png' width='15' height='15' border='0'></a>";
	}
	echo "</th>";

	//Prod Date
	$sqldate="SELECT distinct(prodDate) FROM `andy_labor_performance` order by prodDate asc";
	$result=mysqli_query($conn,$sqldate);
	$ctr_date=0;
	while ($rowdate=mysqli_fetch_assoc($result)) {
		echo "<th>";
		?><a href='SpecificDate.php?sep_date=<?echo $rowdate["prodDate"];?>' target='spec_date'><?echo $rowdate["prodDate"];?></a><?
		echo "</th>";
		$date[$ctr_date]=$rowdate["prodDate"];
		$ctr_date++;
	}
	if ($method_Type==0) {
		echo "<th> Total:</th></tr>";
	}
	
	$i=0;
	$full_tot=0;
	$daily_tot=array(null);
	//Printing Performance Data
	foreach ($depts as &$print) {
		$curr_date=0;
		$dept_tot=0;
		$ctr_daily_tot=0;
		$ctr_col=0;
		$sql="SELECT alp.id, alp.prodDate as prodDate, alp.dept as dept, ad.deptName AS deptName, alp.laborHrs as laborHrs, alp.prodHrs as prodHrs, alp.postHrs as postHrs
				FROM `andy_labor_performance` alp LEFT JOIN andy_dept ad ON alp.dept = ad.deptNum where alp.dept='".$depts[$i]."' order by prodDate asc";
		$result=mysqli_query($conn,$sql);
		while ($row=mysqli_fetch_assoc($result)) {
			if ($row['deptName']!='0') {
				//Dept Name
				if ($ctr_col==0) {
					echo "<tr class='fulltop'><td align=center>";
					?>
						<a href='SpecificDept.php?sep_dept=<?echo $row["dept"];?>' target='spec_dept'><?echo $row["dept"];?></a>
						</td><td>
						<a href='SpecificDept.php?sep_dept=<?echo $row["dept"];?>' target='spec_dept'><?echo $row["deptName"];?></a>
					<?
					echo "</td><td>";
				}
				//Report Type
				if ($rptType=='Direct Performance') {
					if ($row['prodDate']==$date[$curr_date]) {
						if ($row['laborHrs']!=0) {
							$pct=round(($row['prodHrs']/$row['laborHrs'])*100, 2);
							$dept_tot+=$pct;
							if ($method_Type==1) {
								echo $dept_tot.'</td><td>';
							} else {
								echo $pct.'</td><td>';
							}
						} else {
							$pct=0;
							if ($method_Type==1) {
								echo $dept_tot.'</td><td>';
							} else {
								echo $pct.'</td><td>';
							}
						}
					} else {
						echo '0</td><td>';
						if ($row['laborHrs']!=0) {
							$pct=round(($row['prodHrs']/$row['laborHrs']) * 100,2);
							$dept_tot+=$pct;
							if (radioMTD=="") {
								echo $pct.'</td><td>';
							} else {
								echo $dept_tot.'</td><td>';
							}
							$daily_tot[$ctr_daily_tot+1]+=$pct;
							$pct=0;
							$daily_tot[$ctr_daily_tot]+=$pct;
							$ctr_daily_tot++;
						} else {
							$pct=0;
							if (radioMTD=="") {
								echo $pct.'</td><td>';
							} else {
								echo $dept_tot.'</td><td>';
							}
							$daily_tot[$ctr_daily_tot+1]+=$pct;
							$ctr_daily_tot++;
						}
						$curr_date++;
						$ctr_col++;
					}
					$curr_date++;
					$daily_tot[$ctr_daily_tot]+=$pct;
					$ctr_daily_tot++;
					$ctr_col++;
				} else {
					if ($row['prodDate']==$date[$curr_date]) {
						$pct=$row[$rptType];
						$dept_tot+=$pct;
						if ($method_Type==1) {
							echo $dept_tot.'</td><td>';
						} else {
							echo $pct.'</td><td>';
						}
					} else {
						echo '0.00</td><td>';
						$pct=round($row[$rptType],2);
						$dept_tot+=$pct;
						if ($method_Type==1) {
							echo $dept_tot.'</td><td>';
						} else {
							echo $pct.'</td><td>';
						}

						$daily_tot[$ctr_daily_tot+1]+=$pct;
						$pct=0;
						if ($method_Type==1) {
							echo $dept_tot.'</td><td>';
						} else {
							echo $pct.'</td><td>';
						}
						$ctr_daily_tot++;			
						$curr_date++;
						$ctr_col++;
					}
					$curr_date++;
					$daily_tot[$ctr_daily_tot]+=$pct;
					$ctr_daily_tot++;
					$ctr_col++;
				}
			}
		}
		//Empty Dates
		if ($ctr_col!=$ctr_date) {
			echo '0</td><td>';
			if ($daily_tot[$ctr_col]=='') {
				$daily_tot[$ctr_col]=0;
			}
		}
		if ($method_Type==0) {
			echo $dept_tot;
			echo "</td></tr>";
			$full_tot+=$dept_tot;
		}
		$i++;	
	}
	//Printing Daily Totals
	echo "<tr><th colspan='2'><center>Total:</center></th>";
	if ($method_Type==0) {
		for ($k=0;$k<$ctr_date;$k++) {
			echo '<td>'.$daily_tot[$k].'</td>';
		}
		echo "<td>".$full_tot."</td>";
		echo "</tr>";
	} else {
		for ($k=0;$k<$ctr_date;$k++) {
			$total_MTD+=$daily_tot[$k];
			echo '<td>'.$total_MTD.'</td>';
		}
		echo "</tr>";
	}
	echo "</table>";
	echo "</table> ";
	echo "<iframe src='' name='spec_dept' width='49%' height='500px' scrolling='no' frameborder='0'></iframe>";
	echo "<iframe src='' name='spec_date' width='49%' height='500px' scrolling='no' frameborder='0'></iframe>";
?>
</body>
</html>