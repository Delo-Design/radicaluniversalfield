<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseDriver;

JLoader::import( 'components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);

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
    public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form )
    {

    	$fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);
        if ( !$fieldNode )
        {
            return $fieldNode;
        }

        $path = URI::base( true ) . '/templates/' . Factory::getApplication()->getTemplate() . '/';

        $fieldNode->setAttribute('template', $path);
        
        return $fieldNode;
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

        $input = Factory::getApplication()->input;
        $option = $input->get('option', 'com_content');
        $view = $input->get('view', 'article');
        $context_input = $option . '.' . $view;

        if(!empty($item->context_radicalmultifield))
        {
            $context_input =  $item->context_radicalmultifield;
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

        if(empty($template))
        {
            if(in_array($context_input, $template_category))
            {
                $template = $fieldParams->get('templatecategory', $template);
            }

            if(in_array($context_input, $template_item))
            {
                $template = $fieldParams->get('templatearticle', $template);
            }
        }

	    // Get the path for the layout file
	    if(substr_count($field->type, '_') > 0)
	    {
	    	$tmp = explode('_', $field->type);
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
