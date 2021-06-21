# Шаблоны поля
Вы создаете свой файл php в любых из указанных ниже путей и указываете его в настройках поля.

### Пути шаблонов
/templates/<ваша тема>/html/plg_fields_radicaluniversalfield<br/>
/templates/<ваша тема>/html/layouts/plugin/fields/radicaluniversalfield<br/>
/layouts/plugin/fields/radicaluniversalfield<br/>

### Имена файлов
Используйте только буквы латинского алфавита и цифры.

### Что писать внутри шаблона?
Вам сначала надо извлечь все переменные командой
```php 
extract($displayData);
```

После этого у вас есть переменная **$field**. У этого объекта есть свойство value. Дальше вы пишите уже свой html который вам нужно и выгружаете туда данные от поля.


### Если сабформа
Если вы используете сабформу, то у вас данные придут в закодированной json строке. Вам надо ее распарсить функцией json_decode.

Пример
```php
$values = json_decode($field->value, JSON_OBJECT_AS_ARRAY);
```
Флаг JSON_OBJECT_AS_ARRAY, говорит о том чтобы выходящий результат был именно массивом и все его вложенности, не объектами


### Примеры выгрузок
Выгрузка сабформы в виде таблице:
```php
<?php defined('JPATH_PLATFORM') or die;
extract($displayData);

$parse = json_decode($field->value, JSON_OBJECT_AS_ARRAY);

?>

<table>
    <thead>
        <tr>
            <th>Картинка</th>
            <th>Название</th>
            <th>Описание</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($parse as $item) : ?>
            <tr>
                <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>"/></td>
                <td><?php echo $item['title']; ?></td>
                <td><?php echo $item['description']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```


Выгрузка одиночного поля text без сабформы
```php
<?php defined('JPATH_PLATFORM') or die;
extract($displayData);
?>
<span class="myfield"><?php echo $field->value ?></span>
```