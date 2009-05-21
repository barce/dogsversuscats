<?php

class Slowprinter {

    var $rows;
    var $columns;
    var $layout;
    var $match_length = 4;
    
    function __construct($rows, $columns) {
        $this->rows = $rows;
        $this->columns = $columns;

    	for($i = 0; $i < $rows; $i++) {
            for($j = 0; $j < $columns; $j++) {
                $tmp[$j] = "[$j:$i]";
            }
            $this->layout[] = $tmp;
        }    

/*
	$this->layout = array (
            array (" ", "x", "x","x", "x", " ", " "),
            array (" ", " ", " "," ", " ", " ", " "),
            array (" ", " ", "o","o", "o", "o", " "),
            array (" ", " ", " ","x", "x", "x", "x"),
            array (" ", " ", "o"," ", "x", "o", " "),
            array (" ", "x", "x","o", "o", "x", "o"),
            );
*/

    } // end constructor
    

    function display() {
        for($y = 0; $y < $this->rows; $y++) {
            for($x = 0; $x < $this->columns; $x++) {
                print $this->layout[$y][$x];
                // sleep (1);
            }
            print "\n";
        }
    }
    
    function scanRows() {
        for($y = 0; $y < $this->rows; $y++) {
	    print "---[ start:results ]---\n";
            print_r(findWinner($this->layout[$y], $y, 'x', 'o'));
	    print "---[ end:results ]---\n";
        }
        
    }
}


// this is where we find the winner
function findWinner($a_set, $i_index, $x='', $o='') {


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
				if (isset($a_set[$j])) {
            	if (strcmp($a_set[$j], $x) == 0) {
              	  $x_count++;
            	}
				}
            if ($x_count == $match_length) {
                $i_sent  = 0;
                $i_sent2 = 0;
		return array('x' => array($i_index, $i, $j));
            }


				if (isset($a_set[$j])) {
            	if (strcmp($a_set[$j], $o) == 0) {
              	  $o_count++;
            	}
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
// (columns - 4 + 1) * rows == number of checks on columns
// (rows - 4 + 1) * columns == number of checks on rows


$sp = new Slowprinter(6, 7);
$sp->display();
$sp->scanRows();





?>
