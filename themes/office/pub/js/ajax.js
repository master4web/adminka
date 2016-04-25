/*
 * Возвращает новый XMLHttpRequest объект или false, если браузер его не поддерживает
 */

function createHttpRequest() {
 var httpRequest;
 var browser = navigator.appName;

 if (browser == "Microsoft Internet Explorer") {
   httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
   }
  else {
     httpRequest = new XMLHttpRequest();
    }

 return httpRequest;
}


function sendRequest(file, _resultId, request)
{
  
  	 
	resultId = _resultId;
	document.getElementById(resultId).innerHTML = 'Идет загрузка данных&hellip';
	httpRequest.open('GET', file);
	httpRequest.onreadystatechange = request;
	httpRequest.send(null);

	
	  
}


function getRequestEditor() {
  
  if (httpRequest.readyState == 4) {
     document.getElementById(resultId).innerHTML = httpRequest.responseText;
	 document.getElementById(resultId).focus();
	 tinysetup();
	 return True;
   }
 else
	return False;
}


function getRequest() {
  
  if (httpRequest.readyState == 4) {
    document.getElementById(resultId).innerHTML = httpRequest.responseText;
	scroll(0, 0);
	return True;
   }
 else
	return False;
}

function getRequestActive() {
  	
if (httpRequest.readyState == 4) {
		if (httpRequest.responseText != 1)
			document.getElementById(resultId).checked = !document.getElementById(resultId).checked;
		document.getElementById(resultId).focus();
		return True;
	}
	else
		return False;
		
}


function getRequestPost() {
  
  if (httpRequest.readyState == 4) 
     sendRequest(filename, resultId);

}


function FormToPost(obj, filePost, fileres, result_id)
 {      
   
   var send_post = '';
   var elcount = 0;

    for(var k = 0; k < obj.elements.length; k++) {
        if  ((obj.elements[k].type != "button") & (obj.elements[k].type != "submit") & (obj.elements[k].type != "file")) {
		    if (obj.elements[k].type == "checkbox"){
			   if (elcount > 0) send_post += '&';
			   send_post += obj.elements[k].name + "=" +  obj.elements[k].checked;
			}
            else if (obj.elements[k].value != '') {
		       if (elcount > 0) send_post += '&';
			   send_post += obj.elements[k].name + "=" +  obj.elements[k].value; 
		       elcount ++;
		    } 
	    }	  
    }  
  
  
  if (httpRequest){  
    try { 
	  resultId = result_id;
	  filename = fileres;
	  httpRequest.open("POST", filePost, true);
	  httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  httpRequest.onreadystatechange = getRequestPost;
	  httpRequest.send(send_post);
     }
   catch (e){ 
     alert('Невозможно соединиться с сервером:\n' + e.toString());
    }   
  } 
  
 }
 
 
 //включаем TinyMCE
	function TinyAdd (type) {
/*		var oEditor = '';
		tinyMCE.idCounter = 0;
		for (var i = 0; i < document.fMain.elements.length; i++) {
			if (document.fMain.elements[i].className == 'tiny') { 
				element = document.fMain.elements[i].id;
				if (type == 1) {
					oEditor = document.getElementById(element);
					if(oEditor && !bTextareaWasTinyfied) {
						tinyMCE.execCommand('mceAddControl', true, element);
						bTextareaWasTinyfied = true;
	                }
				}	 
				else { 
					oEditor = document.getElementById(element);
					if(oEditor && bTextareaWasTinyfied) {
						tinyMCE.execCommand('mceRemoveControl', true, element);
						bTextareaWasTinyfied = false;
					}	
				}
			}	
		}  */ 
	}


var httpRequest = createHttpRequest();
var resultId = '';
var filename = '';

