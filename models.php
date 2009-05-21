<?php

class Board {

  var $layout;
  var $dog_img = 'img/dog.png';
  var $cat_img = 'img/cat.png';
  var $turn    = 'dog';
  var $rows    = 0;
  var $columns = 0;
  var $x = "<img src='img/dog.jpg' alt='pic of dog' class='move_button'/>";
  var $o = "<img src='img/cat.jpg' alt='pic of cat' class='move_button'/>";
  var $isGameOver = FALSE;
  var $a_winning_spot;
  var $a_winning_array;
	

  function __construct($rows, $columns, $s_layout='', $initial_layout=null) {
	 // initialize board
	 // let x == dog
	 // let o == cat
	 $x = $this->x;
	 $o = $this->o;

	 if (($rows >= 4) && ($columns >= 4) && (!is_array($initial_layout))) {
		$this->rows = $rows;
		$this->columns = $columns;
	 } else {
		$this->rows = 6;
		$this->columns = 7;
	 }
	 
	 
	 if (($s_layout) && (!is_array($initial_layout))) {
		$this->layout = unserialize($s_layout);
	} else if (is_array($initial_layout)) {
		$this->layout = array (
                     array (" ", " ", " "," ", " ", " ", " "),
                     array (" ", " ", " "," ", " ", " ", " "),
                     array (" ", " ", " "," ", " ", " ", " "),
                     array (" ", " ", " "," ", " ", " ", " "),
                     array (" ", " ", "$o"," ", "$x", "$o", " "),
                     array (" ", "$x", "$x","$o", "$o", "$x", "$o"),
                );
         } else {
		for($i = 0; $i < $rows; $i++) {
		  for($j = 0; $j < $columns; $j++) {
			 $tmp[$j] = ' ';
		  }
		  $this->layout[] = $tmp;
		}
		

	 }
  }

	function calculateWinningBoxes() {
		if (!is_array($this->a_winning_spot)) {
			return FALSE;
		} 
		if (is_array($this->a_winning_spot['o'])) { $winner = 'o'; }
		if (is_array($this->a_winning_spot['x'])) { $winner = 'x'; }
		if ($this->a_winning_spot['how'] == 'columns') {
			$y = $this->a_winning_spot[$winner][0];
			$x = $this->a_winning_spot[$winner][1];
			for ($i = 0; $i < 4; $i++) {
				$this->a_winning_array["$y:$x"] = 1;
				$x++;
			}
		}
		if ($this->a_winning_spot['how'] == 'rows') {
			$y = $this->a_winning_spot[$winner][2];
			$x = $this->a_winning_spot[$winner][0];
			for ($i = 0; $i < 4; $i++) {
				$this->a_winning_array["$y:$x"] = 1;
				$y--;
			}
		}

		if ($this->a_winning_spot['how'] == 'gravely') {

			//
			// if the win starts at c4, r1
			// this works if we start in a corner
			// gravely works by going left to right, up to down
			$y = $this->a_winning_spot[$winner][0];
			$x = $this->a_winning_spot[$winner][1];

			for ($i = 0; $i < 4; $i++) {
				$this->a_winning_array["$y:$x"] = 1;
				$y++;
				$x++;
			}
		}

		if ($this->a_winning_spot['how'] == 'acutely') {
			$y = $this->a_winning_spot[$winner][1];
			$x = $this->a_winning_spot[$winner][0];
			for ($i = 0; $i < 4; $i++) {
				$this->a_winning_array["$y:$x"] = 1;
				$y--;
				$x++;
			}
		}
	}

  function pick_first() {
	 $i = rand(0,1);
	 if ($i == 0) {
		$this->turn = 'cat';
	 }
	 if ($i == 1) {
		$this->turn = 'dog';
	 }
  }

  function print_layout() {
	 prethis($this->layout);
  }

  function update() {
	 // update array
	 $column = (int) $_POST["{$_POST['s_turn']}_move"];
	 // put at bottom of array
	 $i_key = $this->rows - 1; // number of rows
	 $i_sent = 1; 
	 while ($i_sent > 0) {
		if ($this->layout[$i_key][$column] == ' ') {
		  $this->layout[$i_key][$column] = "<img src='img/{$_POST['s_turn']}.jpg' alt='pic of {$_POST['s_turn']}' class='move_button'/>";
		  $i_sent = 0;
		}
		if ($i_key == 0) { $i_sent = 0; }
		$i_key--;
	 }
	 if ($_POST['s_turn'] == 'dog') { $this->turn = 'cat'; } 
	 if ($_POST['s_turn'] == 'cat') { $this->turn = 'dog'; }
  }

