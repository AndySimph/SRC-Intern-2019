<!DOCTYPE html>
<html lang="en">
<head>
	<title>
	Andy's Tic-Tac-Toe
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="Content-Language" content="en-us" />
<style>
	.header {
		background-color: #f1f1f1;
		margin: auto;
		padding: 1px;
		text-align: center;
	}
	
	.rotateimg90 {
		transform: rotate(87deg);
	}
	
	.rotateimg132 {
		transform: rotate(132deg);
	}
	
	.rotateimg41 {
		transform: rotate(41.5deg);
	}
	
	.rotateimg10 {
		transform: rotate(356deg);
	}
	
	.line1 {
		position:absolute;
		top: -15px;
		right: 280px;
		z-index: 1;
	}
	
	.line2 {
		position:absolute;
		top: 250px;
		right: 280px;
		z-index: 1;
	}
	
	.line3 {
		position:absolute;
		top: 515px;
		right: 275px;
		z-index: 1;
	}
	
	.line4 {
		position:absolute;
		top: 252px;
		right: 536px;
		z-index: 1;
	}
	
	.line5 {
		position:absolute;
		top: 252px;
		right: 276px;
		z-index: 1;
	}
	
	.line6 {
		position:absolute;
		top: 252px;
		right: 15px;
		z-index: 1;
	}
	
	.line7 {
		position:absolute;
		top: 150px;
		right: 170px;
		z-index: 1;
	}
	
	.line8 {
		position:absolute;
		top: 150px;
		right: 170px;
		z-index: 1;
	}
	
	#grad1 {
		height: 100px;
		width:30%;
		background-color: #1fc8db;   
		background-image: linear-gradient(to bottom right, blue, red);
		color:white;
		opacity:0.95;
		border-radius: 25px
	}
	
	#clockspot {
		border-radius: 25px;
		background-color: white; 
		color: black; 
		border: 2px solid #008CBA;
		padding: 10px 10px;
		font-size: 20px;
		text-align: center;
	}
	
	td.board {
		border-radius: 25px;
		background-color: #eaeaea;
	}
	
	td.log {
		border-radius: 25px;
		background-color: #eaeaea;
	}
	
	a.unocc:hover {
		<?
			$occupied=$_GET['move'];
			$ctr=count($occupied);
			if ($ctr%2==0) {
				echo "content: url(https://cdn2.iconfinder.com/data/icons/letters-and-numbers-1/32/lowercase_letter_x_blue-256.png);";
			} else {
				echo "content: url(https://cdn2.iconfinder.com/data/icons/letters-and-numbers-1/32/lowercase_letter_o_red-256.png);";
			}
		?>
		opacity: 0.5;
	}
	
	h1 {
		text-align: center;
		border-radius: 50px;
		box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
	}
	
	.button {
		background-color: #4CAF50;
		border: none;
		color: white;
		padding: 25px 50px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 20px;
		margin: 10px 5px;
		transition-duration: 0.4s;
		cursor: pointer;
	}
	.button1 {
		border-radius: 25px;
		background-color: white; 
		color: black; 
		border: 2px solid #4CAF50;
	}
	.button1:hover {
		background-color: #4CAF50;
		color: white;
	}
	
	.column {
		float: left;
		width: 45%;
		padding: 15px;
	}

	.row:after {
		content: "";
		display: table;
		clear: both;
	}

	@media screen and (max-width:600px) {
		.column {
			width: 100%;
		}
	}
	input[type=submit] {
		border-radius: 5px;
		background-color: #4CAF50;
		border: none;
		color: white;
		padding: 10px 20px;
		text-decoration: none;
		margin: 4px 2px;
		cursor: pointer;
	}
