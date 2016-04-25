<?php
	
	//сохранить файл
	function save($filename, $content, $mode = null)
	{
		
		$dir = dirname($filename);
				
		if (!is_dir($dir))
			mkdir($dir, 0777, True);
		
			
		$file = fopen($filename,'w');
		if ($file) {
			
			flock($file, LOCK_EX);
			if (fwrite($file, $content))
				$result =  True;
			else
				$result =  False;
			flock($file, LOCK_UN);
			fclose($file);
			if ($mode) // права доступа
				chmod($filename, '0'.$mode);
		}
		else 				
			$result = False;
			
					
	}			

	
	//рубим директорию вместе с файлом
 function full_del_dir ($directory)
  {
    
  if (!is_dir($directory)) exit;
 
 $dir = opendir($directory);
  while(($file = readdir($dir))){
    if ( is_file ($directory."/".$file))
    {
      unlink ($directory."/".$file);
    }
    else if ( is_dir ($directory."/".$file) &&
             ($file != ".") && ($file != ".."))
    {
      full_del_dir ($directory."/".$file);  
    }
  }
  closedir ($dir);
  rmdir ($directory);
 }
 		
		
function crdir($dir)
{
	//создаем каталог  
	if (!is_dir($dir)){
		if (!mkdir($dir, 0775, True)) {
			echo('Невозможно создать каталог'.$dir.'!');
			return False;
		}	
		else
			return True;
	} 
	
	return True;
}		
	
?>