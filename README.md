# Radical universal field

### Что делает плагин?
Это универсальный плагин для компонента кастомных полей (компонент com_fields) в Joomla! <br/>
Он позволяет использовать любые поля для класса Form (JForm). <br/>
Считайте это конструктором XML для Form, которые пишутся для моделей в компонентах. <br/>
Такой плагин позволяет использовать любые поля, которые есть в Joomla! и в сторонних расширениях в материалах, пользователях и контактах.


### Чем отличается от поля Radical Multi Field?
Я не стал делать версию radical multi field 4.x. Мультиполе пусть остается для совместимости таким каким сейчас и является. Сабформой с импортом файлов.<br/>
Radical universal field является завершающим этапом идей мультиполя.<br/>
В новых проектах рекомендую использовать именно Radical universal field. Потому что я принесу сюда в будущем импорт файлов из мультиполя и в нем необязательно создавать сабформу, можно просто указать одиночное поле JForm.


### Требования
- php >= 7.1
- Joomla >= 3.9


### Зависимости
Если вы скачаете мастер ветку и установите, то плагин поставится, но не будет работать. <br/>
Требуется установленные расширения указанные в списке:
- [lib_fields](https://github.com/JPathRu/lib_fields)


### Где скачать?
- В релизах в этом репозитории. [Просмотреть](https://github.com/Delo-Design/radicaluniversalfield/releases)
- Или последний архив с hika.su. [Скачать](https://hika.su/builds/free/pkg_radicaluniversalfield.zip)


###  Документация
- [Настройки поля](https://github.com/Delo-Design/radicaluniversalfield/blob/master/docs/params.md)
- [Как загрузить свои поля JForm](https://github.com/Delo-Design/radicaluniversalfield/blob/master/docs/loadfields.md)
- [Стандартные поля JForm Joomla](https://github.com/Delo-Design/radicaluniversalfield/blob/master/docs/listfields.md)
- [Как создать шаблоны](https://github.com/Delo-Design/radicaluniversalfield/blob/master/docs/templates.md)