</style>
<script>
	var startDateJS=new Date();	//client date
	var startDatePHP=new Date(<?=time();?>000);	//server date

	function updateClock() {
		var currentDateJS=new Date();	//current date (client)
		var timeOffset=currentDateJS.valueOf() - startDateJS.valueOf();
		var clockDate=new Date( startDatePHP.valueOf()+timeOffset );
		var clockHours=clockDate.getHours();
		var clockMins=clockDate.getMinutes();
		var clockSecs=clockDate.getSeconds();
		var clockAMPM=(clockHours>11) ? "PM" : "AM";

		if (clockHours>12)clockHours-=12;
		if (clockHours==0)clockHours=12;
		if (clockMins<10)clockMins=String("0"+clockMins);
		if (clockSecs<10)clockSecs=String("0"+clockSecs);
	
		document.clockform.clockspot.value=clockHours+":"+clockMins+":"+clockSecs+" "+clockAMPM;
		setTimeout("updateClock()",1000);
	}

	window.onload=function() {
		updateClock();
	}
	
	function samename() {
		window.alert("Player names cannot be the same!");
	}
	
	function noname() {
		window.alert("Player names cannot be the empty!");
	}
</script>
</head>
<body>
<div class='header' id='grad1'><h2 style='font-size:50px'> Tic-Tac-Toe </h2></div>
<?
	//Variables
	$x_mark=array();
	$o_mark=array();
	$x_win=false;
	$o_win=false;
	$win_type=0;
	$filename="log.txt";
	$rewrite=file($filename);
	$filectr=count($rewrite);
	$log=fopen("log.txt", "c+");
	$x_user=$_GET['playerx'];
	$o_user=$_GET['playero'];
	
	if ($x_user===null && $o_user===null) {
		echo "<div class='column'><h1 align='center' style='background-color:SlateBlue;'>Enter User Names</h1><br>";
		echo "<form><table align='center' padding='30px'><tr>";
		echo "<td align='center'>Player-X:<br><input type='text' name='playerx' maxlength='10'></td>";
		echo "<td align='center'>Player-O:<br><input type='text' name='playero' maxlength='10'></td>";
		echo "<td><input type='submit' value='Submit'></td></table></form>";
		echo "</div>";
		echo win_print($empty, $empty2);
	} else if ($x_user==$o_user) {
		echo "</body><body onload='samename()'>";
		echo "<div class='column'><h1 align='center' style='background-color:SlateBlue;'>Enter User Names</h1><br>";
		echo "<form><table align='center' padding='30px'><tr>";
		echo "<td align='center'>Player-X:<br><input type='text' name='playerx' maxlength='10' value='".$x_user."'></td>";
		echo "<td align='center'>Player-O:<br><input type='text' name='playero' maxlength='10' value='".$o_user."'></td>";
		echo "<td><input type='submit' value='Submit'></td></table></form>";
		echo "</div>";
		echo win_print($empty, $empty2);
	} else if ($x_user==='' || $o_user==='' || ($x_user==='' && $o_user==='')) {
		echo "</body><body onload='noname()'>";
		echo "<div class='column'><h1 align='center' style='background-color:SlateBlue;'>Enter User Names</h1><br>";
		echo "<form><table align='center' padding='30px'><tr>";
		echo "<td align='center'>Player-X:<br><input type='text' name='playerx' maxlength='10' value='".$x_user."'></td>";
		echo "<td align='center'>Player-O:<br><input type='text' name='playero' maxlength='10' value='".$o_user."'></td>";
		echo "<td><input type='submit' value='Submit'></td></table></form>";
		echo "</div>";
		echo win_print($empty, $empty2);
	} else {
		//To check for a winner
		for ($m=0;$m<$ctr;$m++) {
			if (($m%2)==0) {
				if (isset($occupied[$m-1])) {
					array_push($o_mark, $occupied[$m-1]);
				}
			}
		}
		for ($m=0;$m<$ctr;$m++) {
			if (($m%2)!=0) {
				if (isset($occupied[$m-1])) {
					array_push($x_mark, $occupied[$m-1]);
				}
			}
		}
		if ((($ctr)%2)==0) {
			array_push($o_mark,$occupied[$ctr-1]);
		} else {
			array_push($x_mark,$occupied[$ctr-1]);
		}
		if ($ctr>=5) {
			if ((($ctr)%2)==0) {
				win_Check($o_mark, $o_win, $win_type);
			} else {
				win_Check($x_mark, $x_win, $win_type);
			}
		}
		
		//Printing the table
		if ($x_win==true) {
			echo "<div class='column'><h1 align='center' style='background-color:LawnGreen;'>".ucfirst($x_user)." is the Winner!</h1><br>";
			echo "<table align='center'><tr><td><a href='tictactoe.php' class='button button1'>Reset Game</a></td>";
			echo "<td><form name='clockform'><input type='text' id='clockspot' name='clockspot' size='15' value=''0/></form></td></tr>";
			echo "<tr><td colspan='2' rowspan='2' class='log' align='center'><b>Previous Games</b><br>";
			$txt=date('M\, d h:i:s')."|&|".$x_user."|&|".$o_user."|&|".$x_user."\n";
			echo "<table cellpadding='10' style='text-align: center;'><tr><th>Date</th><th>Player-X</th><th>Player-O</th><th>Winner</th></tr>";
			if ($filectr>=10) {
				for ($i=0;$i<9;$i++) {
					$rewrite[$i]=$rewrite[$i+1];
				}
				$rewrite[9]=$txt;
			}
			$rewrite[$filectr]=$txt;
			foreach ($rewrite as $line) {
				fwrite($log, $line);
			}
			fclose($log);
			$log=fopen("log.txt", "r");
			for ($i=0;$i<10;$i++) {
				$row=explode("|&|", fgets($log));
				echo "<tr>";
				for ($j=0;$j<4;$j++) {
					echo "<td>".ucfirst($row[$j])."</td>";
				}
				echo "</tr>";
			}
			echo "</table></td></tr></table></td></tr></table></div>";
			win_print($occupied, $win_type);
		} else if ($o_win==true) {
			echo "<div class='column'><h1 align='center' style='background-color:LawnGreen;'>".ucfirst($o_user)." is the Winner!</h1><br>";
			echo "<table align='center'><tr><td><a href='tictactoe.php' class='button button1'>Reset Game</a></td>";
			echo "<td><form name='clockform'><input type='text' id='clockspot' name='clockspot' size='15' value=''0/></form></td></tr>";
			echo "<tr><td colspan='2' rowspan='2' class='log' align='center'><b>Previous Games</b><br>";
			$txt=date('M\, d h:i:s')."|&|".$x_user."|&|".$o_user."|&|".$o_user."\n";
			echo "<table cellpadding='10' style='text-align: center;'><tr><th>Date</th><th>Player-X</th><th>Player-O</th><th>Winner</th></tr>";
			if ($filectr>=10) {
				for ($i=0;$i<9;$i++) {
					$rewrite[$i]=$rewrite[$i+1];
				}
				$rewrite[9]=$txt;
			}
			$rewrite[$filectr]=$txt;
			foreach ($rewrite as $line) {
				fwrite($log, $line);
			}
			fclose($log);
			$log=fopen("log.txt", "r");
			for ($i=0;$i<10;$i++) {
				$row=explode("|&|", fgets($log));
				echo "<tr>";
				for ($j=0;$j<4;$j++) {
					echo "<td>".ucfirst($row[$j])."</td>";
				}
				echo "</tr>";
			}
			echo "</table></td></tr></table></td></tr></table></div>";
			win_print($occupied, $win_type);
		} else if ($ctr==9) {
			echo "<div class='row'><div class='column'><h1 style='background-color:SlateBlue;'>Tie, there is no winner!</h1><br>";
			echo "<table align='center'><tr><td><a href='tictactoe.php' class='button button1'>Reset Game</a></td>";
			echo "<td><form name='clockform'><input type='text' id='clockspot' name='clockspot' size='15' value=''0/></form></td></tr>";
			echo "<tr><td colspan='2' rowspan='2' class='log' align='center'><b>Previous Games</b><br>";
			$txt=date('M\, d h:i:s')."|&|".$x_user."|&|".$o_user."|&|Tie\n";
			echo "<table cellpadding='10' style='text-align: center;'><tr><th>Date</th><th>Player-X</th><th>Player-O</th><th>Winner</th></tr>";
			if ($filectr>=10) {
				for ($i=0;$i<9;$i++) {
					$rewrite[$i]=$rewrite[$i+1];
				}
				$rewrite[9]=$txt;
			}
			$rewrite[$filectr]=$txt;
			foreach ($rewrite as $line) {
				fwrite($log, $line);
			}
			fclose($log);
			$log=fopen("log.txt", "r");
			for ($i=0;$i<10;$i++) {
				$row=explode("|&|", fgets($log));
				echo "<tr>";
				for ($j=0;$j<4;$j++) {
					echo "<td>".ucfirst($row[$j])."</td>";
				}
				echo "</tr>";
			}
			echo "</table></td></tr></table></td></tr></table></div>";
			table_print($occupied, $x_user, $o_user);
		} else {
			//Header to see whos turn it is
			if ($ctr%2==0) {
				echo "<div class='column'><h1 style='background-color: #00ccff;'> It is ".ucfirst($x_user)."'s Turn </h1><br>";
				echo "<table align='center'><tr><td><a href='tictactoe.php' class='button button1'>Reset Game</a></td>";
				echo "<td><form name='clockform'><input type='text' id='clockspot' name='clockspot' size='15' value=''0/></form></td></tr>";
				echo "<tr><td colspan='2' rowspan='2' class='log' align='center'><b>Previous Games</b><br>";
				echo "<table cellpadding='10' style='text-align: center;'><tr><th>Date</th><th>Player-X</th><th>Player-O</th><th>Winner</th></tr>";
				for ($i=0;$i<10;$i++) {
				$row=explode("|&|", fgets($log));
				echo "<tr>";
				for ($j=0;$j<4;$j++) {
					echo "<td>".ucfirst($row[$j])."</td>";
				}
				echo "</tr>";
			}
				echo "</table></td></tr></table></td></tr></table></div>";
			} else {
				echo "<div class='column'><h1 style='background-color: #ff3333;'> It is ".ucfirst($o_user)."'s Turn </h1><br>";
				echo "<table align='center'><tr><td><a href='tictactoe.php' class='button button1'>Reset Game</a></td>";
				echo "<td><form name='clockform'><input type='text' id='clockspot' name='clockspot' size='15' value=''0/></form></td></tr>";
				echo "<tr><td colspan='2' rowspan='2' class='log' align='center'><b>Previous Games</b><br>";
				echo "<table cellpadding='10' style='text-align: center;'><tr><th>Date</th><th>Player-X</th><th>Player-O</th><th>Winner</th></tr>";
				for ($i=0;$i<10;$i++) {
				$row=explode("|&|", fgets($log));
				echo "<tr>";
				for ($j=0;$j<4;$j++) {
					echo "<td>".ucfirst($row[$j])."</td>";
				}
				echo "</tr>";
			}
				echo "</table></td></tr></table></td></tr></table></div>";
			}
			table_print($occupied, $x_user, $o_user);
		}
	}
	fclose($log);
