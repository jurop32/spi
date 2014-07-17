<?php
/*
 * Function cuts the string without loosing a word
 * input: string, length
 * output: cutted string with dots on the end
 */
function cutStringToWord($str, $n, $delim=' ...') {
   $len = strlen($str);
   if ($len > $n) {
       return substr($str,0,strrpos(substr($str,0,$n),' ')).$delim;
   }
   else {
       return $str;
   }
}