function createjsDOMenu() {
     
  
    
  absoluteMenu3 = new jsDOMenu(140, "absolute");
  with (absoluteMenu3) {
    addMenuItem(new menuItem("Фото", "", script + '?admin=photobank'));
    addMenuItem(new menuItem("Тематика", "", script + '?admin=photocategory'));
  }

  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Аргументы Недели", "", script + '?admin=news'));
    addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar'));
    addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Номера", "", script+'?admin=numbers'));
 
  }      
  
 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    addMenuBarItem(new menuBarItem("Фотобанк", absoluteMenu3));
    setActivateMode("over");
  }


}