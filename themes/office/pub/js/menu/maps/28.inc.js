function createjsDOMenu() {

  

  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
      addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline_lite'));
      addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
      addMenuItem(new menuItem("-"));
      addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
   
  /*addMenuItem(new menuItem("-"));
  addMenuItem(new menuItem("Афоризмы", "", script + '?admin=aforms'));
  addMenuItem(new menuItem("Анекдоты", "", script + '?admin=anekdots'));*/

    }


 
   absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
      addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
  setActivateMode("over");
  }
}
