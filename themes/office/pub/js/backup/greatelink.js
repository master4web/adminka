
var admin ='';
var mainlink = '';


function GreateLink(admin, actions, param_name, param_value)
 {
	var link = '';
	var admin_default = '';
	var action_default = '';
	var param_default = '';
	var div_name = '';
	var st = document.getElementById("adress").innerHTML;
		
	//if (window.admin != trim(admin)) 
	//	st = 'admin=' + admin; // сброс всех параметров
	//else	
		st = window.mainlink; 
		
	if (st.length > 0) {
		st = st.split("?")[1]; // получаем параметры
		if (st.length > 0) {
			st = st.replace(/&amp;/g, "&"); //защита от  интепретации символа
			var Params = st.split("&");
			Params_len = Params.length;
			//	var Params = location.hash.substring(1).split("&");	
			for (var i = 0; i < Params_len; i++) {
				param = Params[i].split("=")[0];
				if (param != '') {
					loc_param_value = Params[i].split("=")[1];
					switch (param) {
						case param_name:
							//param_default = Params[i].split("=")[1];
							break
						case 'action':
							action_default = loc_param_value;
							break
						case 'admin':
							admin_default = loc_param_value;
							break
						case 'field':
							if (actions == 'active' || actions == 'selecttd') 
								link += AddSpec(link) + 'field=' + loc_param_value;
							break
						case 'active':
							if (actions == 'active') 
								link += AddSpec(link) + 'active=' + loc_param_value;
							break						
						case 'order':
							if (actions != 'order')
								link += AddSpec(link) + param + '=' + loc_param_value;
							break
						case 'ordertype':
							if (actions != 'order')
								link += AddSpec(link) + param + '=' + loc_param_value;
							break
						case 'like':
							link += AddSpec(link) + param + '=' + loc_param_value;
							break
						case 'like_id':
							link += AddSpec(link) + param + '=' + loc_param_value;
							break	
						case '':
							break
						default:	
							link += AddSpec(link) + param + '=' + loc_param_value;
					}
				}	
			}
		}	
	}
	
	if (param_name != '') {
		if (actions == 'order') {
			link += AddSpec(link) + 'order=' + param_name;	
			if (param_value == 1)
				link += AddSpec(link) + 'ordertype=' + param_value;	
		}
		else {
			if  (param_value != '')
				link += AddSpec(link) + param_name + '=' + param_value;	
		}		
	}	
		
		
	if (actions == '')
		link += AddSpec(link) + 'action' + '=' + action_default;
	else if (actions == 'order')
		link += AddSpec(link) + 'action=selectpage';
	else	
		link += AddSpec(link) + 'action' + '=' + actions;	
		
	if (admin == '')
		admin = admin_default;
		
	link += AddSpec(link) + 'admin' + '=' + admin;
	window.admin = admin;
	
	return script + link;
  
 }  
  
 
 function  StartLinkActive (admin, increment, field, data_field)
	{
		
		id = field + increment;
		active = document.getElementById(id).checked;
		MyLink = script + '?admin=' + admin + '&action=active' + '&field=' + field + '&active=' + active + '&increment=' + increment + '&data_field=' + data_field;
		sendRequest(MyLink, id, getRequestActive);
	}
	
	function  StartLinkActiveDate (admin, increment, field,  fieldate)
	{
	
		id = field + increment;
		active = document.getElementById(id).checked;
		MyLink = script + '?admin=' + admin + '&action=active' + '&field=' + field + '&active=' + active + '&increment=' + increment + '&fieldate=' + fieldate;
		sendRequest(MyLink, id, getRequestActive);
	
	}
 
  
 
 function subfilterlink(ob, admin, id, content)
 {
	
 	var objSel = document.getElementById(ob);
	
	value = objSel.options[objSel.selectedIndex].value;
	var MyLink =  script + '?admin='+admin+'&action=subfilter&id='+id+'&value='+value;
	sendRequest(MyLink, content, getRequest);
	
	
	
 }
 
 
 function select_page_link(select_id, admin)
 {
	var objSel = document.getElementById(select_id);
	if ( objSel.selectedIndex != -1){
		var curr_value = objSel.options[objSel.selectedIndex].value;
		StartLink( admin, 'selectpage', 'main',  'page', curr_value);
	}

 }
 
 
 function select_filter(select_id, admin, param_name)
 {
	
	var objSel = document.getElementById(select_id);
	if ( objSel.selectedIndex != -1){
		var curr_value = objSel.options[objSel.selectedIndex].value;
		StartLink(admin, 'selectpage', 'main',  param_name, curr_value);
	}
	
}
 
 
 
 
 function  StartLink (admin, actions, div_name, param_name, param_value)
	{
		var DivF = false;
		var Send = true;
		var MyLink = '';
							
			switch (actions) {
				case 'select': 
					getReq_name = getRequest;
					break
				case 'order':
					getReq_name = getRequest;
					break
				case 'selectall':
					getReq_name = getRequest;
					break
				case 'selectrow':
					getReq_name = getRequest;
					break	
				case 'selectpage':
					getReq_name = getRequest;
					break
				case 'add':
					DivF = true;
					getReq_name = getRequestEditor;
					break
				case 'print_id':
					DivF = true;
					getReq_name = getRequestEditor;
					break	
				case 'edit':
					DivF = true;
					getReq_name = getRequestEditor;
					break
				case 'cancel':
					Send = false;
					TinyAdd(0);
					break				
				case 'subfilter':
					
					break
				default: 
					getReq_name = getRequest;
					break
			}

						
			MyLink = GreateLink(admin, actions, param_name, param_value);
									
			if (DivF)
				document.getElementById("forms").style.visibility = "visible";
			else {	
				if 	(document.getElementById("forms").style.visibility == "visible"){
					document.getElementById("forms").style.visibility = "hidden";
					$('.mce-tinymce').hide();
					scroll(0, 0);
				}	
			}	
	
		if (Send) 
			sendRequest(MyLink, div_name, getReq_name);
    		
		//обновление ссылки
		//document.getElementById("adress").innerHTML = MyLink;
		window.mainlink = MyLink;
	
	}
 
 
	function SubmitForm(form)
	{
		
		/*tinyMCE.triggerSave();
		TinyAdd (0);*/
		form.Submit;
	}
	
	
	
	function AddSpec(str)
	{
		if (str == '')
			return '?';
		else
			return '&';
	}
 
 
 
	function Len (id)
	{
		column = id + '_counts';
		document.getElementById(column).innerHTML = document.getElementById(id).value.length;
	}
 
 
	function Rmarker(id, classname)
	{
		document.getElementById(id).className = classname;
	}
	
	function autoupdate(admin)
	{
		if (document.getElementById('chkupdate').checked){
			buttupdates(admin);
			setTimeout("autoupdate('" + admin + "')", 30000);
		}	
	}
				
	function buttupdates(admin)
	{
		StartLink(admin, 'selecttable','datetable', '', '');
	}
	