<?php
// the view kinda
require 'controllers.php';
$title = 'Connect 4 - Cats versus Dogs';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= $title ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type='text/css'>

.winningbox {
	border: 4px solid #f00;
}

#theboard td {
  height: 50px;
  width: 50px;

}
.move_button {
  height: 50px;
  width: 50px;
}
#rows, #columns {
	width: 50px;
}
</style>
</head>
<body>

<br/>
<form method='post' action='<?=$_SERVER['PHP_SELF'] ?>'>
rows: 
<input type="text" id='rows'    name="rows" value="<?= $_POST['rows'] ?>" />
columns: 
<input type="text" id='columns' name="columns" value="<?= $_POST['columns'] ?>" />
<input type="submit" name="set_rows_columns" value="reset"/>
<?=$theboard ?>
<input type="hidden" name="s_layout" value='<?=$s_layout ?>'/>
<input type="hidden" name="s_turn" value='<?=$s_turn ?>'/>
</form>


<?= $the_turn ?>
<?= $winnar ?>
<?php 
	//prethis($o_this); 
?>
<br/>
<?php
// prethis($_POST);
// prethis($a_layout);
?>
<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10-blue"
        alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
  </p>
</body>
</html>