  function display() {

	 $columns = $this->columns;
	 $s_display .= "<table id='theboard' border='1'>\n";
	 $s_display .= "<tr>\n";

	 // top row logic
	 for ($i = 0; $i < $columns; $i++) {
		$s_display .= "<td>";
		if (IsColumnFilled($this->rows, $i, $this->layout)) {
		  $s_display .= "<img src='img/{$this->turn}.jpg' alt='pic of {$this->turn}' height='50' width='50'/>";
		} else if ($this->isGameOver == TRUE) {
		  $s_display .= "<img src='img/{$this->turn}.jpg' alt='pic of {$this->turn}' height='50' width='50'/>";	
		} else {
		  $s_display .= "<input type='image' name='{$this->turn}_move' src='img/{$this->turn}.jpg' value='$i' class='move_button'/>";
		}
		$s_display .= "</td>\n";
	 }
	 $s_display .= "</tr>\n";

	 $y = 0; // columns
	 $x = 0; // rows
		foreach($this->layout as $row) {
			$s_display .= "<tr>\n";
			foreach($row as $item) {
				if ($this->a_winning_array["$y:$x"] == 1) {
					$s_display .= "<td class='winningbox'>\n";
				} else {
					$s_display .= "<td>\n";
				}
				$s_display .= $item; 
				$s_display .= "</td>\n";
				$y++;
			}
			$s_display .= "</tr>\n";
			$y = 0;
			$x++;
		}

	 $s_display .= "</table>\n";
	 return $s_display;

  }

  // TODO: 
  // 1) start saving the actual array positions where the win happened
  // 2) use that to highlight the winning spot
  // 3) save board size
  function scanRowsForWin() {
	
		for($y = 0; $y < $this->rows; $y++) {
			$a_winning_set = findWinner($this->layout[$y], $y, $this->x, $this->o);
		
			if (is_array($a_winning_set)) {	
				$this->isGameOver = TRUE;
				// add how
				$a_winning_set['how'] = 'rows';
				$this->a_winning_spot = $a_winning_set;
	    	}
		}
  }
  
  function scanColumnsForWin() {
	// turn columns into rows
	// use the code from $board->update();
	$i_key = $this->rows - 1; // number of rows
	// turn column into an array
	$a_tmp_column  = array();
	$a_winning_set = array();
	for($i = 0; $i < $this->columns; $i++) {
		for($j = 0; $j < $this->rows; $j++) {
			$a_tmp_column[] = $this->layout[$j][$i];
		}
		// winnar check here
		$a_winning_set = findWinner($a_tmp_column, $i, $this->x, $this->o);
		if (is_array($a_winning_set)) {
			$this->isGameOver = TRUE;
			$a_winning_set['how'] = 'columns';
			$this->a_winning_spot = $a_winning_set;
		}
		$a_tmp_column = array();
	}
  }

	function scanDiagonalsAcutelyForWin() {
		$a_tmp_column  = array();
		$a_winning_set = array();
		$start_rows    = matchLength() - 1;             // should be 3
		$end_rows      = $this->rows - 1;
		$start_cols    = 0;
		$end_cols      = $this->columns - 1;
		$max_cols      = matchLength()  - 1;
		$y             = $start_cols;
		$x             = $start_rows;
		$i_max         = $this->rows * $this->columns;
		$outer_x       = $x;
		$outer_y       = $y;
		$a_coords      = array();                       // an array of coords

		$i = 0;
		$j = 0;
		while($outer_y <= matchLength()) {
			while ( ($x >= 0) && 
				($outer_y <= $max_cols) && ($y <= $end_cols) ) 
			{
				
				if ($j == 0) {
					$s_start = "start_y = $y start_x = $x<br/>\n";
					$i_start_x = $x;
					$i_start_y = $y;
				}
				$s_end = "end_y = $y end_x = $x<br/>\n";
				$i_end_x = $x;
				$i_end_y = $y;
				$a_tmp_column[] = $this->layout[$y][$x];
				$a_coords[] = "$y:$x\n";
				$y++;
				$x--;
				$j++;
			}
			$j = 0;
			$a_winning_set = findWinner($a_tmp_column, $i, $this->x, $this->o, 'acutely');
			if (is_array($a_winning_set)) {

				$p_limit = count($a_tmp_column);
				for ($p = 0; $p < $p_limit; $p++) {
					if (strlen($a_tmp_column[$p]) > 1) {
						$i_pad = $p;
						break;
					}
				}

				$i_diff_x = $i_end_x - $i_start_x ;
				$i_diff_y = $i_end_y - $i_start_y ;
				$i_need_x = $i_diff_x - matchLength();
				$i_need_y = $i_diff_y - matchLength();

				if ($i_need_y >= 0) { 
					$i_start_y += $i_pad; 
					$i_start_x -= $i_pad; 
				}

				if (isset($a_winning_set['x'])) {
					$a_winning_set['x'][0] = $i_start_y;
					$a_winning_set['x'][1] = $i_start_x;
				}
				if (isset($a_winning_set['o'])) {
					$a_winning_set['o'][0] = $i_start_y;
					$a_winning_set['o'][1] = $i_start_x;
				}

				$this->isGameOver = TRUE;
				$a_winning_set['how'] = 'acutely';
				$this->a_winning_spot = $a_winning_set;
			}
			$a_coords = array(); // clear everything out again
			if (count($a_tmp_column) > 0) { $i++; }
			$a_tmp_column = array();
			if ($outer_x <= $end_rows) {
				$outer_x++;
			} else {
				$outer_x = $end_rows;
				$outer_y++;
			}
			$x = $outer_x;
			$y = $outer_y;
		}

	}

