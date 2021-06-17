<?php

use Joomla\Filesystem\Folder;

class PathsHelper
{


	public static function get()
	{

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

		return $paths;
	}


}