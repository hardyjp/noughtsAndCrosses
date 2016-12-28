<?php
class NoughtsCrosses
{
	public $xWins, $oWins, $draws = 0;

	function get_aggregate_results()
    {
		require_once 'login.php';
		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) die($conn->connect_error);
		$query  = "SELECT result, COUNT(result) FROM results GROUP BY result ORDER BY result DESC";
		$result = $conn->query($query);
		if (!$result) die($conn->error);
		
		$rows = $result->num_rows;

		for ($j = 0 ; $j < $rows ; ++$j)
		{
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if($row['result'] == "D") {
			echo "Draws: " . $row['COUNT(result)'] . " \n";
			} else {
			echo $row['result'] . " wins: " . $row['COUNT(result)'] . " \n";
			}
		}

		$result->close();
		$conn->close();
	}

	function calculate_winners()
    {
		require_once 'login.php';
		$conn = new mysqli($hn, $un, $pw, $db);
		if ($conn->connect_error) die($conn->connect_error);
		$x = 0;
		while (FALSE !== ($line = fgets(STDIN))) {
			$row[$x] = array($line[0],$line[1],$line[2]);
			if($x < 2){
				++$x;
			} else {
				$square1 = (string)$row[0][0];
				$square2 = (string)$row[0][1];
				$square3 = (string)$row[0][2];
				$square4 = (string)$row[1][0];
				$square5 = (string)$row[1][1];
				$square6 = (string)$row[1][2];
				$square7 = (string)$row[2][0];
				$square8 = (string)$row[2][1];
				$square9 = (string)$row[2][2];
				// eight possible winning lines, otherwise a draw
				if($square1 == $square2 && $square1 == $square3){
					$result = $square1;
				} elseif($square1 == $square4 && $square1 == $square7){
					$result = $square1;
				} elseif($square1 == $square5 && $square1 == $square9){
					$result = $square1;
				} elseif($square2 == $square5 && $square2 == $square8){
					$result = $square2;
				} elseif($square3 == $square5 && $square3 == $square7){
					$result = $square3;
				} elseif($square3 == $square6 && $square3 == $square9){
					$result = $square3;
				} elseif($square4 == $square5 && $square4 == $square6){
					$result = $square4;
				} elseif($square7 == $square8 && $square7 == $square9){
					$result = $square7;
				} else {
					$result = "D";
				}
				
				switch($result):
					case "X":
						++$this->xWins;
						break;
					case "O":
						++$this->oWins;
						break;
					default:
						++$this->draws;
				endswitch;
				
				$insert = "INSERT INTO results(square1, square2, square3, square4, square5, square6, square7, square8, square9, result)" . 
				"VALUES('$square1','$square2','$square3','$square4','$square5','$square6','$square7','$square8','$square9','$result')";
				$result = $conn->query($insert);
				if (!$result) die($conn->error);
				$x = 0;
			}			
		}
	}

	function get_results()
    {
		echo "X wins: " . $this->xWins . " \n";
		echo "O wins: " . $this->oWins . " \n";
		echo "Draws: " . $this->draws . " \n";
	}
}

?>