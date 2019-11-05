<!DOCTYPE html>
<html lang="en">
<head>
	<title>
	Andy's Sudoku
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		table td {
			border: 1px solid #ddd;
		}
		table {
			table-layout:auto; 
			width: 1000px;
			height: 1000px;
		}
		table td:nth-child(3), table td:nth-child(6), table td:nth-child(9) {
			border-right: 2px solid black;
		}
		table td:nth-child(1) {
			border-left: 2px solid black;
		}
		tr:nth-child(1), tr:nth-child(4), tr:nth-child(7) { border-top: 2px solid black; }
		tr:nth-child(9) { border-bottom: 2px solid black; }
		.dropdown-submenu {
			position: relative;
		}
		.dropdown-submenu .dropdown-menu {
			top: 0;
			left: 100%;
			margin-top: -1px;
		}
		input {
			text-align: center;
			border:1px solid #ddd;
		}
	</style>
	<script>
	$(document).ready(function(){
	  $('.dropdown-submenu a.test').on("click", function(e){
		$(this).next('ul').toggle();
		e.stopPropagation();
		e.preventDefault();
	  });
	});
	function validate(evt) {
	  var theEvent = evt || window.event;
	  if (theEvent.type === 'paste') {
		  key = event.clipboardData.getData('text/plain');
	  } else {
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode(key);
	  }
	  var regex = /[1-9]|\./;
	  if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	  }
	}
	</script>
</head>
<body>
<?
	while (true) {
		//create grid for sudoku
		$grid=array();
		for ($row=1;$row<=9;$row++) {
			$grid[$row] = array();
			for ($col=1;$col<=9;$col++) {
				$grid[$row][$col]=range(1,9);
			}
		}
		$ctr=0;
		$x_coord=2;
		$finished=false;
		//Loop to go through each element
		for ($row=1;$row<=9;$row++) {
			$y_coord=2;
			if ($row==4) {
				$x_coord=5;
			} else if ($row==7) {
				$x_coord=8;
			}
			for ($col=1;$col<=9;$col++) {
				if ($col==4) {
					$y_coord=5;
				} else if ($col==7) {
					$y_coord=8;
				}
				//Making sure that it is possible to change numbers
				$keys=array_keys($grid[$row][$col]);
				if (count($keys)==0) {
					continue 3;
				}
				//Changing numbers within the grid
				//$grid[$row][$col]=$grid[$row][$col][$keys[mt_rand(0, count($keys)-1)]];
				unique_block($row, $col, $grid, $keys, $x_coord, $y_coord, $ctr);
				if ($y_coord==(-1)) {
					continue 3;
				}
				$index=$grid[$row][$col]-1;
				//Removing the selected number from available numbers
				for ($z=1;$z<=9;$z++) {
					if (is_array($grid[$row][$z])) {
						unset($grid[$row][$z][$index]);
					}
					if (is_array($grid[$z][$col])) {
						unset($grid[$z][$col][$index]);
					}
				}
				//Defining position for the starting row
				if ($row<=3) {
					$r_start=1;
					$R_start=3;
				} else if ($row<=6) {
					$r_start=4;
					$R_start=6;
				} else {
					$r_start=7;
					$R_start=9;
				}
				//Defining position for the start col
				if ($col<=3) {
					$c_start=1;
					$C_start=3;
				} else if ($col<=6) {
					$c_start=4;
					$C_start=6;
				} else {
					$c_start=7;
					$C_start=9;
				}
				//Removing selected numbers from elements
				for (;$r_start<=$R_start;$r_start++) {
					for (;$c_start<=$C_start;$c_start++) {
						if (is_array($grid[$r_start][$c_start])) {
							unset($grid[$r_start][$c_start][$index]);
						}
					}
				}
			}
			if ($row==9) {
				$finished=true;
			}
		}
		if ($finish=true) {
			break;
		}
	}
	echo "<br>";
	print_grid($grid);
	echo http_build_query($grid);
?>	
	<div class="container">                                
	  <div class="dropdown">
		<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Solution
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
		  <li><a tabindex="-1" href="#"><?print_gridfull($grid); ?></a></li>
		</ul>
	  </div>
	</div>			
<?
	function unique_block($row, $col, &$arr, $keys, $x, &$y, &$ctr) {
		$temp=$arr[$row][$col][$keys[mt_rand(0, count($keys)-1)]];
		if ($temp!=$arr[$x][$y] && $temp!=$arr[$x-1][$y-1] && $temp!=$arr[$x-1][$y] && $temp!=$arr[$x-1][$y+1] && $temp!=$arr[$x][$y-1] && $temp!=$arr[$x][$y+1] && $temp!=$arr[$x+1][$y-1] && $temp!=$arr[$x+1][$y] && $temp!=$arr[$x+1][$y+1]) {
			$arr[$row][$col]=$temp;
		} else if ($ctr>=25) {
			$y=(-1);
		} else {
			$ctr++;
			unique_block($row, $col, $arr, $keys, $x, $y, $ctr);
		}
		return;
	}
	//Solved Print Function
	function print_gridfull($grid) {
		echo "<table width='25%' align='center'>";
		for($r=1;$r<=9;$r++) {									//Loops to get through each individual index
			echo "<tr>";
			for ($c=1;$c<=9;$c++) {
				echo "<td align='center'><font size='10'>".$grid[$r][$c]."</font></td>";
			}
			echo "</tr>";
		}
		echo "</table></br>";
		return;	
	}	//print_gridfull()
	//Print Function
	function print_grid($grid) {
		echo "<form><table width='25%' align='center'>";
		for($r=1;$r<=9;$r++) {			//Loops to get through each individual index
			if ($r==3 || $r==6 || $r==9) {
				echo "<tr border-bottom='1px solid black'>";
			} else {
				echo "<tr>";
			}
			for ($c=1;$c<=9;$c++) {
				echo "<td align='center'><font size='10'>";
				if (rand(0,2)!=0) {
					echo "<input type='text' name='num' onkeypress='validate(event)' maxlength='1' size='1'></td>";
				} else {
					echo $grid[$r][$c]."</font></td>";
				}
			}
			echo "</tr>";
		}
		echo "</table></form><br>";
		return;	
	}	//print_grid()
?>
</body>
</html>