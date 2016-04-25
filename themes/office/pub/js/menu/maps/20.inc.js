function createjsDOMenu() {

  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
	/*addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));*/
	addMenuItem(new menuItem("-"));
	}

  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar_non_r'));
    addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
  }

  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Каталоги", absoluteMenu1));
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    setActivateMode("over");
  }
}
