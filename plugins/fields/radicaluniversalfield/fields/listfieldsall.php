<?php defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;

FormHelper::loadFieldClass('list');
JLoader::register('ParamsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/ParamsHelper.php');
JLoader::register('PathsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/PathsHelper.php');

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
		$exclude_attr   = $this->getAttribute('exclude', '');

		if (!empty($exclude_attr))
		{
			$exclude_attr_array = explode(',', $exclude_attr);
			if (is_array($exclude_attr_array))
			{
				$exclude_fields = array_merge($exclude_fields, $exclude_attr_array);
			}
		}


		$options = [
			/*(object) [
				'text'  => 'Clean XML',
				'value' => 'cleanxml',
			]*/
		];

		$fields = PathsHelper::getFields();
		foreach ($fields as $field)
		{
			if (in_array($field, $exclude_fields))
			{
				continue;
			}

			$option        = new stdClass();
			$option->value = $field;
			$option->text  = $field;
			$options[]     = $option;
		}


		return array_merge(parent::getOptions(), $options);
	}


}