<?php defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

FormHelper::loadFieldClass('list');

/**
 * Class JFormFieldListfieldsall
 */
class JFormFieldListfieldsall extends JFormFieldList
{


	protected $type = 'listfieldsall';


	protected $name = 'listfieldsall';


	public function getOptions()
	{
		$exclude_fields = [
			'subformmore'
		];
		$exclude_attr = $this->getAttribute('exclude', '');

		if(!empty($exclude_attr))
		{
			$exclude_attr_array = explode(',', $exclude_attr);
			if(is_array($exclude_attr_array))
			{
				$exclude_fields = array_merge($exclude_fields, $exclude_attr_array);
			}
		}


		$options = [
			(object) [
				'text'  => 'Clean XML',
				'value' => 'cleanxml',
			]
		];

		// загружаем все стандартные поля
		$paths = [
			'libraries/joomla/form/fields'
		];

		// загружаем поля от библиотеки lib_fields
		$path_lib_fields = 'libraries/lib_fields/fields';
		$path_lib_fields_full = JPATH_ROOT . '/' . $path_lib_fields;

		if (file_exists($path_lib_fields_full))
		{
			$folders = Folder::folders($path_lib_fields_full);
			foreach ($folders as $folder)
			{
				$paths[] = $path_lib_fields . '/' . $folder;
			}
		}

		foreach ($paths as $path)
		{
			$path_current = Path::clean(JPATH_ROOT . '/' . $path);
			$files        = Folder::files($path_current);

			foreach ($files as $file)
			{
				$field = str_replace('.php', '', $file);

				$exclude_find = false;
				foreach ($exclude_fields as $exclude)
				{
					if (strpos($file, $exclude) !== false)
					{
						$exclude_find = true;
						break;
					}
				}

				if ($exclude_find)
				{
					continue;
				}

				$option        = new stdClass();
				$option->value = $field;
				$option->text  = $field;
				$options[]     = $option;
			}
		}

		return array_merge(parent::getOptions(), $options);
	}


}