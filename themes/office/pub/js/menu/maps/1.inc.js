function createjsDOMenu() {
     
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Номера", "", script+'?admin=numbers'));
	addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
	addMenuItem(new menuItem("Цитаты", "", script+'?admin=citata'));
	addMenuItem(new menuItem("Книги", "", script+'?admin=books'));
	addMenuItem(new menuItem("Топ", "", script+'?admin=topnews'));

	}
     
  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Аргументы Недели", "", script + '?admin=news'));
	addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar'));
	addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
	addMenuItem(new menuItem("АН-онлайн-корр", "", script + '?admin=anonline_korr'));
	addMenuItem(new menuItem("Благотворительность", "", script + '?admin=charity'));
	addMenuItem(new menuItem("Вся лента", "", script + '?admin=news_lite'));
	addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
	addMenuItem(new menuItem("Юмор", "", script + '?admin=humornew'));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Сюжеты", "", script+'?admin=subject'));
	}
  
    
  absoluteMenu3 = new jsDOMenu(140, "absolute");
  with (absoluteMenu3) {
    addMenuItem(new menuItem("Фотографии", "", script + '?admin=photes_gallery'));
	addMenuItem(new menuItem("Фотогалереи", "", script + '?admin=photogallery'));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Фотобанк", "", script + '?admin=photobank'));
    addMenuItem(new menuItem("Рубрики фотобанка", "", script + '?admin=photocategory'));
  }

  
    
  
   absoluteMenu4 = new jsDOMenu(180, "absolute");
  with (absoluteMenu4) {
    addMenuItem(new menuItem("Ваше мнение: Вопросы", "", server + "admins.php?admin=mnequest"));
	addMenuItem(new menuItem("Ваше мнение: Ответы", "", server + "admins.php?admin=mneanswer"));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Комментарии АН", "", script + '?admin=comments_an'));
	addMenuItem(new menuItem("Комментарии АРУ", "", script + '?admin=comments_news'));
	addMenuItem(new menuItem("Комментарии LIVE", "", script + '?admin=comments_live'));
	addMenuItem(new menuItem("Комментарии  к конкурсам", "", script + '?admin=comments_concurs'));
	addMenuItem(new menuItem("Комментарии  к фотографиям", "", script + '?admin=comments_photo'));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Регистратор ошибок", "",  script + '?admin=error'));
	addMenuItem(new menuItem("Пользователи", "",  script + '?admin=users'));
    }
  
  
  absoluteMenu5 = new jsDOMenu(140, "absolute");
  with (absoluteMenu5) {
    addMenuItem(new menuItem("Отделы", "", server + "admins.php?admin=regions"));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Регионы", "", server + "admins.php?admin=regions"));
  }
  
  
   absoluteMenu5 = new jsDOMenu(140, "absolute");
	with (absoluteMenu5) {
		addMenuItem(new menuItem("Регистрация он-лайн", "", script + '?admin=online'));
		addMenuItem(new menuItem("Вопросы и ответы", "", script + '?admin=onlinequest'));
		addMenuItem(new menuItem("-"));
		addMenuItem(new menuItem("Пресс-конференции", "", script + '?admin=presscenter'));
	

  }
  
  absoluteMenu6 = new jsDOMenu(140, "absolute");
	with (absoluteMenu6) {
		addMenuItem(new menuItem("Пользователи", "", server + "admins.php?admin=users"));
		addMenuItem(new menuItem("Группы", "", server + "admins.php?admin=groupuser"));
		addMenuItem(new menuItem("Пересоздать доступ", "", "http://www.argumenti.ru/passwords/create"));
		addMenuItem(new menuItem("-"));
		addMenuItem(new menuItem("Регионы", "",script + '?admin=regions'));
		
  }
  
  
 absoluteMenu7 = new jsDOMenu(140, "absolute");
  with (absoluteMenu7) {
    addMenuItem(new menuItem("Региональные статьи", "", script + '?admin=regionpubls'));
	addMenuItem(new menuItem("Региональные новости", "", script + '?admin=rnews'));
	}
  
  
  absoluteMenu8 = new jsDOMenu(140, "absolute");
  with (absoluteMenu8) {
	addMenuItem(new menuItem("Баннеры", "",script + '?admin=adv_items2'));
	addMenuItem(new menuItem("Баннерные места", "",script + '?admin=adv_space2'));
	addMenuItem(new menuItem("-"));	
	addMenuItem(new menuItem("Контекстная реклама", "", script + '?admin=contexts'));
	addMenuItem(new menuItem("Seoстатьи", "", script + '?admin=seopubls'));
	addMenuItem(new menuItem("Seoконторы", "", script + '?admin=seocompany'));
	addMenuItem(new menuItem("-"));	
	addMenuItem(new menuItem("ПОп-ТВ: сериалы", "", script + '?admin=tvseries'));
	addMenuItem(new menuItem("ПОп-ТВ: эпизоды", "", script + '?admin=episode'));
	}
	
	
	absoluteMenu9 = new jsDOMenu(180, "absolute");
  	with (absoluteMenu9) {
   		addMenuItem(new menuItem("Работа с фотобанком", "", 'http://argumenti.ru/adanar/pub/docs/photobank.html'));
	}

	absoluteMenu10 = new jsDOMenu(180, "absolute");
  	with (absoluteMenu10) {
   		addMenuItem(new menuItem("Task", "", script + '?admin=tasks'));
	}

	absoluteMenu11 = new jsDOMenu(180, "absolute");
  	with (absoluteMenu11) {
		addMenuItem(new menuItem("Опросы АР", "", script + '?admin=poll_news'));
		addMenuItem(new menuItem("Варианты для опросов АP", "", script + '?admin=votes_news'));
		addMenuItem(new menuItem("-"));
		addMenuItem(new menuItem("Опросы АН", "", script + '?admin=poll_an'));
		addMenuItem(new menuItem("Варианты для опросов АН", "", script + '?admin=votes_an'));
	}	
  
   
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Каталоги", absoluteMenu1));
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
    addMenuBarItem(new menuBarItem("Опросы", absoluteMenu11));
	addMenuBarItem(new menuBarItem("Комментарии", absoluteMenu4));
	addMenuBarItem(new menuBarItem("Фото", absoluteMenu3));
	addMenuBarItem(new menuBarItem("Пресс-центр", absoluteMenu5));
	addMenuBarItem(new menuBarItem("Реклама", absoluteMenu8));
	addMenuBarItem(new menuBarItem("Task", absoluteMenu10));
	addMenuBarItem(new menuBarItem("Помощь", absoluteMenu9));
	setActivateMode("over");
  }
}