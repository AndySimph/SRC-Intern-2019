<!DOCTYPE html>
<html lang="en">
<head>
	<title>
	Andy's Word Search
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
	table {table-layout: fixed;}
	.dropdown-submenu {
		position: relative;
	}
	.dropdown-submenu .dropdown-menu {
		top: 0;
		left: 100%;
		margin-top: -1px;
	}
	mark {
		background-color: yellow;
	}
	</style>
	<script>
	function badword() {
		window.alert("Invalid Characters");
	}
	function longword() {
		window.alert("Too Many Characters");
	}
	$(document).ready(function(){
	  $('.dropdown-submenu a.test').on("click", function(e){
		$(this).next('ul').toggle();
		e.stopPropagation();
		e.preventDefault();
	  });
	});
	</script>
</head>
<body>
	<form id='getword' method='Get' Action='wordsearch.php'>
		Separate multiple words with a comma: word,word<br>Word:
		<input type="text" name="word" maxlength="75">
		<input type="submit" value="Submit"/>
	</form>
<?
	//Variables
	$tablerow=array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19);
	$charset='abcdefghijklmnopqrstuvwxyz';
	$flag=array();
	//Creating the grid variable
	foreach ($tablerow as $key=>$row) {
		for ($i=0;$i<count($tablerow);$i++) {
			$tablecol[$i]=$charset[rand(0, strlen($charset)-1)];
		}
		foreach ($tablecol as $k=>$col) {
			$ret[$row][]=$col;
		}
	}
	//Requested Word(s)
	$base=explode(",", $_GET['word']);
	if (count($base)>1) {
		//For loop to run through each word
		for ($j=0;$j<count($base);$j++) {
			//Getting the word and filtering it
			$str=preg_replace('/\s+/', '', $base[$j]);
			$str=strtolower($str);
			$reqword=str_split($str);
			//To ensure the word is something
			if ($str!=null) {
				if (strlen($str)<10) {
					//To check for only letters
					if (!preg_match('/[^A-Za-z]/', $str)) {
						//Placing requested word in grid
						placeword($ret, $reqword, count($reqword), $flag);
					} else {
						echo "</body><body onload='badword()'>";
					}
				} else {
					echo "</body><body onload='longword()'>";
				}
			}
		}
	} else {
		//Getting the word and filtering it
		$str=preg_replace('/\s+/', '', $base[0]);
		$str=strtolower($str);
		$reqword=str_split($str);
		//To ensure the word is something
		if ($str!=null) {
			if (strlen($str)<10) {
				//To check for only letters
				if (!preg_match('/[^A-Za-z]/', $str)) {
					//Placing requested word in grid
					placeword($ret, $reqword, count($reqword), $flag);
				} else {
					echo "</body><body onload='badword()'>";
				}
			} else {
				echo "</body><body onload='longword()'>";
			}
		}
	}
	//Printing grid
		echo "<table><tr><td valign='middle'>";
		for ($w=0;$w<count($base);$w++) {
			echo $base[$w]."<br>";
		}
		echo "</td><td>";
		print_grid($tablerow, $tablecol, $ret);
		echo "</td></tr></table>";
		//Solution Dropdown
		?>
		<div class="container">                                
		  <div class="dropdown">
			<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Solution
			<span class="caret"></span></button>
			<ul class="dropdown-menu">
			  <li><a tabindex="-1" href="#"><?solve($tablerow, $tablecol, $ret, $flag); ?></a></li>
			</ul>
		  </div>
		</div>		
		<?
	return;
