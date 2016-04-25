<?

function showphotocadr($f, $newF, $type)
{
// f - имя файла 
// type - способ масштабирования 
// q - качество сжатия 
// src - исходное изображение 
// dest - результирующее изображение 
// w - ширниа изображения 
// ratio - коэффициент пропорциональности 
// str - текстовая строка 

// тип преобразования, если не указаны размеры 
if ($type == 's') $w = 120;
  else if ($type == 'b') $w = 180;
   else if ($type == 'm') $w = 180;
    else return false;


// качество jpeg по умолчанию 
if (!isset($q)) $q = 100;


$src = imagecreatefromjpeg($f); 
$w_src = imagesx($src); 
$h_src = imagesy($src);

// если размер исходного изображения 
// отличается от требуемого размера 
if ($w_src != $w) 
{
// операции для получения прямоугольного файла 
   if ($type=='m') 
   { 
       // вычисление пропорц ий 
       $ratio = $w_src/$w; 
       $w_dest = round($w_src/$ratio); 
       $h_dest = round($h_src/$ratio); 

       // создаём пустую картинку 
       // важно именно truecolor!, иначе будем иметь 8-битный результат 
       $dest = imagecreatetruecolor($w_dest,$h_dest); 
       imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src); 
   }
   

  // операции для получения квадратного файла 
    if (($type=='s')||($type=='b')) 
    { 
         // создаём пустую квадратную картинку 
         // важно именно truecolor!, иначе будем иметь 8-битный результат 
         $dest = imagecreatetruecolor($w,$w); 

         // вырезаем квадратную серединку по x, если фото горизонтальное 
         if ($w_src>$h_src) 
         imagecopyresampled($dest, $src, 0, 0,
                          round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                          0, $w, $w, min($w_src,$h_src), min($w_src,$h_src)); 

         // вырезаем квадратную верхушку по y, 
         // если фото вертикальное (хотя можно тоже серединку) 
         if ($w_src<$h_src) 
         imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $w,
                          min($w_src,$h_src), min($w_src,$h_src)); 

         // квадратная картинка масштабируется без вырезок 
         if ($w_src==$h_src) 
         imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $w, $w_src, $w_src); 
     } 

	// вывод картинки и очистка памяти 
	imagejpeg($dest,$newF,$q); 
	imagedestroy($dest); 
	imagedestroy($src); 
 } 
   
   return true;
   
   
 }


 
 function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
  {
 // if (!file_exists($src)) return false;

  $size = getimagesize($src);

  if ($size == false) return false;

  // Определяем исходный формат по MIME-информации, предоставленной
  // функцией getimagesize, и выбираем соответствующую формату
  // imagecreatefrom-функцию.
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;

  $x_ratio = $width / $size[0];
  $y_ratio = $height / $size[1];

  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);

  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
  $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
  $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

    
  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($width, $height);

  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);

 // echo($format);
  imagejpeg($idest,$dest,$quality);
  imagedestroy($isrc);
  imagedestroy($idest);

  return true;

}

 

function showphoto($publ_id, $number_id, $typeimg)
{
  
  if ($number_id < 54){
     $Folder = 'big';
     if ($typeimg == 'b') 
	    $typeimg = 's';
  }
 else 
   $Folder = 'cashe';
  
  $search = False;
  $filename = '';
      
  if(file_exists(site_fold.'images/'.$Folder.'/'.$publ_id.$typeimg.'.jpg')){
	  //$filename = 'http://www.argumenti.ru/images/'.$Folder.'/'.$publ_id.$typeimg.'.jpg';
	  $filename = site.'images/'.$Folder.'/'.$publ_id.$typeimg.'.jpg';
	  $search = True;
	}
  else if (file_exists(site_fold.'images/'.$Folder.'/'.$publ_id.$typeimg.'.JPG')){
    //$filename = 'http://www.argumenti.ru/images/'.$Folder.'/'.$publ_id.$typeimg.'.jpg';
    $filename = site.'images/'.$Folder.'/'.$publ_id.$typeimg.'.JPG';
	$search = True;
    } 
  else if (file_exists(site_fold.'images/'.$Folder.'/'.$publ_id.$typeimg.'.gif')){
	  // $filename = 'http://www.argumenti.ru/images/'.$Folder.'/'.$publ_id.$typeimg.'.jpg';
	   $filename = site.'images/'.$Folder.'/'.$publ_id.$typeimg.'.gif';
	   $search = True;
	}
  else if (file_exists(site_fold.'images/'.$Folder.'/'.$publ_id.$typeimg.'.GIF')){
	  // $filename = 'http://www.argumenti.ru/images/'.$Folder.'/'.$publ_id.$typeimg.'.jpg';
       $filename = site.'images/'.$Folder.'/'.$publ_id.$typeimg.'.GIF';
	   $search = True;
    }
  


   if (!$search) {
      if ($typeimg == 's'){
        $width = 120;
	    $height = 120;
	    } 
	 else if ($typeimg == 'm'){
	    $width = 200;
	    $height = 200;
	   }
	 else if ($typeimg == 'b'){
	    $width = 180;
	    $height = 180;
	} 
	  
 	  
	if(file_exists(site_fold.'images/news/'.$publ_id.'.jpg')){
		if (showphotocadr(site_fold.'images/news/'.$publ_id.'.jpg', site_fold.'/images/cashe/'.$publ_id.$typeimg.'.jpg',$typeimg))
         	//$filename =  site.'images/cashe/'.$publ_id.$typeimg.'.jpg';
			$filename =  site_fold.'images/cashe/'.$publ_id.$typeimg.'.jpg';
	}
	   
	if(file_exists(site_fold.'images/news/'.$publ_id.'.JPG')){
		if (showphotocadr(site_fold.'images/news/'.$publ_id.'.JPG', site_fold.'/images/cashe/'.$publ_id.$typeimg.'.JPG',$typeimg))
		//   $filename =  site.'images/cashe/'.$publ_id.$typeimg.'.JPG';
		$filename =  site_fold.'images/cashe/'.$publ_id.$typeimg.'.JPG';
	}
	   
	if(file_exists(site_fold.'images/news/'.$publ_id.'.JPG')){
		if (showphotocadr(site_fold.'images/news/'.$publ_id.'.gif', site_fold.'/images/cashe/'.$publ_id.$typeimg.'.gif',$typeimg))
		   //$filename =  site.'images/cashe/'.$publ_id.$typeimg.'.gif';
		   $filename =  site_fold.'images/cashe/'.$publ_id.$typeimg.'.gif';
	}
	   
	if(file_exists(site_fold.'images/news/'.$publ_id.'.GIF')){
		if (showphotocadr(site_fold.'images/news/'.$publ_id.'.GIF', site_fold.'/images/cashe/'.$publ_id.$typeimg.'.GIF',$typeimg))
		   //$filename =  site.'images/cashe/'.$publ_id.$typeimg.'.GIF';
		   $filename =  site_fold.'images/cashe/'.$publ_id.$typeimg.'.GIF';
	}	
	   
	   
   }
  
     
   return $filename;
  
  }

?>