	function scanDiagonalsGravelyForWin() {
		$a_tmp_column  = array();
		$a_winning_set = array();
		$start_rows    = $this->rows - matchLength();
		$end_rows      = $this->rows - 1;
		$start_cols    = 0;
		$end_cols      = $this->columns - 1;
		$max_cols      = matchLength() - 1;
		$y             = $start_cols;
		$x             = $start_rows;
		$i_max         = $this->rows * $this->columns;
		$outer_x       = $x;
		$outer_y       = $y;
		$a_coords      = array();
		
		$i = 0;
		$j = 0;
		while($outer_y <= matchLength()) {
			while ( ($x <= $end_rows) && ($x >= 0) && 
				($outer_y <= $max_cols) && ($y <= $end_cols) ) 
			{
				if ($j == 0) {
					$s_start = "start_y = $y start_x = $x<br/>\n";
					$i_start_x = $x;
					$i_start_y = $y;
				}
				$s_end = "end_y = $y end_x = $x<br/>\n";
				$i_end_x = $x;
				$i_end_y = $y;
				$a_tmp_column[] = $this->layout[$x][$y];
				$a_coords[] = "$x:$y";
				$y++;
				$x++;
				$j++;
			}
			$j = 0;
			$a_winning_set = findWinner($a_tmp_column, $i, $this->x, $this->o, 'gravely');

			// find first value filled in a_tmp_column

			if (is_array($a_winning_set)) {

				// TODO: should I use i_pad to increment? hell ya
				$p_limit = count($a_tmp_column);
				for ($p = 0; $p < $p_limit; $p++) {
					if (strlen($a_tmp_column[$p]) > 1) {
						$i_pad = $p;
						break;
					}
				}

				$i_diff_x = $i_end_x - $i_start_x ;
				$i_diff_y = $i_end_y - $i_start_y ;
				$i_need_x = $i_diff_x - matchLength();
				$i_need_y = $i_diff_y - matchLength();

				if ($i_need_x >= 0) { $i_start_x += $i_pad; }
				if ($i_need_y >= 0) { $i_start_y += $i_pad; }

				if (isset($a_winning_set['x'])) {
					$a_winning_set['x'][0] = $i_start_y;
					$a_winning_set['x'][1] = $i_start_x;
				}
				if (isset($a_winning_set['o'])) {
					$a_winning_set['o'][0] = $i_start_y;
					$a_winning_set['o'][1] = $i_start_x;
				}

				$this->isGameOver = TRUE;
				$a_winning_set['how'] = 'gravely';
				$this->a_winning_spot = $a_winning_set;
			}
			$a_coords = array(); // clear everything out again
			if (count($a_tmp_column) > 0) { 
				$i++; 
			}
			$a_tmp_column = array();
			if ($outer_x >= 0) {
				$outer_x--;
			} else {
				$outer_x = 0;
				$outer_y++;
			}
			$x = $outer_x;
			$y = $outer_y;
		}
	}
}



function IsColumnFilled($rows, $column, $a_set) {
 if (!is_int($column)) { return false; }
	
 $i_filled = 0;
 foreach($a_set as $row) {
  $i = 0;
  foreach ($row as $item) {
	 if ($i == $column) {
		if (strcmp($item, ' ') != 0)  {
		  $i_filled++;
		}
	}
	 $i++;
	}
 }
 if ($i_filled == $rows) { return true; }
 return false;
}

// this is where we find the winner
function findWinner($a_set, $i_index, $x='', $o='', $how='') {


    $match_length = matchLength();
    $set_size     = count($a_set);
    $i_tries      = $set_size - $match_length + 1;
    $start_point  = 0;
    $end_point    = $set_size - $i_tries;


	if (strlen($x) <= 0) {
		$x = 'x';
	}
	if (strlen($o) <= 0) {
		$o = 'o';
	}
    
    // loop through places to try
    // turn this into a while loop
    
    $x_count = 0;
    $o_count = 0;
    $i = 0;
    $j = 0;
    $i_sent = 1;
    $i_sent2 = 1;

    while($i_sent2 > 0) {    
        while ($i_sent > 0){
            if (strcmp($a_set[$j], $x) == 0) {
		$x_count++;
            }
            if ($x_count == $match_length) {
                $i_sent  = 0;
                $i_sent2 = 0;
		return array('x' => array($i_index, $i, $j));
            }

            if (strcmp($a_set[$j], $o) == 0) {
                $o_count++;
            }
            if ($o_count == $match_length) {
                $i_sent  = 0;
                $i_sent2 = 0;
		return array('o' => array($i_index, $i, $j));
            }
            if ($j >= $end_point) { $i_sent = 0; }
            $j++;

        } // end while loop
        $start_point++;
        $end_point++;
        $j = $start_point;
        $x_count = 0;
        $o_count = 0;
        $i_sent = 1;
        
        if ($i >= $i_tries) { $i_sent2 = 0; }
        $i++;        
    } // end while loop
    
    // no winner
    return FALSE; 
}


function matchLength() {

	return 4;

}


?>
