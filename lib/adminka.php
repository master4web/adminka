<?php

require 'log.php';


function lockkey($table, $id){
	return site_fold_ad.'log/'.$table.'_'.$id.'.lock';
}


function lock_id($table, $id, $user = '') {
		
	$lockfile = lockkey($table, $id);

	if (file_exists($lockfile)){
		return file_get_contents($lockfile);
	}
	else {
		file_put_contents($lockfile, trim($user)); // rezerv
		return False;
	}
	


}

function lock_status($table, $id) {
	
	$lockfile = lockkey($table, $id);
	return file_exists($lockfile);
	
}


function unlock_id($table, $id, $user){
	
	$lockfile = lockkey($table, $id);
	$user = trim($user);

	if (file_exists($lockfile)){
		$name =  trim(file_get_contents($lockfile));
	}
	
	if ($name == $user){
		if (unlink($lockfile) == False)
			write_log($_SERVER['PHP_AUTH_USER'].':ip='.$_SERVER['REMOTE_ADDR'].':error unlink: '.$lockfile, 'log/lock.log');
	}

	return;

}


class AKdmin {

private $admin = ''; 
private $fields = array();
private $where = array();
private $order = array(); // 
private $limit = 20;
private $page = 1;
public $link = null;
private $userid = 0;


function xss($value) {
	
	
	$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
	$value = htmlspecialchars($value);
	$value = strip_tags($value);
	$value = stripslashes ($value);
	     
  
    return $value;	

}


function link($link) {
	$this->link = $link;
}


//обработка входящих параметров
function gparam($name, $default = null, $type = 'str') {

	if (isset($_GET[$name])) {
		$value = $_GET[$name];
		if ($type == 'str')
			return $this->xss($value);
		elseif($type == 'int')
			return (int)$value;
	}	
	else
		return  $default;

}



//загрузка схемы или ее создание по умолчанию
function load($shema) {
	
	$f_xml = APPPATH.'xml/'. $shema.'.xml';

	if (!file_exists($f_xml)){ // если файла нет - пытаемся создать
		if (!$this->create($shema, $f_xml)) { // если файл не создался
			echo('not found shema');
			exit;
		}	
	} 

	return simplexml_load_file($f_xml);

}


/**
* автоматическое создание файла модели
*/
function create($table, $xfile) {

	$result = mysql_query("SHOW COLUMNS FROM `$table`");

	if (mysql_num_rows($result) == 0)
	    return false;

    while ($row = mysql_fetch_assoc($result)) {
       	$xml .= "	<item>\n";
       	$xml .= "		<column>".$row['Field']."</column>\n
       	<title>".$row['Field']."</title>\n";
       	if ($row['Extra'] == 'auto_increment')
       		$xml .= "		<type>increment</type>\n";
       	elseif ($row['Type'] == 'tinyint(1)')
       		$xml .= "		<type>checkbox</type>\n";
   		elseif ($row['Type'] == 'varchar(4)')
       		$xml .= "		<type>file</type>\n
       						<col>85</col>\n";
       	elseif ($row['Type'] == 'text')
       		$xml .= "		<type>textareatiny</type>\n
       						<row>8</row>\n
       						<col>85</col>\n";
       	elseif ($row['Type'] == 'datetime')
       		$xml .= "		<type>datetime</type>\n";		
       	else
       		$xml .= "		<type>text</type>\n
       						<col>85</col>\n";
       	
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
	"<items>
	<main>
		<table>$table</table>
		<order>$incremen</order>
		<order_type>DESC</order_type>
		<increment>$increment</increment>
		<title>$table</title>
	</main>$xml</items>";	

	//$xfile = site_fold_ad.'xml/'.$table.'.xml';
	return file_put_contents($xfile, $xml);
	
} 




function config($fconfig) {

	
	configer::load($fconfig);
	$set = configer::all();

	// Выставляем папки по умолчанию

	if (!isset($set['site']))
		$set['SITE'] = 'http://'.str_replace('www', '', $_SERVER['HTTP_HOST']).'/';


	if (!isset($set['AD']))
		$set['AD'] = 'http://'.str_replace('www', '', $_SERVER['HTTP_HOST']).'/';

	if (!isset($set['site_fold'])){
		$set['site_fold'] = $_SERVER['DOCUMENT_ROOT'].'/';
		$set['SITEPATH'] = $set['site_fold'];
	}

	

	if (!isset($set['site_fold_ad'])) { //автоопределение папки 
		
		$maindir = dirname($fconfig);
		
		if (substr($maindir,-6) == 'config');
			$maindir = substr($maindir, 0, -6);

		$set['site_fold_ad'] =  $maindir;

	}


	$set['vendor']=dirname(dirname(__FILE__)).'/';
 
	if (!isset($set['APPPATH']))
		$set['APPPATH'] = $set['site_fold_ad'].'app/';

	if (!isset($set['site_ad']))
		$set['site_ad'] = $set['AD'];

	if (!isset($set['THEME']))
		$set['THEME'] = $set['site_fold_ad'].'vendor/master4web/adminka/themes/office/';

	if (!isset($set['PUB']))
		$set['PUB'] = $set['AD'].'vendor/master4web/adminka/themes/office/pub/';

	if (!isset($set['psite']))
		$set['psite'] = $set['SITE'];

	if (!isset($set['sysfold']))
		$set['psite'] = $set['site_fold'].'system';

	if (!isset($set['imgfold']))
		$set['imgfold'] = $set['site_fold_ad'].'images/';

	if (!isset($set['imgcache']))
		$set['imgcache'] = $set['imgfold'].'preview/';

if (!isset($set['imglink']))
	$set['imglink'] =$set['AD'].'images/';


	
// подключаемся к БД
if (isset($set['db'])) {
	kORM::config($set['db']['db'], $set['db']['user'], $set['db']['password']);
	
	$this->link=@mysql_connect($set['db']['host'], $set['db']['user'], $set['db']['password']) or die ('Нет связи с базой : ' . mysql_error());
	
	mysql_select_db($set['db']['db'], $this->link) or die ('Can\'t use foo : ' . mysql_error());
	mysql_query("SET NAMES UTF8");
}


// показываем ошибки
/*if (isset($set['debug']) and $set['debug'] == 1){ 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}
else {
	ini_set('display_errors',0);
}*/

configer::load($set);
configer::todefines();

//print_r($set);

return $this;


}









function start(){

	if (!defined('DEBUG')) {
		include_once ('auth.lib.php');
		$auth = new auth();
		$auth->action();
		
		$user_row = kORM::table('users')->where('login', $_SERVER['PHP_AUTH_USER'])->one();

		/*$user = mysql_query("SELECT * FROM `users` Where `login`='".$_SERVER['PHP_AUTH_USER']."'");
		$user_row = mysql_fetch_array($user);*/
	
		if ($user_row == null)
			$auth->authorized();
	
		session_start();
		$_SESSION['user_id'] = $user_row['user_id'];
		setcookie('user_id', $user_row['user_id']);
		$this->userid = $user_row['user_id']; 
		$group_id =  $user_row['group_id'];
		$nameuser = $user_row['name'];
		$region_id = $user_row['region_id'];

		$this->username = $nameuser;
	
		$grrow = kORM::table('groupuser')->where('group_id', $group_id)->one();

		if ($user_row != null) {
			$_SESSION['group'] = $grrow['name'];
			$_SESSION['readonly'] = 0;		
		}
		else
			$_SESSION['readonly'] = 0;
	
	
		write_log($_SERVER['PHP_AUTH_USER'].':ip='.$_SERVER['REMOTE_ADDR'].':authorized', 'log/edition.log');
	}
	else
		$group_id = 1;


	$menufile = file_get_contents(APPPATH.'menu/'.$group_id.'.json');
	
	$menus = json_decode($menufile, true);


	include(THEME.'views/layout/main.phtml');
	return;

}


function user_init() {
	
	$user_row = kORM::table('users')->where('login', $_SERVER['PHP_AUTH_USER'])->one();
	
	if (isset($user_row))
		$this->userid = $user_row['user_id'];
}


function init() {

	if (count($_GET) == 0) {
		$this->start();
		return;
	} 


	$this->user_init();

	
session_start();

 require_once ('photos.php');
 require_once ('day_and_week.php');
 require_once ('acess.php');
 
 require_once ('filter.php');
 require_once ('file.php');


 $filter = new filter;






 function tp_quotes($text)
	{
		$quotes=array('&quot;', '&laquo;', '&raquo;', '«', '»', '&#171;', '&#187;', '&#147;', '&#132;', '&#8222;', '&#8220;');
		$text=str_replace($quotes, '"', $text);

		$text=preg_replace('/([^=]|\A)""(\.{2,4}[а-яА-Я\w\-]+|[а-яА-Я\w\-]+)/', '$1<typo:quot1>"$2', $text);
		$text=preg_replace('/([^=]|\A)"(\.{2,4}[а-яА-Я\w\-]+|[а-яА-Я\w\-]+)/', '$1<typo:quot1>$2', $text);

		$text=preg_replace('/([а-яА-Я\w\.\-]+)""([\n\.\?\!, \)][^>]{0,1})/', '$1"</typo:quot1>$2', $text);
		$text=preg_replace('/([а-яА-Я\w\.\-]+)"([\n\.\?\!, \)][^>]{0,1})/', '$1</typo:quot1>$2', $text);

		$text=preg_replace('/(<\/typo:quot1>[\.\?\!]{1,3})"([\n\.\?\!, \)][^>]{0,1})/', '$1</typo:quot1>$2', $text);
		$text=preg_replace('/(<typo:quot1>[а-яА-Я\w\.\- \n]*?)<typo:quot1>(.+?)<\/typo:quot1>/', '$1<typo:quot2>$2</typo:quot2>', $text);
		$text=preg_replace('/(<\/typo:quot2>.+?)<typo:quot1>(.+?)<\/typo:quot1>/', '$1<typo:quot2>$2</typo:quot2>', $text);
		$text=preg_replace('/(<typo:quot2>.+?<\/typo:quot2>)\.(.+?<typo:quot1>)/', '$1<\/typo:quot1>.$2', $text);
		$text=preg_replace('/(<typo:quot2>.+?<\/typo:quot2>)\.(?!<\/typo:quot1>)/', '$1</typo:quot1>.$2$3$4', $text);
		$text=preg_replace('/""/', '</typo:quot2></typo:quot1>', $text);
		$text=preg_replace('/(?<=<typo:quot2>)(.+?)<typo:quot1>(.+?)(?!<\/typo:quot2>)/', '$1<typo:quot2>$2', $text);
		$text=preg_replace('/"/', '</typo:quot1>', $text);

		$text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $text);
		$text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $text);
		$text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $text);
		$text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $text);
		$text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $text);
		$text=preg_replace('/(<[^>]+)<\/typo:quot\d>/', '$1"', $text);

		$text=str_replace('<typo:quot1>', $this->quot11, $text);
		$text=str_replace('</typo:quot1>', $this->quot12, $text);
		$text=str_replace('<typo:quot2>', $this->quot21, $text);
		$text=str_replace('</typo:quot2>', $this->quot22, $text);

		return;
	}




 //превращает в js синтаксис
 function js_func ($namefunc, $params){

		$params_line = '';

		foreach ($params as $param) {
			if ($params_line !== '')
				$params_line .= ',';
			$params_line .=	chr(39).$param.chr(39);
		}

		return $namefunc.'('.$params_line.');';
	}





 function delete_cache($fstr, $increment)
 {

	$files = explode(',',$fstr);

	foreach ($files as $file){
		$fname = SITEPATH.str_replace('{%}', $increment, trim($file));
		unlink($fname);
	}

	return;

 }


 //приводим дату в названии папки
 function date_to_url($date, $time = True, $separ = '/')
 {

	$currdate = explode(' ', $date);
	$date = explode('-', $currdate[0]);
	$url = $date[0].$separ.$date[1].$separ.$date[2];

	if ($time) {
		$time = explode(':', $currdate[1]);
		$url .= $separ.$time[0].$separ.$time[1].$separ.$time[2];
	}

	return $url;

 }


 //пересчет кол-во значений в таблице
 function counts($citems, $values)
 {

	/*
		table - таблица, где обновляем данные
		t_inc - имя инкрементного поля таблицы в которой обновляем
		t_inc_column - ссылка на значение в поле гл таблицы
		column - колонка которую обновляем
		values - все полученные значения переменных
	*/

	$col_inc_name = (string)$citems->column_inc;

	$count_sql = 'SELECT COUNT(*) FROM '.MAINTABLE.' WHERE '.$col_inc_name.'='.$values[$col_inc_name].' '.$citems->sql_where;
	$countres = mysql_query ($count_sql); 
	if (!$countres)
		 write_log('Ошибка MySQL: '.mysql_error()); //подсчет
	else {	
		$sqlrows = mysql_num_rows($countres);
		if ($sqlrows > 0) { //запись результатов
			$row = mysql_fetch_array($countres, MYSQL_NUM);
			$count = $row[0];//получаем кол-во
			$sql_update = 'UPDATE '.$citems->table.' SET '. $citems->column.'='.$count.' WHERE '.$citems->t_inc.' = '.$values[$col_inc_name];
			$upd_result = mysql_query($sql_update);
			if (!$upd_result)
		    	write_log('Ошибка MySQL: '.mysql_error());
		}    
	}


 }



