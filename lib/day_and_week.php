 <?     
 
 function monthName($nmonth)
  {
    $mese[1]="января";
	$mese[2]="февраля";
	$mese[3]="марта";
	$mese[4]="апреля";
	$mese[5]="мая";
	$mese[6]="июня";
	$mese[7]="июля";
	$mese[8]="августа";
	$mese[9]="сентября";
	$mese[10]="октября";
	$mese[11]="ноября";
	$mese[12]="декабря";
  	    
	$mes1 = (int)$nmonth;
	if ($mes1 != 0)
	  return $mese[$mes1];
	 else
       return "";	 
  }	
		
	

function AddNull($num)
{
  if ((int)$num < 10)
     return  '0'.$num;
   else
     return $num;    
}	
	
function weekName($nweek)
{
	
	$giorno[0]="Воскресенье";
	$giorno[1]="Понедельник";
	$giorno[2]="Вторник";
	$giorno[3]="Среда";
	$giorno[4]="Четверг";
	$giorno[5]="Пятница";
	$giorno[6]="Суббота";
	
    return $giorno[$nweek];
}

function DateNotNull($dd)
{
   $d1 = (int)$dd;
   return (string)$d1;
  
}

//преобразование даты в формат RFC 822
function  DateToRFC822($date)
{
  $datatime = explode(" ",$date); //Вместо date() результат из базы
  $dater = explode("-",$datatime[0]);
  $timer = explode(":",$datatime[1]);

  return date('r', mktime($timer[0], $timer[1], $timer[2], $dater[1], $dater[2], $dater[0])); 

}


function DateToStr($date)
{
  
   $begin_date = explode('-',$date);
   return DateNotNull($begin_date[2]).' '.monthName(DateNotNull($begin_date[1])).' '. $begin_date[0];

}


?>