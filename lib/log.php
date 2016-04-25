<?function write_log($value, $file = 'log/error.log')
  {
	$log = site_fold_ad.$file;
	
	if (file_exists($log))
		$fp = fopen($log,'a+');
	else {	
		$fp = fopen($log,'w');
		chmod($log, 0660);
	}	
	
	fwrite($fp, '['.date('d.m.y\ H:i:s').'] '.$value.chr(13));
	fclose($fp);
  }
 ?> 