function createjsDOMenu() {
    
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Р РµРіРёРѕРЅР°Р»СЊРЅС‹Рµ РЅРѕРІРѕСЃС‚Рё", "", script+'?admin=rnews'));
	}
   
 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("РљР°С‚Р°Р»РѕРіРё", absoluteMenu1));
     setActivateMode("over");
	}
}