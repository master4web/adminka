#!/usr/bin/php
<?php

	if (!isset($argv[1]))
		exit;

	$table = $argv[1]; 


	require_once ('../config/ini.php');


	$result = mysql_query('SHOW COLUMNS FROM '.$table);

	if (mysql_num_rows($result) == 0) exit;

   	
    while ($row = mysql_fetch_assoc($result)) {
       	$xml .= "	<item>\n";
       	$xml .= "		<column>".$row['Field']."</column>\n
       	<title>".$row['Field']."</title>\n";
       	if ($row['Extra'] == 'auto_increment')
       		$xml .= "		<type>increment</type>\n";
       	elseif ($row['Type'] == 'tinyint(1)')
       		$xml .= "		<type>checkbox</type>\n";
   		elseif ($row['Type'] == 'varchar(4)')
       		$xml .= "		<type>file</type>\n";
       	elseif ($row['Type'] == 'text')
       		$xml .= "		<type>textareatiny</type>\n";
       	elseif ($row['Type'] == 'datetime')
       		$xml .= "		<type>datetime</type>\n";		
       	else
       		$xml .= "		<type>text</type>\n";
       	
       	$xml .= "		<view>\n";
		$xml .= "			<table>True</table>\n";
		if ($row['Extra'] == 'auto_increment'){
			$xml .=	"			<form>False</form>\n";
			$increment = $row['Field'];
		}
			else
				$xml .=	"			<form>True</form>\n";
				
		$xml .=	"		</view>\n";
       	$xml .=	"	</item>\n";
    }     	
    

	$xml = '<?xml version="1.0" encoding="UTF8"?>'."\n".
	'<items>
	<main>
		<table>'.$table.'</table>
		<order>'.$increment.'</order>
		<order_type>DESC</order_type>
		<increment>'.$increment.'</increment>
		<title>'.$table.'</title>
	</main>'."\n".$xml.'</items>';	

	$xfile = site_fold_ad.'xml/'.$table.'.xml';
	echo $xfile;
	$file = fopen($xfile,'w');
	fwrite($file, $xml);
	fclose($file);