?>
<?
Function table_print($used, $player1, $player2) {
	echo "<div class='column'><table align='center' border='0'><tr>";
	for ($i=0;$i<9;$i++) {
		$match=false;
		
		//To check if it's after the first move
		if (isset($used)) {
			//To place a marker in an occupied frame
			for ($k=0;$k<count($used);$k++) {
				if ($used[$k]==$i) {
					if (($k%2)==0) {
						echo "<td class='board'><img disabled src='https://cdn2.iconfinder.com/data/icons/letters-and-numbers-1/32/lowercase_letter_x_blue-256.png'></td>";
					} else {
						echo "<td class='board'><img disabled src='https://cdn2.iconfinder.com/data/icons/letters-and-numbers-1/32/lowercase_letter_o_red-256.png'></td>";
					}
					$match=true;
				}
			}	
			//To place an unoccupied frame
			if ($match==false) {
				echo "<td class='board'><a href='tictactoe.php?playerx=".$player1."&playero=".$player2."&";
				for ($j=0;$j<=count($used);$j++) {
					echo "move%5B%5D=".$used[$j];
					if ($j<=(count($used)-1)) {
						echo "&";
					}
				}
				echo $i."' class='unocc'><img src='https://cdn0.iconfinder.com/data/icons/feather/96/empty-256.png'></a></td>";
			}
		} else {
			echo "<td class='board'><a href='tictactoe.php?playerx=".$player1."&playero=".$player2."&move%5B%5D=".$i."' class='unocc'><img src='https://cdn0.iconfinder.com/data/icons/feather/96/empty-256.png'></a></td>";
		}
		if ((($i+1)%3)==0) {
			if (($i+1)==9) {
				echo "</tr";
			} else {
				echo "</tr><tr>"; 
			}
		}
	}
	echo "</table></div>";
	return;
}

