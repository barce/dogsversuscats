<?php

require 'functions.php';
require 'models.php';



// prethis($_POST);
if ((isset($_POST['dog_move'])) ||(isset($_POST['cat_move']))) {
  $board = new Board($_POST['rows'], $_POST['columns'],urldecode($_POST['s_layout']));
  $board->update();
} else if (($_POST['set_rows_columns'] == 'reset') && ($_POST['rows'] >= 4)
  && ($_POST['columns'] >= 4)){
  $board = new Board($_POST['rows'],$_POST['columns']);
} else {
  // $board = new Board(6,7,'',array('hi'));
  $board = new Board(6,7);

}

// $board->print_layout();
// $board->wincheck();

$board->scanRowsForWin();
$board->scanColumnsForWin();
$board->scanDiagonalsAcutelyForWin();
$board->scanDiagonalsGravelyForWin();

$board->calculateWinningBoxes();
$theboard = $board->display();
$s_layout = urlencode(serialize($board->layout));
$s_turn   = $board->turn;

$the_turn = "It's the $s_turn's turn.";
// $board->print_layout();
$a_layout = $board->layout;
if ($board->isGameOver) {
  $winnar = '<h1>' . ucfirst($_POST['s_turn']) . ' wins!</h1>';
  $the_turn = '';
  ob_start();
  print_r($board->a_winning_spot);
  print_r($board->a_winning_array);
  $o_this = ob_get_contents();
  ob_end_clean();
}



?>
