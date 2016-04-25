function createjsDOMenu() {

  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
	/*addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));*/
	addMenuItem(new menuItem("-"));
	}

  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Аргументы Недели", "", script + '?admin=news'));
    addMenuItem(new menuItem("Комментарии АН", "", script + '?admin=comments_an'));
    	
	/*addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Афоризмы", "", script + '?admin=aforms'));
	addMenuItem(new menuItem("Анекдоты", "", script + '?admin=anekdots'));*/

    }


 /* absoluteMenu3 = new jsDOMenu(140, "absolute");
  with (absoluteMenu3) {
    addMenuItem(new menuItem("Анонсы RSS", "item1", ""));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Интерфакс", "", server + 'interfax.php'));
  }*/

  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    setActivateMode("over");
  }
}
