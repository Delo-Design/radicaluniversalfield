<?php defined('JPATH_PLATFORM') or die;
extract($displayData);

$parse = json_decode($field->value, JSON_OBJECT_AS_ARRAY);

if(is_array($parse))
{
	echo '<code>' . print_r($parse) . '</code>';
}
else
{
	echo $field->value;
}