function createjsDOMenu() {

  

  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
	  addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
    addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Юмор", "", script + '?admin=humornew'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Регистратор ошибок", "",  script + '?admin=error'));
	}

absoluteMenu3 = new jsDOMenu(140, "absolute");
  with (absoluteMenu3) {
    addMenuItem(new menuItem("Фотографии", "", script + '?admin=photes_gallery'));
    addMenuItem(new menuItem("Фотогалереи", "", script + '?admin=photogallery'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
  }

 
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
      addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
      addMenuBarItem(new menuBarItem("Фото", absoluteMenu3));
      setActivateMode("over");
  }
}
