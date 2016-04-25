function createjsDOMenu() {

   

  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Номера", "", script+'?admin=numbers'));
    addMenuItem(new menuItem("Авторы", "", script + '?admin=authors'));
    addMenuItem(new menuItem("Темы", "", script+'?admin=specialnews'));
  }


  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Аргументы Недели", "", script + '?admin=news'));
    addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
    addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
    addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
	}

   absoluteMenu3= new jsDOMenu(180, "absolute");
    with (absoluteMenu3) {
     addMenuItem(new menuItem("Фотографии", "", script + '?admin=photes_gallery'));
      addMenuItem(new menuItem("Фотогалереи", "", script + '?admin=photogallery'));
   }


 absoluteMenu4 = new jsDOMenu(180, "absolute");
  with (absoluteMenu4) {
    addMenuItem(new menuItem("Комментарии АН", "", script + '?admin=comments_an'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Регистратор ошибок", "",  script + '?admin=error'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Опросы АН", "", script + '?admin=poll_an'));
    addMenuItem(new menuItem("Варианты для опросов АН", "", script + '?admin=votes_an'));
   }


   absoluteMenu5 = new jsDOMenu(140, "absolute");
	with (absoluteMenu5) {
		addMenuItem(new menuItem("Регистрация он-лайн", "", script + '?admin=online'));
		addMenuItem(new menuItem("Вопросы и ответы", "", script + '?admin=onlinequest'));
		addMenuItem(new menuItem("-"));
		addMenuItem(new menuItem("Пресс-конференции", "", script + '?admin=presscenter'));

  }


 

   absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
   with (absoluteMenuBar) {
      addMenuBarItem(new menuBarItem("Каталоги", absoluteMenu1));
      addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
      addMenuBarItem(new menuBarItem("Интерактив", absoluteMenu4));
      addMenuBarItem(new menuBarItem("Фотогалереи", absoluteMenu3));
   	  addMenuBarItem(new menuBarItem("Пресс-центр", absoluteMenu5));
	    setActivateMode("over");
  }

}
