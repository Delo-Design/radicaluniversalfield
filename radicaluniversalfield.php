<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseDriver;
use Joomla\Filesystem\Path;

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);
JLoader::register('ParamsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/ParamsHelper.php');
JLoader::register('PathsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/PathsHelper.php');

/**
 * Radical universal field plugin.
 *
 * @package  radicaluniversalfield
 * @since    1.0
 */
class PlgFieldsRadicaluniversalfield extends FieldsPlugin
{


	/**
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{


		// Check if the field should be processed by us
		if (!$this->isTypeSupported($field->type))
		{
			return null;
		}

		// Detect if the field is configured to be displayed on the form
		if (!FieldsHelper::displayFieldOnForm($field))
		{
			return null;
		}

		// получаем все параметры поля
		$params = ParamsHelper::get($field->name);

		// запускаем объект указанного поля
		$paths  = PathsHelper::get();
		$fields = PathsHelper::getFields();

		foreach ($paths as $path)
		{
			FormHelper::addFieldPath(Path::clean(JPATH_ROOT . '/' . $path));
		}

		foreach ($fields as $rfield)
		{
			JFormHelper::loadFieldClass($rfield);
		}

		$params->set('rname', $field->name);
		$params->set('rlabel', $field->label);
		$params->set('rdescription', $field->description);

		// получаем поля getInput и возвращаем
		$xml        = ParamsHelper::buildArray($params);
		$class_name = 'JFormField' . ucfirst(strtolower($params->get('rtype')));

		if (class_exists($class_name))
		{
			$node = $parent->appendChild(new DOMElement('field'));

			foreach ($xml as $attr => $value)
			{
				if (is_array($value))
				{

					if ($attr === 'form')
					{
						$node = $node->appendChild(new DOMElement('form'));
						foreach ($value as $form_fields)
						{
							$node = $node->appendChild(new DOMElement('field'));
							foreach ($form_fields as $form_field_key => $form_field_value)
							{
								$node->setAttribute($form_field_key, $form_field_value);
							}
							$node = $node->parentNode;
						}
						$node = $node->parentNode;
					}

					if ($attr === 'list')
					{
						foreach ($value as $item)
						{
							$node = $node->appendChild(new DOMElement('option'));
							$node->setAttribute('value', $item['value']);
							$node->textContent = $item['title'];
							$node              = $node->parentNode;
						}
					}

					continue;
				}

				$node->setAttribute($attr, $value);
			}

		}

	}


	/**
	 * Prepares the field value.
	 *
	 * @param   string    $context  The context.
	 * @param   stdclass  $item     The item.
	 * @param   stdclass  $field    The field.
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareField($context, $item, $field)
	{
		// Check if the field should be processed by us
		if (!$this->isTypeSupported($field->type))
		{
			return;
		}

		JLoader::register('RadicalmultifieldHelper', JPATH_ROOT . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, ['plugins', 'fields', 'radicalmultifield', 'radicalmultifieldhelper']) . '.php');

		$input         = Factory::getApplication()->input;
		$option        = $input->get('option', 'com_content');
		$view          = $input->get('view', 'article');
		$context_input = $option . '.' . $view;

		if (!empty($item->context_radicalmultifield))
		{
			$context_input = $item->context_radicalmultifield;
		}

		// Merge the params from the plugin and field which has precedence
		$fieldParams = clone $this->params;
		$fieldParams->merge($field->fieldparams);
		$template = $fieldParams->get('templatedefault');

		$template_category = [
			'com_content.category',
			'com_content.categories',
			'com_users.users',
			'com_contact.categories',
			'com_tags.tag',
		];

		$template_item = [
			'com_content.article',
			'com_users.user',
			'com_contact.contact',
		];

		if (empty($template))
		{
			if (in_array($context_input, $template_category))
			{
				$template = $fieldParams->get('templatecategory', $template);
			}

			if (in_array($context_input, $template_item))
			{
				$template = $fieldParams->get('templatearticle', $template);
			}
		}

		// Get the path for the layout file
		if (substr_count($field->type, '_') > 0)
		{
			$tmp  = explode('_', $field->type);
			$path = PluginHelper::getLayoutPath('radicalmultifield', $tmp[1], $template);
		}
		else
		{
			$path = PluginHelper::getLayoutPath('fields', $field->type, $template);
		}


		// Render the layout
		ob_start();
		include $path;
		$output = ob_get_clean();

		// Return the output
		return $output;
	}


}
