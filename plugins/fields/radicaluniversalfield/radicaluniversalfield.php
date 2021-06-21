<?php defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\Database\DatabaseDriver;
use Joomla\Filesystem\Path;

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);
JLoader::register('ParamsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/ParamsHelper.php');
JLoader::register('PathsHelper', JPATH_PLUGINS . '/fields/radicaluniversalfield/helpers/PathsHelper.php');
JLoader::register('LayoutPathsHelper', JPATH_LIBRARIES . '/lib_fields/fields/layouts/helpers/LayoutPathsHelper.php');

/**
 * Radical universal field plugin.
 *
 * @package  radicaluniversalfield
 * @since    1.0
 */
class PlgFieldsRadicaluniversalfield extends FieldsPlugin
{


	/**
	 * Application object
	 *
	 * @var    CMSApplication
	 * @since  1.0.0
	 */
	protected $app;


	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;


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
			FormHelper::loadFieldClass($rfield);
		}

		$params->set('rname', $field->name);
		$params->set('rlabel', $field->label);
		$params->set('rdescription', $field->description);

		// получаем карту в виде массива
		$xml        = ParamsHelper::buildArray($params);
		$class_name = 'JFormField' . ucfirst(strtolower($params->get('rtype')));

		$fix = (int)$params->get('rsubformfix', 0);

		if($fix)
		{
			HTMLHelper::script('plg_fields_radicaluniversalfield/fixsubform.js', [
				'version' => filemtime ( __FILE__ ),
				'relative' => true,
			]);
		}

		if (class_exists($class_name))
		{
			$node = $parent->appendChild(new DOMElement('field'));

			// TODO переделать на анализ xml, чтобы можно было рекурсивно строить по чистому xml всю карту для DOMelement.
			// Иначе никак не сделать. Joomla не передает доступ к DOMdocument и не импортировать туда весь узел из xml, поэтому надо рекурсивно проходить


			// TODO переделать на рекурсию

			// проходим карту и создаем элементы
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
								if (is_array($form_field_value))
								{
									if ($form_field_key === 'options')
									{
										foreach ($form_field_value as $item)
										{
											$node = $node->appendChild(new DOMElement('option'));
											$node->setAttribute('value', $item['value']);
											$node->textContent = $item['title'];
											$node              = $node->parentNode;
										}
										continue;
									}
								}

								$node->setAttribute($form_field_key, $form_field_value);
							}
							$node = $node->parentNode;
						}
						$node = $node->parentNode;
					}

					if ($attr === 'options')
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

		if (!empty($item->context_local))
		{
			$context_input = $item->context_local;
		}

		// Merge the params from the plugin and field which has precedence
		$fieldParams = clone $this->params;
		$fieldParams->merge($field->fieldparams);
		$layout = $fieldParams->get('layoutdefault');

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


		if (in_array($context_input, $template_category))
		{
			$layout = $fieldParams->get('layoutcategory', $layout);
		}

		if (in_array($context_input, $template_item))
		{
			$layout = $fieldParams->get('layoutitem', $layout);
		}

		$theme = '';
		if (strpos($layout, '::') !== false)
		{
			[$theme, $layout] = explode('::', $layout);
		}
		else
		{
			$theme = Factory::getApplication()->getTemplate();
		}

		$file_layout = new FileLayout($layout);
		$paths       = new LayoutPathsHelper('plugin.fields.radicaluniversalfield', 'PathsHelper::getLayouts', $theme);
		$file_layout->addIncludePaths($paths->get('paths'));

		return $file_layout->render(['field' => $field]);
	}


}
