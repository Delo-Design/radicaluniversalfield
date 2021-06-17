<?php

use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

class PathsHelper
{


	public static function get()
	{

		// загружаем все стандартные поля
		$paths = [
			'libraries/joomla/form/fields'
		];

		// загружаем поля от библиотеки lib_fields
		$path_lib_fields      = 'libraries/lib_fields/fields';
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


	public static function getFields()
	{

		$fields = [];

		$paths = self::get();

		foreach ($paths as $path)
		{
			$path_current = Path::clean(JPATH_ROOT . '/' . $path);
			$files        = Folder::files($path_current);

			foreach ($files as $file)
			{
				$fields[] = str_replace('.php', '', $file);
			}
		}

		return $fields;
	}


}