<?php defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\Filesystem\Path;

JFormHelper::loadFieldClass('text');
JLoader::register('ParamsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/ParamsHelper.php');
JLoader::register('PathsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/PathsHelper.php');

/**
 * Class JFormFieldRadicaluniversalfield
 */
class JFormFieldRadicaluniversalfield extends JFormFieldText
{


	public function getInput()
	{
		$field_name = $this->fieldname;

		// получаем все параметры поля
		$params = ParamsHelper::get($field_name);

		$params->set('rname', $field_name);
		$params->set('rlabel', $this->label);
		$params->set('rdescription', $this->description);

		// запускаем объект указанного поля
		$paths  = PathsHelper::get();
		$fields = PathsHelper::getFields();

		foreach ($paths as $path)
		{
			FormHelper::addFieldPath(Path::clean(JPATH_ROOT . '/' . $path));
		}

		foreach ($fields as $field)
		{
			JFormHelper::loadFieldClass($field);
		}

		// получаем поля getInput и возвращаем
		$xml        = ParamsHelper::build($params);
		$class_name = 'JFormField' . ucfirst($params->get('rtype'));

		if (class_exists($class_name))
		{
			$field = new $class_name();
			$field->setup(new SimpleXMLElement($xml), $this->value);
			return $field->getInput();
		}

		return parent::getInput();
	}


}
