# Как загрузить свои поля JForm

- Найти поля JForm (это php классы), которые хотите загрузить. Если у вас есть компоненты сторонние и вы хотите эти же поля загрузить, например, в материалы, то посмотрите файловую структуру компонента и найдите эти классы, которые содержат в названии классов "JFormField". Обычно такие файлы кладут в: administrator/components/<выбранный компонент>/models/fields 
- Открыть настройки плагина "Поля - Универсальное поле загрузки полей Form (JForm)"
- Вставьте нужные вам пути для полей в "Дополнительные пути для загрузки полей" через новую строчку (без DOCUMENT_ROOT, то есть относительный путь от того где установлена Jomla)

В Joomla! есть всякие поля из родных компонентов, можете их просмотреть тут: [Стандартные поля JForm Joomla](https://github.com/Delo-Design/radicaluniversalfield/blob/master/docs/listfields.md)