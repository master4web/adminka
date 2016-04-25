function CalendarDT (MyElement)
{
  
  var cal = new calendar3(document.fMain.elements[MyElement]);
  
  cal.year_scroll = false;
  cal.time_comp = true;
  cal.popup();
  
  return  True;
  
}

function CalendarD (MyElement)
{
  
  var cal = new calendar3(document.fMain.elements[MyElement]);
  
  cal.year_scroll = false;
  cal.time_comp = false;
  cal.popup();
  
  return  True;
  
}
