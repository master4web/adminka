<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);


// for agreements
define("site", 'http://admin.my/');
define("AD", site);
define("site_fold", '/home/user/Sites/adanar/');
define("site_fold_ad", site_fold);
define('APPPATH', '/home/user/Sites/adanar/app/');
define("site_ad", AD);

define('psite', '/home/user/Sites/'); // попка сайта
define('sysfold', psite.'system/'); // папка ядра
define('THEME', APPPATH.'themes/office/');
define("PUB", THEME.'/pub/');


define('maintitle', 'Аргументы Недели');


kORM::config('default', 'localhost', 'root', '', 'argumentiru');

//$link=@mysql_connect('localhost', 'argumentiru', 'Qx6UFnvjpRC3MwxQ') or die ('Нет связи с базой : ' . mysql_error());
$link=@mysql_connect('localhost', 'root', '') or die ('Нет связи с базой : ' . mysql_error());

 
mysql_select_db('argumentiru', $link) or die ('Can\'t use foo : ' . mysql_error());
mysql_query("SET NAMES UTF8");