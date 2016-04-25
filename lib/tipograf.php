<?

error_reporting(E_ERROR);
 
 
define('NOBRSPACE',  "\x03");
define('NOBRHYPHEN', "\x04");
define('THINSP',     "\x05");
define('DASH',       "\x06");
define('NUMDASH',    "\x07");
 
 
 
 function entity_code($text,$ctype){
  $htmlents = array(
		'&#8222;'=>'„','&#8219;'=>'“','&#8220;'=>'”','&#8216;'=>'‘','&#8217;'=>'’',
		'&laquo;'=>'«','&raquo;'=>'»','&hellip;'=>'…','&euro;'=>'€','&permil;'=>'‰',
		'&bull;'=>'•','&middot;'=>'·','&ndash;'=>'–','&mdash;'=>'—','&nbsp;'=>' ',
		'&trade;'=>'™','&copy;'=>'©','&reg;'=>'®','&sect;'=>'§','&#8470;'=>'№',
		'&plusmn;'=>'±','&deg;'=>'°');
  
  if (ctype == 0)
    $text = strtr($text, $htmlents ); // Делаем замены html entity на символы из cp1251
  else {
       $text = strtr($text, array_flip($htmlents)); // Делаем замены на html-entity 
       $text = str_replace( '"','&quot;', $text ); // Заменяем " на &quot;
       $text = str_replace( "'",'&#39;', $text ); // Заменяем ' на &#39; 
 }
	
  return $text;
  
  }
   
    
  
  function tipograf($text, $type, $stylekill)
 {
 
 
 //ОБЩИЕ 
// $text = entity_code($text,0); //замены сущностей
// $text = str_replace( '&nbsp;',' ', $text);//преобразовываем пробелы
 $text = trim($text); // начальные и конечные пробелы
 
 
 //обработка стилистики тегов
  if ($stylekill) {
     //работа с тегами 
     $text = preg_replace("!<div[^>]*?>(.*?)</div>!si","<p>\\1</p>",$text);	
     $text = preg_replace("!<strong[^>]*?>(.*?)</strong>!si","<b>\\1</b>",$text);
	 $text = preg_replace("!<em[^>]*?>(.*?)</em>!si","<i>\\1</i>",$text);
    
	 //вырезаем ненужные теги
     $text = strip_tags($text,"<b><i><p><br><ul><ol><li><img>");
  }	 
  
    
	if ($type == 'tags') {
		$text = nl2br($text);// разрывы строк  на  <br />
		$text = preg_replace( "'<br ?/?>'", '</p><p>', $text ); //преобразуем переносы в параграфы 
		$text = preg_replace("'<p.*?>(.*?)</p>'si","<p>\\1</p>",$text); //убиваем стилистики абзацев
		$text = preg_replace( "'<p>(.*?)<(.*?)> *</\\2>(.*?)</p>'si", '<p>\\1\\3</p>', $text ); //убиваем пустые вложенные теги
		$text = preg_replace("'<p> +(.*?)</p>'",'<p>&nbsp;&nbsp;&nbsp;\\1</p>', $text);//пробелы в начале абазца преобразовываем в табуляцию<p>
		$text = preg_replace("'<p>(.*?) +</p>'",'<p>\\1</p>', $text);//убиваем пробелы в конце  <p>
        $text = preg_replace( "'</p> +<p>'", '</p><p>', $text );//убиваем пробелы между параграфами
		$text = preg_replace( "'<([a-zA-Z]+)>[ | ]*</\\1>'", '', $text ); //убиваем пустые теги или теги из пробелов 
	} 
 
 
   //работа с текстом
  $text = preg_replace( "'\.{3}'", '&#133;', $text);  //Многоточие
 // $text = preg_replace("'\.+'",'.',$text); // двойные точки
  $text = preg_replace("'\,+'",',',$text); // двойные запятые
  $text = preg_replace("'\;+'",';',$text); // двойные ;
  $text = preg_replace("'\:+'",':',$text); // двойные :
      
  $text = preg_replace( "' +'", ' ', $text); // Убираем лишние пробелы
  $text = preg_replace( "'\t+'", ' ', $text); // Убираем лишние табуляторы
  $text = preg_replace( '/\( *([^)]+?) *\)/', '(\\1)', $text ); // удаляем пробелы после открывающей скобки и перед закрыващей скобкой
  $text = preg_replace( '/([а-яА-ЯёЁa-zA-Z.,!?:;…])\(/', '\\1 (', $text ); // добавляем пробел между словом и открывающей скобкой, если его 
  
  //знаки припинания
  $text = preg_replace("' ?(\.|,|\!|\?)'",'\\1', $text ); // Убираем пробелы перед знаками препинания*/
  $text = preg_replace("'(\w+),(\w+)'",'\\1, \\2', $text); // Пробелы после знаков препинания
  $text = preg_replace("'(\d+), ?(\d+)'",'\\1,\\2', $text); // Пробелы между цифрами
       
  //$text = preg_replace( "'(\S+)-(\S+)'", '<nobr>$1-$2</nobr>', $text ); //не обрывать слова, написанные через дефис
  
      
  // Русские денежные суммы, расставляя пробелы в нужных местах.
  $text = preg_replace( '~(\d+)\s?(руб.)~s','$1&nbsp;$2', $text );
  $text = preg_replace( '~(\d+)\s?(млн.|тыс.)?\s?(руб.)~s','$1&nbsp;$2&nbsp;$3', $text );
  
  //праивльное обозначение размера
  $text = preg_replace( '~(\d+)[x|X|х|Х|*](\d+)~','$1&times;$2', $text );
 
 //спецсимволы 
  $text = preg_replace( '/\((c|C|с|С)\)/','©', $text);//копирант
  $text = str_replace( '(tm)','™', $text );
  $text = str_replace( '(TM)','™', $text ); // trademark
 
//неразрывать
  $text = preg_replace( "'(\w\.)\s?(\w\.)\s(\w\w+)'", '$1&nbsp;$2&nbsp;$3', $text ); // Инициалы + фамилия
  $text = preg_replace( "'(\w\w+)\s?(\w\.)\s(\w\.)'", '$1&nbsp;$2&nbsp;$3', $text ); // фамилия + инициалы
  $text = preg_replace("'(\W\w\.)\s(\w\w+)'",'$1&nbsp;$2',$text); //один инициал + фамилия  
   
 //последние обработки
 $text = str_replace( '!?','?!', $text ); // Правильно в таком порядке
 $text = str_replace( '№ №', '№№', $text ); // слитное написание "№№"
 $text = str_replace( '§ §', '§§', $text ); // слитное написание "§§"
 
 $text = entity_code($text,1); //обратные замены
 
 $text = str_replace( '','&nbsp;', $text);//старые косяки
 $text = str_replace( '','&nbsp;', $text);//старые косяки
  
 return $text;
 
 
 //убийца шрифтов 
/*$text = preg_replace("!<font[^>]*?>(.*?)</font>!si","\\1",$text); //убиваем стилистики абзацев
$text = preg_replace("!<span[^>]*?>(.*?)</span>!si","\\1",$text); //убиваем стилистики абзацев*/

 
 }
?>
  
  