?>
<?
	//Function to place requested word
	function placeword(&$grid, $word, $len, &$check) {
		//Getting Variables
		$r=rand(0,19);
		$c=rand(0,19);
		$dir=rand(1,8);
		$match=false;
		//Inputting word
		if ($dir==1) {
			//Loop to check for word overlapping
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)($r.",".($c+$d)))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				//Checking if there is room for the word
				if (($c+($len-1))<=19) {
					//Loop to insert the word
					for ($i=0;$i<$len;$i++) {						
						$grid[$r][$c+$i]=$word[$i];
						array_push($check, (string)($r.",".($c+$i)));
					}
				} else {
				//Recursion if the word doesn't fit										Same method for the other 7 directions
				placeword($grid, $word, $len, $check);						
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==2) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)(($r+$d).",".($c+$d)))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				if ((($r+($len-1))<=19) && (($c+($len-1))<=19)) {
					for ($i=0;$i<$len;$i++) {
						$grid[$r+$i][$c+$i]=$word[$i];
						array_push($check, (string)(($r+$i).",".($c+$i)));
					}
				} else {
					placeword($grid, $word, $len, $check);
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==3) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)(($r+$d).",".$c))) {
						$match=true;
					}
				}
			}
		if ($match==false) {
			if (($r+($len-1))<=19) {
				for ($i=0;$i<$len;$i++) {
					$grid[$r+$i][$c]=$word[$i];
					array_push($check, (string)(($r+$i).",".$c));
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==4) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)(($r+$d).",".($c-$d)))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				if ((($r+($len-1))<=19) && (($c-($len-1))>=0)) {
					for ($i=0;$i<$len;$i++) {
						$grid[$r+$i][$c-$i]=$word[$i];
						array_push($check, (string)(($r+$i).",".($c-$i)));
					}
				} else {
					placeword($grid, $word, $len, $check);
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==5) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)($r.",".($c-$d)))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				if (($c-($len-1))>=0) {
					for ($i=0;$i<$len;$i++) {
						$grid[$r][$c-$i]=$word[$i];
						array_push($check, (string)($r.",".($c-$i)));
					}
				} else {
					placeword($grid, $word, $len, $check);
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==6) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)(($r-$d).",".($c-$d)))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				if ((($c-($len-1))>=0) && (($r-($len-1))>=0)) {
					for ($i=0;$i<$len;$i++) {
						$grid[$r-$i][$c-$i]=$word[$i];
						array_push($check, (string)(($r-$i).",".($c-$i)));
					}
				} else {
					placeword($grid, $word, $len, $check);
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==7) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)(($r-$d).",".$c))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				if (($r-($len-1))>=0) {
					for ($i=0;$i<$len;$i++) {
						$grid[$r-$i][$c]=$word[$i];
						array_push($check, (string)(($r-$i).",".$c));
					}
				} else {
					placeword($grid, $word, $len, $check);
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		} else if ($dir==8) {
			for ($k=0;$k<count($check);$k++) {
				for ($d=0;$d<$len;$d++) {
					if ($check[$k]==((string)(($r-$d).",".($c+$d)))) {
						$match=true;
					}
				}
			}
			if ($match==false) {
				if ((($r-($len-1))>=0) && (($c+($len-1))<=19)) {
					for ($i=0;$i<$len;$i++) {
						$grid[$r-$i][$c+$i]=$word[$i];
						array_push($check, (string)(($r-$i).",".($c+$i)));
					}
				} else {
					placeword($grid, $word, $len, $check);
				}
			} else {
				placeword($grid, $word, $len, $check);
			}
		}
		return;
	}	//placeword()
	//Print Function
	function print_grid($col, $row, $grid) {
		echo "<table width='25%' align='center'>";
		for($r=0;$r<count($row);$r++) {									//Loops to get through each individual index
			echo "<tr>";
			for ($c=0;$c<count($col);$c++) {
				echo "<td align='center'>".$grid[$r][$c]."</td>";
			}
			echo "</tr>";
		}
		return;	
	}	//print_grid()
	//Solve Function
	function solve($col, $row, $grid, $check) {
		echo "<table width='25%' align='center'>";
		for($r=0;$r<count($row);$r++) {									//Loops to get through each individual index
			echo "<tr>";
			for ($c=0;$c<count($col);$c++) {
				if (in_array(((string)($r.",".$c)), $check)) {			//Checking if it is a part of a hidden word, and highlighting it
					echo "<td align='center' bgcolor='yellow'>".$grid[$r][$c]."</td>";
				} else {
					echo "<td align='center'>".$grid[$r][$c]."</td>";
				}
			}
			echo "</tr>";
		}
		return;	
	}	//solve()
?>
</body>
</html>