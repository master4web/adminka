<?  header("Content-Type: text/html;charset=windows-1251");
session_start();
	
	//0 - файла нет
	//1 - файл удален
	//2 - невозможно удалить файл 
	
	if (isset($_GET['file']))
		$file = $_SERVER['DOCUMENT_ROOT'].'/'.strip_tags($_GET['file']);
	if (isset($_GET['id']))
		$id = $_GET['id'];	
		
	if (isset($_GET['column']))
		$column = $_GET['column'];
		
	if(file_exists($file)) {		
		if (unlink($file)){
			$message = 'файл удален!';
			$status = 1;
		}	
		else
			$status = 2;
	}		
	else{
		$message = 'файл не существует!';
		$status = 0;
	}
		
		
	if ($status == 2){
		echo('<INPUT  TYPE = "button" VALUE = "Удалить файл" onClick = "'."sendRequest('".site_ad."/deletefile?file=".$file."&id=".$id."', 'fl".$id."', getRequest);".'" /> <i>файл не удален!</i>');	
	}
	else
		echo '<i>'.$message.'</i><INPUT NAME = "DFile_'.$column.'" TYPE = "hidden" VALUE = "'.$status.'">'
		
?>