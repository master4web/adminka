<? class auth
	{
		
		var $suser = 'Auth_User';
		var $spw = 'Auth_PSWRD';
		
		
		function action()
		{
			if(!$this->check()) $this->authorized();
			
		}
		
		//вызываем автороизацию
		function authorized()
		{
			header('WWW-Authenticate: Basic realm="Argumenti: authorized"');
			header('HTTP/1.0 401 Unauthorized');
			header('status: 401 Unauthorized');
			echo 'нет доступа';
			exit();
			/*http://loginassword@www.domain.ru/page.php*/
		}
		
		//проверяем данные
		function check()
		{
			
			if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) 
				return False;
			
			return True;	
			
		}
		
		function save()
		{
			$_SESSION['Auth_User'] = $_SERVER['PHP_AUTH_USER'];
			$_SESSION['Auth_PSWRD'] = $_SESSION['Auth_PSWRD'];
		}
		
	
	
	
	}

?>