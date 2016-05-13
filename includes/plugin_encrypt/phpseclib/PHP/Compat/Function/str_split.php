<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */
/**
 * Replace str_split()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.str_split
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.1 $
 * @since       PHP 5
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_str_split($string, $split_length = 1)
{
    if (!is_scalar($split_length)) {
        user_error('str_split() expects parameter 2 to be long, ' .
            gettype($split_length) . ' given', E_USER_WARNING);
        return false;
    }

    $split_length = (int) $split_length;
    if ($split_length < 1) {
        user_error('str_split() The length of each segment must be greater than zero', E_USER_WARNING);
        return false;
    }
    
    // Select split method
    if ($split_length < 65536) {
        // Faster, but only works for less than 2^16
        preg_match_all('/.{1,' . $split_length . '}/s', $string, $matches);
        return $matches[0];
    } else {
        // Required due to preg limitations
        $arr = array();
        $idx = 0;
        $pos = 0;
        $len = strlen($string);

        while ($len > 0) {
            $blk = ($len < $split_length) ? $len : $split_length;
            $arr[$idx++] = substr($string, $pos, $blk);
            $pos += $blk;
            $len -= $blk;
        }

        return $arr;
    }
}


// Define
if (!function_exists('str_split')) {
    function str_split($string, $split_length = 1)
    {
        return php_compat_str_split($string, $split_length);
    }
}