function separ($txt)
	{
		return '`'.$txt.'`';
	}

function quote($txt)
	{
		return "'".$txt."'";
	}


 function NullErrSession() {

	if (isset($_SESSION['errors']))
		unset($_SESSION['errors']);
	if (isset($_SESSION['values']))
		unset($_SESSION['values']);

  }



  //спецсимволы при сборе заброса
 function SqlAddSpec($str, $type)
	{
		if (mb_strlen($str) > 0) {
			if ($type == 0)
				return ', ';
			else if ($type == 1)
				return ' AND ';
			else
				return '';
		}
		else
			return '';


	}



 function  fileexpansion ($filename)
 {
	$out = array();
	preg_match('/\S+\.(\S+)$/', $filename, $out);
	return $out[1];
 }



 function subfilters($value, $config)
 {

 }

 //построение выпадающих списков и фильтров
 function lookup($table, $increment, $column, $value,  $params = array(), $attrs = array())
 {

	$where = '';
	$order = '';
	$limit = '';
	$join = '';


	//поля учавствующее в запросе
	$fields[] = separ($increment);
	$fields[] = separ($column);


	if (sizeof($params) > 0){
		If (isset($params['where']))
			$where = ' WHERE '.$params['where'];
		If (isset($params['order']))
			$order = ' ORDER BY '.$params['order'];
		If (isset($params['limit']))
			$limit = ' LIMIT '.$params['limit'];
		if (isset($params['subfilter'])){
			$subfilter = $params['subfilter'];
			//$fields[] = separ($params['subfilter']['column']);

			/*if ($subfilter['table'] !== $table) {
				$join = ' LEFT JOIN '.separ($subfilter['table']).' ON ('.separ($subfilter['table']).'.'.separ($subfilter['id']).'='.separ($table).'.'.separ($wh_column).') ';
			}*/
		}
	}


	$sql_select = 'SELECT '.implode(',', $fields).' FROM '.separ($table).$join.$where.$order.$limit;
	//echo $table.': '.$sql_select.'<br /><br />';
	$selectres = mysql_query($sql_select);

	if (@mysql_num_rows($selectres) !== 0) {
		foreach ($attrs as $key => $attr) //дополнительные атрибуты
			$attr_str .= ' '.$key.'="'.$attr.'" ';
		$null_txt = (isset($params['null'])) ? $params['null'] : '- не выбрано - ';
		$selected = ($value == 0) ? ' selected="selected"': '';
		$result = '<SELECT'.$attr_str.'><option '.$selected.'class="grays" VALUE="0">'.$null_txt.'</option>';
		while ($selectrow = mysql_fetch_row($selectres)) {
			if ($selectrow[0] > 0 and  $selectrow[0] !== '') {
				$selected = ($selectrow[0] == $value) ? $selected = ' selected ' : '';
				$sel_txt = $selectrow[1];
				if (isset($selectrow[2]) and $selectrow[2] !== '')
					$sel_txt .= '['.$selectrow[2].']';
				$result .= '<option'.$selected.' value="'.$selectrow[0].'">'.$sel_txt.'</option>';
			}
		}
		$result .= '</SELECT>';
		return $result;
	}
	else
		return ' - нет данных - ';


 }




 //построение списка подзаписей
 function subfilter($value, $config, $id)
 {


	$sql_filter = 'SELECT '.separ($config->increment).', '.separ($config->column).' FROM '.separ($config->table).' WHERE '.separ($config->wh_column).'='.quote($value);
	$selectres = mysql_query($sql_filter);
	if (@mysql_num_rows($selectres) !== 0) {
		$result = '<SELECT ID="'.$id.'"><OPTION class="gray" VALUE="0">по умолчанию</OPTION>';
		while ($selectrow = mysql_fetch_row($selectres)) {
			$result .= '<OPTION VALUE="'.$selectrow[0].'">'.$selectrow[1].'</OPTION>';
		}
		$result .= '</SELECT>';
		return $result;
	}
	else
		return '';

}





 function GreateMainFilter($admin, $columname, $currvalue, $nullvalue = 'Нулевые значения')
	{
		$currvalue = (string)$value_tek;
		$titles = array('Все', 'Пустые значения', $nullvalue);
		$values = array(
			'all'=>array('name'=>'Все', 'value'=>''),
			'null'=>array('name'=>$nullvalue, 'value'=>'0')
			);

		foreach ($values as $value) {
			$selected = ($value['value'] == $currvalue) ? $selected = ' selected="selected"' : '';
			$result .= '<option class="grays" VALUE = "'.$value['value'].'"'.$selected.'>'.$value['name'].'</option>';
		}

		return $result;
	}

  function GreateMainLookup($value_tek) {
	$value = (string)$value_tek;
	$types = array('null', '0');
	$titles = array('Пустое значение', 'Нулевое значения');
	for ($s = 0; $s < 2; $s++) {
		$selected = ($value == $types[$s]) ? 'selected="selected"' : '';
		echo '<option value = "'.$types[$s].'" '.$selected.'>'.$titles[$s].'</option>';
	}

  }


 function AnonsText($text, $counts, $counttype)
  {

	$text = strip_tags($text);
	$mb_strlen = mb_strlen($text);
	if ($mb_strlen < $counts)
	    return $text;

	else {

		 for ($i = $counts - 1; $i <= $mb_strlen; $i++){
	         $s = mb_substr($text,$i,1);
	         if ($counttype == 0) {
			   if (($s == ' ') or ( $s == ',') or ($s == '.') or ($s == '!') or ($s == '?')) break; //не обрывать слова.
			   }
              else {
                 if (( $s == ',') or ($s == '.') or ($s == '!') or ($s == '?')) break; //не обрывать предложения
				}
	       }
        if ($i != $mb_strlen ) $countend = $i; else $countend = $counts;
	    return mb_substr($text, 0, $countend). '	...';
	   }
 }


