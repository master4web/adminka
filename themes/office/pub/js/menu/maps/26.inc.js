function createjsDOMenu() {
     
  absoluteMenu1 = new jsDOMenu(180, "absolute");
  with (absoluteMenu1) {
    addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
	addMenuItem(new menuItem("Цитаты", "", script+'?admin=citata'));
	addMenuItem(new menuItem("-"));
	}
     
  absoluteMenu2 = new jsDOMenu(140, "absolute");
  with (absoluteMenu2) {
    addMenuItem(new menuItem("Аргументы Недели", "", script + '?admin=news'));
	addMenuItem(new menuItem("Аргументы.ру", "", script + '?admin=ar'));
	addMenuItem(new menuItem("АН-онлайн", "", script + '?admin=anonline'));
    addMenuItem(new menuItem("Спорт", "", script + '?admin=sport'));
	addMenuItem(new menuItem("Медали", "", script + '?admin=prize'));
	addMenuItem(new menuItem("Страны", "", script + '?admin=country'));
	
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Таблойд", "", script + '?admin=tabloid'));
	addMenuItem(new menuItem("Афиша", "", script + '?admin=broadcast'));
	addMenuItem(new menuItem("Опросы АР", "", script + '?admin=poll_news'));
	addMenuItem(new menuItem("Варианты для опросов АP", "", script + '?admin=votes_news'));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Опросы АН", "", script + '?admin=poll_an'));
	addMenuItem(new menuItem("Варианты для опросов АН", "", script + '?admin=votes_an'));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Сюжеты", "", script+'?admin=subject'));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Фотографии", "", script + '?admin=photes_gallery'));
	addMenuItem(new menuItem("Фотогалереи", "", script + '?admin=photogallery'));
	}
  
    
  absoluteMenu3 = new jsDOMenu(140, "absolute");
  with (absoluteMenu3) {
    addMenuItem(new menuItem("Блоки", "", script + '?admin=topics'));
	addMenuItem(new menuItem("Анонсы RSS", "item1", ""));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("Интерфакс", "", server + 'interfax.php'));
  }

  
  absoluteMenu3_1 = new jsDOMenu(150, "absolute");
  with (absoluteMenu3_1) {
    addMenuItem(new menuItem("Все ленты", "", "http://www.argumenti.ru/rss/all/1"));
    addMenuItem(new menuItem("-"));
    addMenuItem(new menuItem("MAIL-RU", "",  "http://www.argumenti.ru/rss/feed/mailru/create/1"));
	addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Наша лента", "",  "http://www.argumenti.ru/rss/feed/argumenti/create/1"));
    addMenuItem(new menuItem("Yandex", "",  "http://www.argumenti.ru/rss/feed/yandex/create/1"));
    addMenuItem(new menuItem("Rambler", "",  "http://www.argumenti.ru/rss/feed/rambler/create/1"));
    addMenuItem(new menuItem("SMI-RU", "", "http://www.argumenti.ru/rss/feed/smiru/create/1"));
	
  }
  
  
   absoluteMenu4 = new jsDOMenu(180, "absolute");
  with (absoluteMenu4) {
    addMenuItem(new menuItem("Ваше мнение: Вопросы", "", server + "admins.php?admin=mnequest"));
	addMenuItem(new menuItem("Ваше мнение: Ответы", "", server + "admins.php?admin=mneanswer"));
    addMenuItem(new menuItem("-"));
	addMenuItem(new menuItem("Комментарии к статьям и новостям", "", script + '?admin=comments_news'));
	addMenuItem(new menuItem("Комментарии к блогам", "", script + '?admin=comments_posts'));
	addMenuItem(new menuItem("Комментарии АРУ", "", script + '?admin=comments_ar'));
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
	addMenuItem(new menuItem("Баннеры", "",script + '?admin=adv_items'));
	addMenuItem(new menuItem("-"));	
	addMenuItem(new menuItem("Информеры", "", script + '?admin=informers'));    
	addMenuItem(new menuItem("Контекстная реклама", "", script + '?admin=contexts'));
	addMenuItem(new menuItem("Seoстатьи", "", script + '?admin=seopubls'));
	addMenuItem(new menuItem("Seoконторы", "", script + '?admin=seocompany'));
	addMenuItem(new menuItem("-"));	
	addMenuItem(new menuItem("ПОп-ТВ: сериалы", "", script + '?admin=tvseries'));
	addMenuItem(new menuItem("ПОп-ТВ: эпизоды", "", script + '?admin=episode'));
	}
	
	
	absoluteMenu9 = new jsDOMenu(180, "absolute");
  with (absoluteMenu9) {
   addMenuItem(new menuItem("Блоги", "", script+'?admin=blogs'));
    addMenuItem(new menuItem("Посты", "", script+'?admin=posts_admin'));
	}
  
  absoluteMenu3.items.item1.setSubMenu(absoluteMenu3_1);
  //absoluteMenu1.items.item2.setSubMenu(absoluteMenu1_1);
  //absoluteMenu2.items.item4.setSubMenu(absoluteMenu2_1);
 
  
  absoluteMenuBar = new jsDOMenuBar("static", "staticMenuBar");
  with (absoluteMenuBar) {
    addMenuBarItem(new menuBarItem("Контент", absoluteMenu2));
	addMenuBarItem(new menuBarItem("Блоги", absoluteMenu9));
    addMenuBarItem(new menuBarItem("Реклама", absoluteMenu8));
	setActivateMode("over");
  }
}