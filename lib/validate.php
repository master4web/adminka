<?

function validate($txt, $vald_type)
{
		
	switch (strtolower(trim($vald_type)))  {
		case 'notnull':
			return ($txt == '') ? 0 : 1;
			break;
		case 'mail':
			return (!eregi("^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$", $txt)) ?  0 : 1;
			break;
		case 'phone':
			return (!eregi("^[0-9]{3}-*[0-9]{3}-*[0-9]{4}$", $txt)) ? 0 : 1;
			break;
		case 'mysqldate':
			return (!eregi("^[0-9]{4}-*[0-9]{2}-*[0-9]{2}$", $txt)) ? 0 : 1;
			break;
		default:
			return 0;
	}
}


?>