#зачитываем параметры

$action = $this->gparam('action', 'selectall'); 

if ($admin = $this->gparam('admin'))
   	$this->admin = $admin;
else {
    echo 'shema zero';
    exit;
}


//доступ группам
/*if (isset($_SESSION['group'])) {
	$grname = $_SESSION['group'];
	include_once ('acess.php');
	if (isset($group[$grname])){
		$acs = explode(',', $group[$grname]);
		if (!in_array($admin, $ac)){
			echo 'нет доступа';
			exit();
		}
	}
}
else {
	echo 'нет доступа';
	exit();
}*/


 //наличие пустых параметров
 //if ($f_xml == '' or !file_exists($_SERVER['DOCUMENT_ROOT'].'/xml/'.$f_xml)){

$page = $this->gparam('page', 1, 'int');
$order = (isset($_GET['order'])) ? strip_tags(trim($_GET['order'])) : '';
 if ($order !== ''){
	$ord_len = mb_strlen($order);
	$endpos = mb_substr($order, $ord_len-1, 1);
	if ($endpos == '+') {
		$order_type = 0;
		$order = mb_substr($order, 0 ,$ord_len-1);
	}	
	elseif ($endpos == '-') {
		$order_type = 1;
		$order = mb_substr($order, 0 ,$ord_len-1);
	}	
	else	
		$order_type = 0;
}

