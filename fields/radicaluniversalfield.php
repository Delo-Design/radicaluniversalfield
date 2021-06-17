<?php defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Path;

JFormHelper::loadFieldClass('subform');
JLoader::register('ParamsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/ParamsHelper.php');
JLoader::register('PathsHelpers', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/PathsHelpers.php');

/**
 * Class JFormFieldRadicaluniversalfield
 */
class JFormFieldRadicaluniversalfield extends JFormFieldSubform
{


	public function getInput()
	{
		$field_name = $this->fieldname;

		// получаем все параметры поля
		$params = ParamsHelper::get($field_name);

		// запускаем объект указанного поля
		$paths = PathsHelper::get();
		foreach ($paths as $path)
		{
			FormHelper::addFieldPath(Path::clean(JPATH_ROOT . '/' . $path));
		}

		// получаем поля getInput и возвращаем
		$xml = ParamsHelper::build($params);
		$class_name = 'JFormField' . ucfirst($params->get('rtype'));

		if(class_exists($class_name))
		{
			$field = new $class_name();
			$field->setup($xml);
			return $field->getInput();
		}

		return parent::getInput();
	}


}