Function win_Check($arr, &$win, &$dir) {
	if (in_array(0, $arr)) {
		if (in_array(1, $arr)) {
			if (in_array(2, $arr)) {
				$win=true;
				$dir=102;
			}
		}
		if (in_array(3, $arr)) {
			if (in_array(6, $arr)) {
				$win=true;
				$dir=306;
			}
		}
		if (in_array(4, $arr)) {
			if (in_array(8, $arr)) {
				$win=true;
				$dir=408;
			}
		}
	}
	if (in_array(4, $arr)) {
		if (in_array(3, $arr)) {
			if (in_array(5, $arr)) {
				$win=true;
				$dir=435;
			}
		}
		if (in_array(1, $arr)) {
			if (in_array(7, $arr)) {
				$win=true;
				$dir=417;
			}
		}
		if (in_array(2, $arr)) {
			if (in_array(6, $arr)) {
				$win=true;
				$dir=426;
			}
		}
	}
	if (in_array(8, $arr)) {
		if (in_array(7, $arr)) {
			if (in_array(6, $arr)) {
				$win=true;
				$dir=876;
			}	
		}
		if (in_array(5, $arr)) {
			if (in_array(2, $arr)) {
				$win=true;
				$dir=852;
			}	
		}	
	}
	return;
}

Function win_print($used, $dir) {
	echo "<div class='column'><table align='center' border='0'><tr>";
	if ($dir==102) {
		echo "<img class='rotateimg41 line1' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==435) {
		echo "<img class='rotateimg41 line2' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==876) {
		echo "<img class='rotateimg41 line3' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==306) {
		echo "<img class='rotateimg132 line4' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==417) {
		echo "<img class='rotateimg132 line5' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==852) {
		echo "<img class='rotateimg132 line6' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==408) {
		echo "<img class='rotateimg90 line7' width='43%' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} else if ($dir==426) {
		echo "<img class='rotateimg10 line8' width='43%' src='https://ui-ex.com/images600_/line-svg-solid-1.png'/>";
	} 
	for ($i=0;$i<9;$i++) {
		$match=false;
		//To place a marker in an occupied frame
		for ($k=0;$k<count($used);$k++) {
			if ($used[$k]==$i) {
				if (($k%2)==0) {
					echo "<td class='board'><img class='occupied' src='https://cdn2.iconfinder.com/data/icons/letters-and-numbers-1/32/lowercase_letter_x_blue-256.png'></td>";
				} else {
					echo "<td class='board'><img class='occupied' src='https://cdn2.iconfinder.com/data/icons/letters-and-numbers-1/32/lowercase_letter_o_red-256.png'></td>";
				}
				$match=true;
			}
		}
		//To place an unoccupied frame
		if ($match==false) {
			echo "<td class='board'><img disabled src='https://cdn0.iconfinder.com/data/icons/feather/96/empty-256.png'></td>";
		}
		if ((($i+1)%3)==0) {
			if (($i+1)==9) {
				echo "</tr";
			} else {
				echo "</tr><tr>"; 
			}
		}
	}
	echo "</table></div>";
	return;
}
?>
</body>
</html>