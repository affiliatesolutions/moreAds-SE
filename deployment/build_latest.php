#!/usr/bin/env php
<?php
$theme = 'ma-se';


$style = file_get_contents('../'.$theme.'/ma-se.php');
preg_match ( '|Version:(.*)|i', $style, $a_version );

if(!isset($a_version[1])) { exit; }
$version = trim($a_version[1]);


$zip = new ZipArchive();


chdir("../");
system("zip -r deployment/latest.zip $theme/");