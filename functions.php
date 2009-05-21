<?php

/*
 * @author: barce[a t]codebelay.com
 * @parameters: object
 * @returns:    bool
 * 
 *
 */
function prethis($obj, $label='') {


  print "<pre>\n";
  if (strlen($label) > 0) { print $label . ":\n"; }
  print_r($obj);
  print "</pre>\n";
  return true;
}


?>
