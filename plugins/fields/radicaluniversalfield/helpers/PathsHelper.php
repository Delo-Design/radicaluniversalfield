<?php defined('_JEXEC') or die;

use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

JLoader::register('ConfigHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/ConfigHelper.php');

class PathsHelper
{


	public static $template;


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

		$extend_fields = ConfigHelper::get('extendfield', '');

		if (!empty($extend_fields))
		{
			$extend_fields_paths = explode("\n", $extend_fields);
			$paths               = array_merge($paths, $extend_fields_paths);
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
			$folders      = Folder::folders($path_current);

			foreach ($files as $file)
			{
				$content = file_get_contents($path_current . '/' . $file);
				if(strpos($content, 'JFormField') === false)
				{
					continue;
				}

				$fields[] = str_replace('.php', '', $file);
			}

			foreach ($folders as $folder)
			{
				$files = Folder::files($path_current . '/' . $folder);
				foreach ($files as $file)
				{
					$content = file_get_contents($path_current . '/' . $folder . '/' . $file);
					if(strpos($content, 'JFormField') === false)
					{
						continue;
					}

					$fields[] = $folder . '_' . str_replace('.php', '', $file);
				}
			}
		}

		return $fields;
	}


	public static function getLayouts()
	{
		return [
			'{TEMPLATES}/html/plg_fields_radicaluniversalfield',
			JPATH_PLUGINS . '/fields/radicaluniversalfield/tmpl',
		];
	}


}