<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('walletonebilling', 'plug');

function cot_walletonebilling_print_answer($result, $description)
{
  print "WMI_RESULT=" . strtoupper($result) . "&";
  print "WMI_DESCRIPTION=" .$description;
  exit();
}

?>