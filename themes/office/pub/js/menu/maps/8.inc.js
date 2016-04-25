function createjsDOMenu() {
     
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
	addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
	addMenuItem(new menuItem("-"));
	}
     
  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar'));
    addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Комментарии АРУ", "", script + '?admin=comments_news'));
    addMenuItem(new menuItem("Регистратор ошибок", "",  script + '?admin=error'));
	}

   absoluteMenu9 = new jsDOMenu(180, "absolute");
    with (absoluteMenu9) {
      addMenuItem(new menuItem("Работа с фотобанком", "", 'http://argumenti.ru/adanar/pub/docs/photobank.html'));
  }

    
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Каталоги", absoluteMenu1));
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    addMenuBarItem(new menuBarItem("Помощь", absoluteMenu9));
    setActivateMode("over");
  }

}