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

$path = '../tmp/';

$tempfilename = $_REQUEST['filename'].'.pdf';
if (strstr($tempfilename,'/') || strstr($tempfilename,'\\')) { die("Filename should not contain \ or / "); }
$opname = $_REQUEST['opname'];
$dest = $_REQUEST['dest'];
	if ($tempfilename && file_exists($path.$tempfilename)) {
		// mPDF 5.3.17
		if ($dest=='I') {
			if(PHP_SAPI!='cli') {
				header('Content-Type: application/pdf');
				header('Content-disposition: inline; filename="'.$name.'"');
				header('Cache-Control: public, must-revalidate, max-age=0'); 
				header('Pragma: public');
				header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); 
				header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			}
		}

		else if ($dest=='D') {
			header('Content-Description: File Transfer');
			if (headers_sent())
				$this->Error('Some data has already been output to browser, can\'t send PDF file');
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: public, must-revalidate, max-age=0');
			header('Pragma: public');
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Content-Type: application/force-download');
			header('Content-Type: application/octet-stream', false);
			header('Content-Type: application/download', false);
			header('Content-Type: application/pdf', false);
			header('Content-disposition: attachment; filename="'.$name.'"');
		}
		$filesize = filesize($path.$tempfilename);
		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) OR empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
			// don't use length if server using compression
			header('Content-Length: '.$filesize);
		}
		$fd=fopen($path.$tempfilename,'rb');
		fpassthru($fd);
		fclose($fd);
		unlink($path.$tempfilename);
		// ====================== DELETE OLD FILES - Housekeeping =========================================
		// Clear any files in directory that are >24 hrs old
		$interval = 86400;
		if ($handle = opendir(dirname($path.'dummy'))) {
		   while (false !== ($file = readdir($handle))) { 
			if (((filemtime($path.$file)+$interval) < time()) && ($file != "..") && ($file != ".") && substr($file, -3)=='pdf') { 
				unlink($path.$file); 
			}
		   }
		   closedir($handle); 
		}
		exit;
	}
?>