// $order_type = (isset($_GET['order_type'])) ? (int)($_GET['order_type']) : 0;
 $increment_value = $this->gparam('increment', 0 , 'int');
 $like = $this->gparam('like', '');
 $like_id = $this->gparam('like_id', 0, int);
 

 $maxi = -1;

 $xml = $this->load($this->admin); //загружаем схему


 // читаем главные параметры
 foreach ($xml->xpath('/items/main') as $mainitem) {
	$nametable = $mainitem->table;
	$maintable = separ($nametable);
	$caption = $mainitem->title;
	$order_main = (string)$mainitem->order;
	$fields_search = (string)$mainitem->search; //поиск по полям
	
	if (isset($mainitem->deleted))
		$deleted = (string)$mainitem->deleted;
	else
		$deleted = 'True';


	if($mainitem->order_type) {
		$order_main_type = ' '.(string)$mainitem->order_type;
	}

	if (isset($mainitem->export)){
		$ex_table = $mainitem->export->table;
		$ex_map = $mainitem->export->map;
	}

	//убивание файлов кеша
	if (isset($mainitem->cache))
		$fcache = (string)$mainitem->cache;

	//пересчет кол-ва
	if(isset($mainitem->count))
		$count_items = $mainitem->count;


	$increment = (string)$mainitem->increment;

	if ($mainitem->where !== '')
	    $where_main = $mainitem->where;
	$link_view = $mainitem->vbutton->link;
	$inc_view = $mainitem->vbutton->incname;

 }


 define('INC', $increment);
 define('MAINTABLE', $maintable);

 unset($mainitem);


 $filters = array();
 $filters_count = 0;


 switch ($action) {
    case 'select':
	case 'selectall':
	case 'selectrow':
	case 'selectpage':
	case 'selecttable':


		//первоначальные значения
	    $filters_count = 0;
		$increment_num = -1;
		$maxi = -1;
		$inc_show = false;


		//зачитывае данные
		$item = $xml->xpath('/items/item');
		$item_count = sizeof($item);

		$c_type = array();
		$chet = False;

		if ($action == 'selectall'){ ?>
			<div id="caption"><?=$caption?>  Редактирование</div>
		<?}
		for ($it = 0; $it < $item_count; $it++) {

			$nullfilter = False;
			$columnname = (string)$item[$it]->column;
			if ($item[$it]->filter == 'True'){
				$filters[$filters_count]['column'] = $item[$it]->title;
				if (isset($_GET[$columnname])){
					$colfilter = strip_tags($_GET[$columnname]);
					$where_filter .= SqlAddSpec($where_filter, 1).$maintable.'.'.$item[$it]->column.' = '.$colfilter;
					if ($colfilter == 'null' || $colfilter == 0)
						$nullfilter = True;
				}
				if ($action == 'selectall'){ //рисуем фильтры
					$filters_count ++;
					if ($filters_count == 1)
						 echo '<p id = "titles">Фильтрация</p><div id = "filter">
						 <table>';
					
					$ftable = table($item[$it]->lookup->table)->select($item[$it]->lookup->id, $item[$it]->lookup->column);
					
					if ($item[$it]->lookup->where != '')
						$ftable->wh($item[$it]->lookup->where);
					
					if ($item[$it]->lookup->order != '')
						$ftable->ord_str($item[$it] ->lookup->order);
										
					$fitems = $ftable->all();

					if ($fitems !== null){
						$id_select = $item[$it]->column;
						$fvalue = $item[$it]->lookup->column;
						?>
						
						<td><b style = "color:#696969;"><?=$item[$it]->title?></b></td>
						<td>
							<SELECT ID="<?=$id_select?>" NAME = "<?=$id_select?>"  onChange="<?=js_func('select_filter', array('select_id'=>$id_select, 'admin'=>$admin, 'param_name'=>$id_select))?>">
								<option value = ""></option>
								<?foreach ($fitems as $fitem):?>
									<option value = "<?echo $fitem["$id_select"];?>"><?echo $fitem["$fvalue"];?></option>
								<?endforeach?>	
							</SELECT>
						</td>	

					<?}?>

					<?
				
				}
			}

			if ($item[$it]->view->table == 'True'){ // если для таблицы активна
			    $maxi ++;
				if ($columnname == $increment) {
					$increment_num = $it;
					$component[$maxi]['type'] = 'increment';
					$component[$maxi]['column'] = $increment;
					$inc_show = true;
				}
				$component[$maxi]['type'] = (string)$item[$it]->type; //запоминаем тип компонета
				$component[$maxi]['column'] = (string)$item[$it]->column;
				if (isset($item[$it]->link)) $component[$maxi]['link'] = (string)$item[$it]->link;
				if (isset($item[$it]->folder)) $component[$maxi]['folder'] = (string)$item[$it]->folder;
				if (isset($item[$it]->folder)) $component[$maxi]['width'] = (string)$item[$it]->width;
				if (isset($item[$it]->fieldate))
					$component[$maxi]['fieldate'] = (string)$item[$it]->fieldate;
				/*if (isset($item[$it]->curruser))
					$component[$maxi]['curruser'] = (string)$item[$it]->curruser;*/
				$new_order_type = '';
				if ($columnname == $order) {
					$order_value = ' ORDER BY '.$maintable.'.'.$columnname;
					if ($order_type == 1){
						$ord_title_type = '+';
						$order_value .= ' ASC';
						$img = '<IMG class="img_sort" src="'.PUB.'img/s_asc.png" alt="по возрастанию"/>';
					}
					else {
						$ord_title_type = '-';
						$order_value .= ' DESC';
						$img = '<IMG class="img_sort"  src="'.PUB.'img/s_desc.png" alt="по убыванию"/>';
					}
					$new_order_type = ($order_type == 1) ? ' ASC' : ' DESC';
					$title_class = ' class="order_title"';
				}
				else {
					$title_class = ' class="std_title"';
					$ord_title_type = '+';
					$img = '';
				}


				$titles .= '<td'.$title_class.'title = "'.$item[$it]->hint.'" >
				<a href="#" onClick = "StartLink('.chr(39).$admin.chr(39).", 'selectall', 'content', 'order', '".$columnname.$ord_title_type."'".');">'.$item[$it]->title.$img.'</a>
				</td>';
				if ($item[$it]->type == 'lookup' && !$nullfilter){ //формируем запрос
					$sql .= SqlAddSpec($sql, 0).$columnname.'.'.$item[$it]->lookup->column;
				//	$tables .=', '.$item[$it]->lookup->table;
					$join .= ' LEFT JOIN '.separ($item[$it]->lookup->table).' '.separ($columnname).' ON ('.separ($columnname).'.'.separ($item[$it]->lookup->id).'='.$maintable.'.'.separ($item[$it]->column).')';

					/*$where_lookup .= SqlAddSpec($where_lookup, 1).$item[$it]->lookup->table.'.'.$item[$it]->lookup->id.'='.$maintable.'.'.$item[$it]->column;*/
				}
				else
					$sql .= SqlAddSpec($sql, 0).$maintable.'.'.$item[$it]->column;

			}
		}



		if ($action == 'selectall') {
			if ($filters_count > 0):?>
				<tr><?=$ttitle?></tr><tr><?=$tselect?></tr></table></div>
			<?endif?>

			<?$like_id_value = ($like_id == 0) ? '' : $like_id;?>
			<p id = "titles">Поиск</p>
			<p id="search">
				Искать&nbsp;<input id="like" name="like" size="60" value="<?=$like?>" />
				<a onclick="StartLink('<?=$admin?>', 'selectall', 'content', 'like', document.getElementById('like').value);" title="Редактирование" href="#">
				<img alt="начать поиск" src="<?=PUB?>img/lupa.png" id="rbutton"/>
				</a>      ID&nbsp;<input id="like_id" name="like_id" size="8" value="<?=$like_id_value?>" />
				<a onclick="StartLink('<?=$admin?>', 'selectall', 'content', 'like_id', document.getElementById('like_id').value);" title="Редактирование" href="#">
				<img alt="начать поиск" src="<?=PUB?>img/lupa.png" id="rbutton"/>
				</a>
			<p id = "titles">Данные</p><div id = "main"><div id = "fields">

		<?}



		if ($increment_num == -1) {
			$sql .= SqlAddSpec($sql, 0).$maintable.'.'.$increment; //добавляем инекремнт если он не указан
			$maxi ++;
			$increment_num = $maxi;
			$component[$maxi]['type'] = 'increment'; //запоминаем тип компонета
			$component[$maxi]['column'] = $increment;
			$inc_show = false;
		}



		if ($action == 'selectrow'){
			$where = $increment." = '".$increment_value."'";
			//$where .= SqlAddSpec($where, 1).$where_lookup;
			//$sqlres = 'SELECT '.$sql.' FROM '.$maintable.$tables.' WHERE '.$where;
			$sqlres = 'SELECT '.$sql.' FROM '.$maintable;
			if ($tables !== '') $sqlres .= $tables;
			if ($join !== '') $sqlres .= $join;
			If ($where !=='')$sqlres .= ' WHERE '.$where;
		}
		else {
			if ($order_value == '' && $order_main != '') //если не один не сыграл - фильтр по умолчанию
				$order_value = ' ORDER BY '.$order_main.$order_main_type;
			if ($where_main !== '')
				$where .= SqlAddSpec($where, 1).$where_main;
			$where_filter = trim($where_filter);
			if ($where_filter !== '')
				$where .= SqlAddSpec($where, 1).$where_filter;
			if ($like !== '') {
				$sqlres .= " LIKE '".$like."'";
				$sfields = explode(',',$fields_search);
				foreach ($sfields as $field){
					$like_where[] = separ(trim($field)). " LIKE '%%".$like."%'";
				}
				$where .= SqlAddSpec($where, 1).implode(' OR ', $like_where);

			}
			if ($join != '') $sqlres .= $join;
			//if ($where_lookup != '')
			//	$where .= SqlAddSpec($where, 1).$where_lookup;
			if (trim($where) !== '')
				$where = ' WHERE '.$where;

			$per_page = (isset($_GET['limit'])) ? (int)($_GET['limit']) : 20;
			$max_sql = 'SELECT count('.$increment.')'.' FROM '.$maintable.$tables.$where.$order_value;
			$maxres = mysql_query($max_sql);
			if (@mysql_num_rows($maxres) != 0) {
				$maxrow = mysql_fetch_row($maxres);
				$total_rows = $maxrow[0];
				if ($total_rows > $per_page) {
					$num_pages = ceil($total_rows/$per_page);
					$vanitem =  ($page - 1) * $per_page;
					if ($page > 1 && $vanitem > $total_rows)
						$vanitem = 1;
					$limit = ' LIMIT '.$vanitem.','.$per_page;
				}
			}
			/*<INPUT type = "updates"  VALUE = "Обновить данные" onClick = "sendRequest('.chr(39).$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].chr(39).", 'selectpage', getReq_name);".'" />*/

			if ($action != 'selecttable'){
				echo '<p id = "edit"><INPUT type = "BUTTON"  VALUE = "Добавить" onClick = "StartLink('.chr(39).$admin.chr(39).", 'add', 'forms',  '', ''".');" />'; ?>

				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT name="updates" id="updates"  type="button" VALUE = "Обновить данные" onClick="buttupdates('<?=$admin?>');" />
				&nbsp;<input id="chkupdate" name="chkupdate" type="checkbox" onclick="autoupdate('<?=$admin?>');">автоматически</input>

			<?php }

			$sqlres = 'SELECT '.$sql.' FROM '.$maintable;
			if ($tables != '') $sqlres .= $tables;
			if ($join != '') $sqlres .= $join;
	
			if ($like_id > 0){
				$where_like = separ($increment).'= "'.$like_id.'"';
				$where .= SqlAddSpec($where, 1).$where_like;
			}	
			
			if ($where !== '')
				$sqlres .= $where;
			if ($order_value !== '')
				$sqlres .= $order_value;
			$sqlres .= $limit;
			
			

		}

		//echo $sqlres;
		$selectres = mysql_query($sqlres) or write_log('Ошибка MySQL: '.mysql_error().':'.$sqlres); //подсчет;
		if (@mysql_num_rows($selectres) != 0) {
			if ($action != 'selectrow')
				echo '<div id="datetable"><TABLE><THEAD>'.$titles.'<TD title = "изменение, редактирование элемента ">Редактор</TD></THEAD><TBODY>';
			while ($selectrow = mysql_fetch_row($selectres)){
				$increment_value = $selectrow[$increment_num];
				$edit_active = '"StartLink('.chr(39).$admin.chr(39).", 'edit', 'forms', 'increment', '".$increment_value."'".');"';
				$print_active = '"StartLink('.chr(39).$admin.chr(39).", 'print_id', 'forms', 'increment', '".$increment_value."'".');"';
				if ($action != 'selectrow') {
					$chet = !$chet;
					$tr_class = ($chet) ? 'nechet' : 'chet';
					
					$lock_status = lock_status($nametable, $increment_value);
					if ($lock_status)
						$substyle = 'style="color: #999999;"';
					else
						$substyle = '';

					echo('<TR id = "'.$increment_value.'" class="'.$tr_class.'" '.$substyle.' onmouseover = "Rmarker(this.id, '."'market'".');" onmouseout = "Rmarker(this.id, '."'".$tr_class."'".');">');
				}
				for ($a = 0; $a <= $maxi; $a++) {
					switch ($component[$a]['type']) {
						case 'checkbox' :
							echo('<TD>');
							if ($selectrow[$a] == 1) {
								$input_value = 'checked';
								$checked_value = '0';
							}
						    else {
								$input_value = '';
								$checked_value = '1';
							}
					        echo ('<INPUT TYPE = "checkbox" id = "'.$component[$a]['column'].$increment_value.'"'.$input_value.' onClick = "'."StartLinkActive('".$admin."' , '".$increment_value."', '".$component[$a]['column']."');".'"/>');
					        echo('</td>');
							break;

						case 'checkdate' :
						echo('<TD>');
							if ($selectrow[$a] == 1) {
								$input_value = 'checked';
								$checked_value = '0';
							}
						    else {
								$input_value = '';
								$checked_value = '1';
							}
					        echo ('<INPUT disabled TYPE = "checkbox" id = "'.$component[$a]['column'].$increment_value.'"'.$input_value.' onClick = "'."StartLinkActiveDate('".$admin."' , '".$increment_value."', '".$component[$a]['column']."', '".$component[$a]['fieldate']."');".'"/>');
					        echo('</td>');
							break;

						case 'increment':
							if ($inc_show) {
								echo('<TD>');
								echo (AnonsText($selectrow[$a], 30, 0));
								echo('</td>');
							}
							break;
				        case 'spin':
							echo('<TD>');
				            if ($selectrow[$a] != 0)
								echo $selectrow[$a];
							echo('</TD>');
						break;
						case 'file':
							echo '<TD>';
							if (in_array($selectrow[$a], array('jpg', 'jpeg', 'gif', 'png', 'JPG'))) {
								$wwwname = SITE.$component[$a]['folder'].'/'.$increment_value.'.'.$selectrow[$a];
								$fwidth = 100;
								if ($component[$a]['width'])
									$fwidth = $component[$a]['width'];
								else
									$fwidth = 100;
								echo'<a href="'.$wwwname.'" target="_blank"><img src ="'.$wwwname.'" width="'.$fwidth.'"></a>';
							}	
							echo '</TD>';
						break;
						default:
							$fulltext = $selectrow[$a];
							$texttitle = '';
							if ($component[$a]['maxtext']) {
								$maxtext = $component[$a]['maxtext'];
								if (mb_strlen($fulltext) > $maxtext) {
									$texttitle = 'title="'.$fulltext.'" ';
									$val_txt = AnonsText($selectrow[$a], $maxtext, 0);
								}
								else {
									$val_txt = $fulltext;
								}
							}
							else
								$val_txt = $fulltext;

							echo('<TD '.$texttitle.$selectrow[$a].'">');
				            if (isset($component[$a]['link'])){
								$ulink = str_replace('{%}', $selectrow[$a], $component[$a]['link']);
								//$link = str_replace('{%inc%}', $increment, $component[$a]['link']);
								echo '<a target="_blank" href ="'.$ulink.'">'.$val_txt.'</a>';
							}
							else
								echo  $val_txt;
							echo('</td>');
				        break;
				    }
				}
				echo('<td id>');

				echo('<a href="#" title = "Редактирование" onClick = '.$edit_active.' ><img id = "rbutton" src="'.PUB.'img/b_edit.png" alt="редактирование записи" /></a>');


				


				if ($link_view != '') {
					$vlink = SITE.str_replace('{%}', $increment_value, $link_view);
					echo('<a href="#" onClick = "window.open('."'".$vlink."', 'Просмотр_".$caption."', config='height=600,width=800,scrollbars=1,resizable=1');".'" title = "Просмотр"><img id = "rbutton" src="'.PUB.'img/lupa.png" alt="Просмотр" /></a>');
				}

				if (isset($ex_table)):
					$export_id=$increment_value.'_ex';?>
					<span id="<?=$export_id?>"><a onclick="sendRequest('<?=AD?>index.php?admin=<?=$admin?>&action=export&increment=<?=$increment_value?>', '<?=$export_id?>', getRequest);" title="Экспорт" href="#"><img alt="редактирование записи" src="<?=PUB?>img/export.png" id="rbutton"/></a></span>
				<?endif;

				//if ($_SESSION['readonly'] == 0)
				
				if ($deleted == 'True') {
					echo '<a href="#" title = "Удаление" onClick = "ShowModalDelete('.chr(39).$admin.chr(39).','.chr(39).$increment_value.chr(39).');"/><img id = "rbutton" src="'.PUB.'img/b_drop.png" alt="удаление записи" /></a>';
				}	


				echo('<a href="'.$_SERVER['SCRIPT_NAME'].'?admin='.$admin.'&action=print_id&increment='.$increment_value.'" title = "Печать" target="_blank"><img id = "rbutton" src="'.PUB.'img/filequickprint.png" alt="Печать записи" /></a></td>');

				if ($action != 'selectrow')  echo('</tr>');
			}
			if ($action != 'selectrow') {
				echo('<tr><td id = "counts">всего:</td><td id = "counts">'.$total_rows.'</td></tr>');   //общее кол-во
				echo('</TBODY></TABLE></div>');
				if ($action != 'select' && $action != 'selecttable') {
					if ($total_rows > $per_page) { //рисуем странички
						echo('</div><br /><div id = "page"><b>Странички: </b>
						<SELECT NAME = "pages" id="pages" onChange="'.js_func('select_page_link', array('select_id'=>'pages', 'admin'=>$admin)).'">');

						for($i = 1; $i <= $num_pages; $i++) {
							$selected = ($i == $page) ? 'selected' : '';
							echo('<option value = "'.$i.'" '.$selected.' accesskey="'.$i.'"> '.$i.'</option>');
						}
						echo('</SELECT> из <b>'.$num_pages.'</b>');
						$olimits  = array('5', '10', '15', '20', '25', '30');
						/*echo('&nbsp;&nbsp;&nbsp;&nbsp;<b>Элементы:</b>&nbsp;<select id = "limit">');
						for ($it = 0; $it < sizeof($olimits) ;$it++) {
							$selected = ($olimits[$it] == $per_page) ? 'selected' : '';
							echo('<OPTION VALUE = "'.$olimits[$it].'" onClick  = "StartLink('.chr(39).$admin.chr(39).", 'selectpage', 'main' , 'limit', '".$olimits[$it]."'".');" '.$selected.'> '.$olimits[$it]); </select>
						} */ ?>

						</div>
						</div>
					<?}
				}
			}
		}


    break;


   //подфильтр
    case 'subfilter':

		if (isset($_GET['id']))
			$id = strip_tags(($_GET['id']));

		if (isset($_GET['value']))
			$value = $_GET['value'];

		$item = $xml->xpath('/items/item');
		$item_count = sizeof($item);

		for ($it = 0; $it < $item_count; $it++) {
			if ($item[$it]->id == $id){
				if ($value > 0)
					$params['where'] = $item[$it]->lookup->subfilter->wh_column.'='.$value;
				$attrs['name'] = $item[$it]->column;
				$attrs['id'] = $attr['name'];
				echo lookup($item[$it]->lookup->table, $item[$it]->lookup->id, $item[$it]->lookup->column, 0,  $params, $attrs);
				break;
			}
		}


	break;


    //горячий checkbox
    case "active":

		$result = 0;

		if (isset($_GET['increment']))
			$increment_value = (int)($_GET['increment']);
		else
			$increment_value = 0;
		if (isset($_GET['active']))
			$active = strip_tags(($_GET['active']));
		$newactive = ($active == 'true') ? 1 : 0;
		if (isset($_GET['field']))
			$field = strip_tags($_GET['field']);
		if ($increment_value > 0) {
			write_log($_GET['fieldate']);
			if (isset($_GET['fieldate']) and $newactive == 1)
				$sql_active = mysql_query('UPDATE '.$maintable.' SET `'.$field.'` = '.$newactive.', '.'`'.$_GET['fieldate'].'` = '."'".date('Y:m:d G:i:s')."'".' WHERE `'.$increment.'` = '."'".$increment_value."'");
			else
				$sql_active = mysql_query('UPDATE '.$maintable.' SET  `'.$field.'` = '.$newactive.' WHERE `'.$increment.'` = '."'".$increment_value."'");

			$result =  ($sql_active) ? 1 : 0;
		}

		 echo (int)$result ;

		 write_log($_SERVER['PHP_AUTH_USER'].': '.'table='.$maintable.':action=active:value='.$newactive.':id='.$increment_value, 'log/edition.log');


		break;


   case "print_id":?>

		<?

		if (isset($_GET['increment']))
			$increment_value = (int)($_GET['increment']);
		else
			$increment_value = 0;

		$item = $xml->xpath('/items/item');
		$item_count = sizeof($item);

		for ($i = 0; $i < $item_count; $i++) {
			if ($item[$i]->view->printed == 'True'){
				$column = (string)$item[$i]->column;
				if ($column != $increment){
					$sql .= SqlAddSpec($sql, 0).$maintable.'.'.$column;
					if ($item[$i]->type == 'lookup'){
						$join .= ' LEFT JOIN '.separ($item[$i]->lookup->table).'  ON ('.separ($item[$i]->lookup->table).'.'.separ($item[$i]->lookup->id).'='.$maintable.'.'.separ($item[$i]->column).')';
						$sql .= SqlAddSpec($sql, 0).$item[$i]->lookup->table.'.'.$item[$i]->lookup->column;
					}
					else
						$sql .= SqlAddSpec($sql, 0).$maintable.'.'.$column;
				}
			}
		}

		$editres = mysql_query ('SELECT '.$sql.' FROM '.$maintable.$join.' WHERE '.$increment.' = '.$increment_value);
		$sqlrows = mysql_num_rows($editres);
		if ($sqlrows > 0)
			$editrow = mysql_fetch_array($editres);

		for ($f = 0; $f < $item_count; $f++) {
			if ($item[$f]->view->printed == 'True'){
				echo '<i>'.$item[$f]->title.'</i>:<br/>';
				if($item[$f]->type == 'lookup'){
					$column = (string)$item[$f]->lookup->column;
					echo $editrow[$column];
				}
				else {
					$column = (string)$item[$f]->column;
					echo $editrow[$column];
				}

				echo '<br /><br /><br />';
			}
		}


   break;



	case 'export':



		if (isset($_GET['increment']))
			$increment_value = (int)($_GET['increment']);
		else
			echo 'no';


		$source = array();
		$exvalue = array();

		$count_element = 0;


		$elements = explode(',',$ex_map);
		foreach ($elements as $element)
		{
			$count_element++;
			$params = explode('->',trim($element));
			$ex_fields[] = trim($params[0]);
			$sou_fields[] = separ(trim($params[1]));
		}


		$sql_select = 'SELECT '.implode(',',$ex_fields).' FROM '.$maintable.' WHERE '.separ($increment).'='."'".$increment_value."' LIMIT 1;";

		$data = mysql_query($sql_select)  or die("Invalid query");
		if (mysql_num_rows($data) == 0) {
			echo "error";
			break;
		}

		$row = mysql_fetch_row($data);
		for ($f = 0; $f < $count_element; $f++) {
			$curr_value = (isset($row[$f])) ? $row[$f] : 0;
			$ex_values[] = "'".addslashes($curr_value)."'";
		}

		$sql_insert = 'INSERT INTO'.separ($ex_table).' ('.implode(',',$sou_fields).') VALUES('.implode(',',$ex_values).');';
		write_log(':'.$sql_insert);

		mysql_query($sql_insert)  or die("Invalid");



		write_log($_SERVER['PHP_AUTH_USER'].': '.'table='.$maintable.':action=export :id='.$increment_value, 'log/edition.log');


	break;



  //формы  - добавления и  редактирования
   case "edit":
   case "add":

	$lockstring = lock_id($nametable, $increment_value, $_SERVER['PHP_AUTH_USER']);

	
	if ($action == 'edit') {
		if ($lockstring !==  False and $lockstring !== '')
			echo '<h2 style="color: red; font-weight: bold; ">Файл занят:&nbsp;'.$lockstring.'</h2>';
	}	
	
	$act_str = ($action == 'edit') ? 'Изменение': 'Добавление';?>
	
	<div id="caption" name="mainform" ><?=$caption?>. <?=$act_str?> - <?=$_SERVER['PHP_AUTH_USER']?> <span id="closed"><a href="javascript:closeform('<?=$nametable?>','<?=$increment_value?>');">закрыть X</a></span></div>

	<div id = "editor">
	<?php
	$item = $xml->xpath('/items/item');
	$item_count = sizeof($item);

	if ($action == 'edit') { //формируем запрос
		$f_acton = '&action=update&increment='.$increment_value;
		if(!$valid)	{
			for ($i = 0; $i < $item_count; $i++) {
				$column = (string)$item[$i]->column;
				if ($column != $increment)
					$sql .= SqlAddSpec($sql, 0).$maintable.'.'.$column;
			}
			$editres = mysql_query ('SELECT '.$sql.' FROM '.$maintable.' WHERE '.$increment.' = '.$increment_value);
			$sqlrows = mysql_num_rows($editres);
			if ($sqlrows > 0)
				$editrow = mysql_fetch_array($editres);
		}
	}
	else
		$f_acton = '&action=insert';

	$pr_form = '<FORM NAME = "fMain" id="fMain" target = "tform" ACTION ="'.AD.'index.php?admin='.$admin.$f_acton.'" METHOD = "post" enctype = "multipart/form-data" onSubmit="SubmitForm(this.id);">';
	$active_err = (isset($_SESSION['ferror']) && $_SESSION['ferror'] == 1) ? 1 :0; // узнаем ошибки ли это были или нет
	$active_err = 0;
	$_SESSION['ferror'] = 0; // сбрасываем на случай отмены
	for ($f = 0; $f < $item_count; $f++) {
		$column = (string)$item[$f]->column;
		$column_id = (isset($item[$f]->id)) ? (string)$item[$f]->id : '';
		if ($item[$f]->view->form == 'True' && $column != $increment) {
			if ($active_err == 1 ) {
				$class_valid = (isset($_SESSION['errors'][$column])) ?  $_SESSION['errors'][$column] : 'hidden';
				$column_value = $_SESSION['value'][$column];
			}
			else {
				$class_valid = 'hidden';
				if ($action == 'edit')
					$column_value = $editrow[$column];
				else if ($action == 'add') {
					if (isset($item[$f]->default)) //значение по умолчанию
						$column_value = $item[$f]->default;
					else //если есть фильтр ставим значение автоматом
						$column_value = ($item[$f]->filter && isset($_GET[$column])) ? (int)$_GET[$column] : '';
				}
			}

			$types = $item[$f]->type;
			if ($types == 'hidden' or $types == 'user')
				$title = '';
			else
				$title = ($item[$f]->title == '') ? $column : $item[$f]->title;

			if ($title != '')
				$pr_form .= '<p><label for = "'.$column.'name">'.$title.'</label></p>';
			$valtype = (isset($item[$f]->validate->type)) ? (string)$item[$f]->validate->type : '';
			/*$blur  = ($valtype != '') ? 'onblur = "validate(this.value, '."'err_".$column."', '".$valtype."'".');"' : ''; */
			switch ($types) {
				case 'textarea':
				case 'textareatiny':
					$controls[] = $column; //поля для проверки орфографии 
					
					$maxlength = (isset($item[$f]->maxsize)) ? ' maxlength = "'.$item[$f]->maxsize.'" ondrop="ismaxlength(this)" onkeypress="ismaxlength(this)" onkeydown="ismaxlength(this)" onkeyup="ismaxlength(this)" onchange="ismaxlength(this)" onfocus="ismaxlength(this)" ' : '';

					if ($types == 'textareatiny') {
						$class =  (isset($item[$f]->tinyclass)) ? $item[$f]->tinyclass : 'mceSimple';
						$class = ' class="'.$class.'"';
					}
					else
						$class = 'textarea_'.$column;

					/*onKeyUp = Len(this.id); onKeyDown = Len(this.id);*/

					if (isset($item[$f]->folder)) {
						$htmlfile = $item[$f]->folder.$increment_value.'.html';
						if (file_exists($htmlfile))
							$text = file_get_contents($htmlfile);
						else
							$text = '';
					}
					else
						$text = $column_value;

					$pr_form .=  '<p><TEXTAREA  NAME = "'.$column.'" ID = "'.$column.'"'.$class.' rows="'.$item[$f]->row.'" cols="'.$item[$f]->col.'" '.$maxlength.'>'.$text.'</TEXTAREA></p>';

					if ($types == 'textarea')
						$pr_form .=  '<p class =  "mainfilter">символов: <b id = "'.$column.'_counts"></b></p>';
					break;

				case 'text':
				case 'html':
					$controls[] = $column; //поля для проверки орфографии
					$maxlength = (isset($item[$f]->maxsize)) ? ' maxlength="'.$item[$f]->maxsize.'"' : '';
					$readonly = ($item[$f]->readonly == TRUE) ? ' READONLY': ''; //readonly
					$pr_form .=  '<p><INPUT TYPE = "text" NAME = "'.$column.'" size = "'.$item[$f]->col.'" value = "'.$column_value.'" '.$blur.$maxlength.$readonly.' /><span id = "err_'.$column.'" class = "'.$class_valid.'" >Неверное значение</span></p>';
					break;


				case 'value':
					$pr_form .=  '<p><INPUT TYPE = "hidden" NAME = "'.$column.'"  value = "'.$item[$f]->value.'" ></p>';
				break;



				case 'checkbox':
				case 'checkdate':

					$week = date('N');
					$hour = date('G');

					$checkview = True;

					/*if (isset($item[$f]->curruser)) { # слежение за юзерами
						if ($week == 6 or $week == 7)
							$checkview = True;
						elseif ($hour < 10 and $hour > 19)
							$checkview = True;
						else {
							$userres = mysql_query ('SELECT '.separ('user_id').' FROM '.$maintable.' WHERE '.$increment.' = '.$increment_value);
							if (isset($userres)) {
								$row = mysql_fetch_row($userres);
								$curruser =  $row[0];
								if ($curruser == $_SESSION['user_id'])
									$checkview = False;
							}
						}
					}*/


					if ($checkview) {
						$checked = ($column_value == 1) ? 'checked' : '';
						$pr_form .=  '<p><INPUT TYPE = "checkbox" NAME = "'.$column.'" '.$checked.'/></p>';
					}
					else
						$pr_form .= '- нет прав -';

					break;

				case 'datetime':
					$datetime =  ($column_value == '0000-00-00 00:00:00' or $column_value == '') ? date('Y:m:d G:i:s') : $column_value;
					$pr_form .=  '<INPUT TYPE = "text" NAME = "'.$column.'" value = "'.$datetime.'" '.$blur.' /><a href="#" onClick = "javascript:CalendarDT('."'".$column."'".');"><img height="16" alt="Щелкните для открытия календаря" src="cal.gif" width="16" border="0"/></a><span id = "err_'.$column.'" class = "'.$class_valid.'">Неверный формат даты</span></p>';
				break;
				case 'date':
					if ($action == 'add' & $active_err == 0) $column_value = date('Y-m-d');
					$pr_form .=  '<p><INPUT TYPE = "text" NAME = "'.$column.'" value = "'.$column_value.'" '.$blur.' /><a href="#" onClick = "javascript:CalendarD('."'".$column."'".');"><img height="16" alt="Щелкните для открытия календаря" src="cal.gif" width="16" border="0"/></a><span id = "err_'.$column.'" class = "'.$class_valid.'">Неверный формат даты</span></p>';
					break;
				case 'file':
					if ($column_value !== '') {
						$filename = $item[$f]->folder.'/'.$increment_value.'.'.$column_value;
						$id = 'fl'.$column;
						$wwwname = SITE.$item[$f]->folder.'/'.$increment_value.'.'.$column_value;
						if (in_array($column_value, array('jpg', 'jpeg', 'png', 'gif', 'JPEG')))
							$pr_form .= ' <p><IMG src="'.$wwwname.'" width="100" onClick = "window.open('."'".$wwwname."', 'Просмотр_".$wwwname."', config='height=600,width=800');".'" title="чтобы увеличить - кликните" /></span>';
						$pr_form .= '<p><span id = "'.$id.'"><INPUT  TYPE = "button" VALUE = "Удалить файл" onClick = "'."sendRequest('".AD."deletefile.php?file=".$filename."&id=".$increment_value."&column=".$column."', '".$id."', getRequest);".'" /></span>';
					}
					else 
						$pr_form .= '<p>';
					$pr_form .=  '<INPUT TYPE="file" NAME="'.$column.'" /></p>';
					
					break;
				case 'user':

					if ($action == 'edit') {
						//проверяем права доступа
						if (isset($item[$f]->access)){

							//проверка защищенных групп
							if (isset($item[$f]->access->groups)){
								$groups = explode(',', $item[$f]->access->groups);
								$accessed = (in_array($_SESSION['group'], $groups)) ? False : True;
							}
							else
								$accessed = False; //проверять у всех

							//проверять
							if ($accessed == False and ($column_value !== $this->userid)){
								echo 'Нет доступа на редактирование!';
								exit; //прерывание, нет доступа
							}
						}
					}




					$insert_type = (isset($item[$f]->user_type)) ? True : False;
					if ($action == 'edit' and $insert_type)
						$user_value = $column_value;
					else {
						$user_value = $this->userid;
					}

					$pr_form .=  '<p><INPUT TYPE = "hidden" NAME = "'.$column.'"  value = "'.$user_value.'" ></p>';
				break;

				case 'spin':
					$max = ($item[$f]->max == '') ? 100 : (int)$item[$f]->max;
					$min = ($item[$f]->min == '') ? 1 : (int)$item[$f]->min;
					$pr_form .= '<p><SELECT NAME = "'.$column.'">';
					$pr_form .= '<OPTION VALUE = "">';
					for ($s = $min; $s <= $max; $s++) {
						$checked = ($s == $column_value ) ? 'selected' : '';
						$s_view = ($s == 0) ? ' ' : $s;
						$pr_form .= '<OPTION VALUE = "'.$s.'" '.$checked.'>'.$s_view;
					}
					$pr_form .= '</SELECT></p>';
					break;





				case 'lookup':


					$look_params = array();
					$attrs = array();
					$sub_params = array();
					$sub_attrs = array();

					$subfiltered = (isset($item[$f]->lookup->subfilter)) ? True : False; //есть ли подфильтр


					if (isset($item[$f]->lookup->where))
						$look_params['where'] = (string)$item[$f]->lookup->where;
					if (isset($item[$f]->lookup->order))
						$look_params['order'] = (string)$item[$f]->lookup->order;
					if (isset($item[$f]->lookup->nulltxt))
						$look_params['null'] = (string)$item[$f]->lookup->nulltxt;

					$look_params['limit'] = 800;
					$select_id = 'select_'.$column_id;
					$attrs = array('id'=>$item[$f]->column, 'name'=>$item[$f]->column);

					if ($subfiltered) {
						$look_params['subfilter']['table']=$item[$f]->lookup->subfilter->table;
						$look_params['subfilter']['id']=$item[$f]->lookup->subfilter->id;
						$look_params['subfilter']['column']=$item[$f]->lookup->subfilter->column;
						$look_params['subfilter']['wh_column']=$item[$f]->lookup->subfilter->wh_column;
					}

					$pr_form .= '<p id="'.$select_id.'">'.lookup($item[$f]->lookup->table, $item[$f]->lookup->id, $item[$f]->lookup->column, $column_value,  $look_params, $attrs).'<p>';


					if ($subfiltered) {
						if (isset($item[$f]->lookup->subfilter->where))
							$sub_params['where'] = (string)$item[$f]->lookup->subfilter->where;
						if (isset($item[$f]->lookup->subfilter->order))
							$sub_params['order'] = (string)$item[$f]->lookup->subfilter->order;
						$sub_params['null']	= 'Все значения';


						$sub_attrs['id'] = 'subfilter_'.$item[$f]->column;
						$sub_attrs['name'] = $sub_attrs['id'];
						$sub_func = 'subfilterlink('.chr(39).$sub_attrs['id'].chr(39).','.chr(39).ADMIN.chr(39).', '.chr(39).$column_id.chr(39).','.chr(39).$select_id.chr(39).');';
						$sub_attrs['onChange'] = $sub_func;

						$pr_form .= '<p>  Фильтр: '.lookup($item[$f]->lookup->subfilter->table, $item[$f]->lookup->subfilter->id, $item[$f]->lookup->subfilter->column, 0,  $sub_params, $sub_attrs).'</p>';
					}

					$pr_form .= '</p>';

					/*$lookchange = ($subfiltered) ? 'OnChange="subfilterlink('.ADMIN.', '.$column_id.');"' : '';

					if ($where_field != '')
						$sql_res = 'SELECT '.$item[$f]->lookup->id.', '.$item[$f]->lookup->column.' FROM '.$item[$f]->lookup->table.' WHERE '.$where_field. ' Order By '.$where_field.' LIMIT 260';
					else
						$sql_res = 'SELECT '.$item[$f]->lookup->id.', '.$item[$f]->lookup->column.' FROM '.$item[$f]->lookup->table.$where.$where_order.' LIMIT 200';
					$selectres = mysql_query ($sql_res);
					if (@mysql_num_rows($selectres) != 0) {
						$pr_form .=  '<p><SELECT NAME = "'.$item[$f]->column.'" '.$lookchange.'>';
						$select0 = (isset($item[$f]->select0)) ? (string)$item[$f]->select0 : 'True';
						if ($select0 == 'True') {
							$null_value = (isset($item[$f]->lookup->null_value) ? (string)$item[$f]->lookup->null_value : 'нулевое значение');
							$pr_form .=  '<OPTION class="grays" value = "0">'.$null_value;
						//	echo '<OPTION class="grays" value = "null">пустое значение';

						}


						while ($selectrow = mysql_fetch_row($selectres)){
							$selected = ($column_value == $selectrow[0]) ? 'selected' : '';
							$pr_form .=  '<OPTION VALUE = "'.$selectrow[0].'" '.$selected.'>'. mb_substr(trim($selectrow[1]), 0, 60).'&hellip;';
						}
						$pr_form .=  '</SELECT></p>';
						if ($subfiltered) {
							$subfilter_id = 'subfilter_'.$column_id;
							$pr_form .= 'Фильтр: <SPAN name="'.$subfilter_id.'" ID="'.$subfilter_id.'">'.subfilter($column_value, $item[$f]->lookup->subfilter, $subfilter_id).'</span>';
						}
						$pr_form .= $input_pr;
						$pr_form .= '</p>';

					}*/
					break;

			}

		}
	}

	echo $pr_form;//принтеруем форму

	NullErrSession(); //стираем все на случай отмены

	/*if ($action = 'edit')
		echo('<INPUT NAME = "'.$increment.'" TYPE = "hidden" VALUE = "'.$increment_value.'" />');
	echo '<br /><p><b><INPUT name = "tipogreg" TYPE = "checkbox" class = "grays" />Отключить типографику</b></p><br />';*/

	//echo '<strong style="color: red;">не забудьте воспользоваться новой замечательной функцией</strong>';

	write_log('readonly='.$_SESSION['readonly']);

	echo '<p>';
	
	if ($_SESSION['readonly'] == 0){
		echo('<INPUT accesskey="s" TYPE = "submit" VALUE="Сохранить">');
	}

	echo '
	<button name="cmdSpell" type="button" onclick="spellCheck()" style="margin-right: 58px;">Правописание</button>';


	$ctljs = '';

	foreach ($controls as $control) {
		if ($ctljs !== '')
			$ctljs .= ', ';
		$ctljs .= 'form.'. $control;
	}

	
	echo('</p></FORM></DIV>');

	//echo('<INPUT TYPE = "button" VALUE="Отмена" OnClick = "StartLink('.chr(39).$admin.chr(39).",'cancel', '', '', '');".'"/>

	
	//echo('<INPUT TYPE="button" value="Удалить"  onClick = "ShowModalDelete('.chr(39).$admin.chr(39).','.chr(39).$increment_value.chr(39).');"/></p>


	break;


  case "insert":
  case "update":

  	//защита от пустого $POST
  	if (sizeof($_POST) == 0)
  		exit;

	require_once("validate.php");
	require_once("tipograf.php");


	$errors = 0;
	$errresult = false;

	//if ($action == 'insert') {
		$div_res = 'content';
		$admin_res = 'selectall';
//	}
//	else {
//		$div_res = $increment_value;
//		$admin_res = 'selectrow';
//	}

	NullErrSession();


	$item = $xml->xpath('/items/item');
	$item_count = sizeof($item);
	$fa = 0;
	$tip_reg = (isset($_POST['tipogreg'])) ? false : true; /* вкл-выкл типографики */
	for ($i = 0; $i <= $item_count - 1; $i++) {
		$posts = (string)$item[$i]->column;
		if ($posts !== '') {
			$column_id = (isset($item[$i]->id)) ? (string)$item[$i]->id : '';
			$key = $posts;
			$type = (string)$item[$i]->type;
			$values = null;
			if (isset($item[$i]->view->form))
				$view_form = ($item[$i]->view->form == 'True') ? 'True' : 'False';
			else
				$view_form = 'False';
		}
		else
			$view_form = 'False';

		if ($view_form == 'True') {

			switch ($type) {
				case 'checkbox':
				case 'checkdate':
					
					$activation = True;

					if (isset($item[$i]->count) and $item[$i]->count !== ''){
						$ccolumn =(string)$item[$i]->count;
						if (isset($_POST[$ccolumn])) {
							$txtonly = $_POST[$ccolumn];
							$txtonly = html_entity_decode($txtonly);
							$txtonly = str_replace("&nbsp;", '', $txtonly);
							//$txtonly = str_replace(" ", '', $txtonly);
							$txtonly = preg_replace('/&([a-zA-Z0-9]{2,6}|#[0-9]{2,4});/', '', $txtonly);
							$txtonly = str_replace('|+|amp|+|', '&', $txtonly);	
							$txtonly = strip_tags($txtonly);
							$txtonly = trim($txtonly);
							$len = mb_strlen($txtonly, 'UTF-8');
							if ( $len > 1200 and in_array($_POST['sitepart_id'], array(1, 3, 21)) ) {
								$values = null;
								break;
							}	
						}						
						
					}


					

					if (isset($_POST[$posts])){
						$values = '1';
						$checkdated = True;
					}
					else {
						$values = '0';
						$checkdated = False;
					}
					
					$activation = True;
										
				break;

				case 'file':
					$file_increment[$fa] = $i;
					$activation = False;
					$fa ++;
					break;
				case 'increment':
					$activation = False;
					break;
				case 'lookup':
					$values = (isset($_POST[$posts])) ? (int)$_POST[$posts] : 0;
					write_log($posts.'='.$values);
					$activation = True;
					break;
				case 'date':
					$values = (isset($_POST[$posts])) ? $_POST[$posts] : '';
					$activation = True;
					break;
				case 'user':
					$values = (isset($_POST[$posts])) ? (int)$_POST[$posts] : 0;
					$activation = True;
					break;	
				default:
					$values = (isset($_POST[$posts])) ? $_POST[$posts] : '';
					$values = str_replace('img src="../images', 'img src="http://www.argumenti.ru/images', $values); //хак для полного адреса фоток
					$values = str_replace('img src="../photo', 'img src="http://www.argumenti.ru/photo', $values); //хак для полного адреса фоток
					//$values = preg_replace('/<!--.*-->/Uis', '', $values);
					if ($type == 'text')
						$values = $filter->source($values)->entity('html');
					$values = addslashes($values);
					if (isset($item[$i]->folder)) {
						file_put_contents($item[$i]->folder.$increment_value.'.html',$values);
						$activation = False;
					}	
					
					
					$vald_type = (string)$item[$i]->validate->type;
					$errors = validate($values, $vald_type);

					if ($errors == 0) {
						$err_value = 'error';
						if ($item[$i]->validate->critic)
							$errresult = true;
						}
					else {
						/*$err_value = 'hidden';
						if ($tip_reg) {
							$tip_type = ($item[$i]->tipograf->type != '') ? $item[$i]->tipograf->type : 'standart';
							$tip_kill = ($item[$i]->tipograf->killstyle == 'true') ? true : false;
							$values = tipograf($values, $tip_type, $tip_kill);
						}*/
					}
					$activation = True;
					break;
			}


			//типографика
			$tip_reg = False;
		/*	if ($item[$i]->typograf == 1 and $tip_reg){
				$jerrors = null;
				$jevix = new Jevix();
				$values = $jevix->parse($values, $jerrors);
				write_log($jevix->parse('\&quot;Зениит\&quot;', $jerrors));
				$values = tp_quotes($values);
			}*/


			$exxmlwhere = '';
			//экслюзивность значения, в остальных полях идет сброс его
			if (isset($item[$i]->exclusive) and $item[$i]->exclusive->value !== $values) {
				$exclusives[$posts] = array('value'=>$values, 'reset'=>$item[$i]->exclusive->value);
				if (isset($item[$i]->exclusive->where))
					$exxmlwhere = trim($item[$i]->exclusive->where);
					if ($exxmlwhere !== '') {
						$exclusives[$posts]['where'] = $item[$i]->exclusive->where;
					}
					if (isset($item[$i]->exclusive->wherefields)){
						$exclusives[$posts]['wherefields'] = explode(',', $item[$i]->exclusive->wherefields);

					}
				else
					$exxmlwhere = '';
			}


			if ($type != 'file') {
				$_SESSION['errors'][$posts] = $err_value;
				$_SESSION['value'][$posts] = $values;
			}
			if (!$errresult) {
				if ($activation) {
					if ($posts !== '') {
						$values = chr(39).$values.chr(39);
						$save_items[$posts] = $values;
						$posts = '`'.$posts.'`';
						if ($action == 'insert') {
							$incolumns .= SqlAddSpec($incolumns, 0).$posts;
							$value_insert .= SqlAddSpec($value_insert, 0).$values;
							if ($type == 'checkdate' and $checkdated) {
								$incolumns .= SqlAddSpec($incolumns, 0). separ($item[$i]->fieldate);
								$value_insert .= SqlAddSpec($value_insert, 0).quote(date('Y-m-d G:i:s'));
							}
						}
						else if ($action == 'update') {
							//	if ($item[$i]->filter) {
							//	if (isset($_GET[$posts]) && (int)$_GET[$posts] != $values) {
							//		$div_res = 'content';
							//		$admin_res = 'selectall';
							//	}
							//	}
							
							if ($values !== null)
								$sql_update .= SqlAddSpec($sql_update, 0).$posts.' = '.$values;
							
							if ($type == 'checkdate' and $checkdated) { # публикация

								$actupdate = separ($item[$i]->fieldate).' = '.quote(date('Y-m-d G:i:s'));

								//$actupdate = 'UPDATE '.$maintable.' SET  '.$actupdate.' WHERE '.separ($increment).' = '."'".$increment_value."'".' AND '.separ($item[$i]->fieldate).' = '.quote('0000-00-00 00:00:00').' OR '.separ($item[$i]->fieldate).'  IS NULL';
								$actupdate = 'UPDATE '.$maintable.' SET  '.$actupdate.' WHERE '.separ($increment).' = '."'".$increment_value."'".' AND '.separ($item[$i]->fieldate).'  IS NULL';
								mysql_query($actupdate) or write_log('Ошибка MySQL: '.mysql_error().' SQL:'.$actupdate);

								# дата
								$actupdate = 'UPDATE '.$maintable.' SET  '.$actupdate.' WHERE '.separ($increment).' = '."'".$increment_value."'".' AND '.separ($item[$i]->fieldate).'='.quote('0000-00-00 00:00:00');
								mysql_query($actupdate) or write_log('Ошибка MySQL: '.mysql_error().' SQL:'.$actupdate);

								# user
								if (isset($item[$i]->curruser)) {
									$usersql = 'UPDATE '.$maintable.' SET  '.separ($item[$i]->curruser).'='.$this->userid.' WHERE '.separ($increment).' = '."'".$increment_value."'";
									mysql_query($usersql) or write_log('Ошибка MySQL: '.mysql_error().' SQL:'.$usersql);
								}
							}
						}
					}
				}
			}
		}	

		//копим все полученные значения с ключом
		$allvalues[$key] = $values;



	}


	if ($errresult) {
		$_SESSION['ferror'] = 1;
		$newaction = ($action == 'update') ? 'edit' : 'add';
		echo("<SCRIPT>window.parent.StartLink('".$admin."', '".$newaction."', 'forms', '', '');</SCRIPT>");
	}
	else {
	    $_SESSION['ferror'] = 0;
		if ($action == 'insert')
			$sqltext = 'INSERT INTO '.$maintable.' ('.$incolumns.') VALUES ('.$value_insert.')';
		else if ($action == 'update')
			$sqltext = 'UPDATE '.$maintable.' SET  '. $sql_update.' WHERE '.$increment.' = '."'".$increment_value."'";

		$sqlres = mysql_query($sqltext);

		if (!$sqlres)
		 	write_log('Ошибка MySQL: '.mysql_error().' sql:'.$sqltext);
		
		if ($sqlres) { //если запрос прошел успешно
			if ($action == 'insert') {  // значение инкремента
				$inc_indx = mysql_insert_id($this->link);
				//echo 'созданный id = '.mysql_insert_id($this->link);
			}	
			else
				$inc_indx = $increment_value;

			$save_items[$increment] = $inc_indx;

			//обработка экслюзивных значений
			if (isset($exclusives)) {
				foreach ($exclusives as $exc_key => $excl){
					$exupdwhere = ' WHERE '.$exc_key.' = '.$excl['value'].'  AND '.$increment.' <> '."'".$inc_indx."'";
					if (isset($excl['where'])){
						$exwhere = $excl['where'];
						preg_match_all("{(\w+)}", $exwhere, $exwherearr);
						if (isset($exwherearr)) {
							foreach ($exwherearr as $exwh){
								$key = $exwh[1];
								if (isset($allvalues[$key]));
									$exwhere = str_replace('{'.$exwh[0].'}', $allvalues[$key], $exwhere);
							}
						}
						$exupdwhere .= ' AND '.$exwhere;
					}

					if (isset($excl['wherefields'])){
						foreach ($excl['wherefields'] as $exfvalue){
							$exfvalue = trim($exfvalue);
							if ($allvalues[$exfvalue] > 0) {
								$exupdwhere .= ' AND '.separ($exfvalue).'='.$allvalues[$exfvalue];
								break;
							}
						}
					}

					$upd_sql = 'UPDATE '.$maintable.' SET '.$exc_key.'='.$excl['reset'].$exupdwhere;
					write_log('exclusive: '.$upd_sql);
					
					$upd_result = mysql_query($upd_sql);
					if (!$upd_result)
						write_log('Ошибка MySQL: '.mysql_error().' SQL:'.$upd_sql);
				}
			}

			
			for ($l = 0; $l < $fa; $l ++) { //загрузка файло
				$indx = $file_increment[$l];
				$column = (string)$item[$indx]->column;
				if(isset($_FILES[$column])){
					if ($_FILES[$column]["name"] != '') {
						$f_exp = fileexpansion($_FILES[$column]["name"]);
						if (in_array($f_exp, array('jpg','jpeg','JPG', 'gif', 'png', 'swf'))) {
							$newfilename = SITEPATH.$item[$indx]->folder.'/'.$inc_indx.'.'.$f_exp;
							echo $newfilename;
							if (copy($_FILES[$column]["tmp_name"], $newfilename)) {
								$file_update = mysql_query('UPDATE '.$maintable.' SET '.$column.' = '."'".$f_exp."'".' WHERE '.$increment.' = '.$inc_indx);
								chmod($newfilename, 0666);
							}
							else
								echo "<SCRIPT>alert('Файл '".$newfilename."'не сохранен!')</SCRIPT>";
						}

						unlink($_FILES[$column]["tmp_name"]);
					}
				}
				if (isset($_POST['DFile_'.$column])){ //признак или несуществующего  файла
						
						$file_update = mysql_query('UPDATE '.$maintable.' SET '.$column.' = '."''".' WHERE '.$increment.' = '.$inc_indx);
						if (!$file_update)
							write_log('Ошибка MySQL: '.mysql_error());
				
				}		
			}
		}

		if ($action == 'update')
			unlock_id($nametable, $inc_indx, $_SERVER['PHP_AUTH_USER']);

		$increm = ($action == 'insert') ? $inc_indx : $increment_value;

		define('INC_VALUE', $increm);
		$history_save = True;

		if ($history_save) {
			$currdate = date('Y-m-d H:i:s'); //текущая дата
			$histoty_file = set('site_fold_ad').'history/'.$nametable.'/'.date_to_url($currdate, False).'/'.$nametable.'_'.$increm.'_'.date_to_url($currdate, True,'_').'.json'; //файл истории запроса sql
			save($histoty_file, json_encode($save_items)); //сохраняем историю
		}

		if (isset($fcache))
			delete_cache($fcache, $increm);


		if (isset($count_items))
			counts($count_items, $allvalues);

		
		write_log($_SERVER['PHP_AUTH_USER'].': '.'table='.$maintable.':action='.$action.':id='.$inc_indx.' save:'.$histoty_file, 'log/edition.log');
		echo("<SCRIPT>window.parent.StartLink('".$admin."','".$admin_res."' ,'".$div_res."', '', '');</SCRIPT>");
	}

break;


case "unlock":  
	unlock_id($nametable, $increment_value, $_SERVER['PHP_AUTH_USER']);
break;

  case "delete":


	if (isset($_GET['increment']))
	    $increment_value = (int)($_GET['increment']);
	else
	    $increment_value = 0;

	if ($increment_value > 0)
	    $sql_delete = mysql_query ('DELETE FROM '.$maintable.' WHERE '.$increment.' = '."'".$increment_value."'");
    if (isset($fcache))
		delete_cache($fcache, $increment_value);

	write_log($_SERVER['PHP_AUTH_USER'].': '.'table='.$maintable.':action=delete :id='.$increment_value, 'log/edition.log');
	echo("<SCRIPT>window.parent.StartLink('".$admin."', 'selectall', 'content', '', '');</SCRIPT>");

   break;

 }

 }
 }
