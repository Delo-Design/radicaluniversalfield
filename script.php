<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Class plgFieldsRadicaluniversalfieldInstallerScript
 */
class plgFieldsRadicaluniversalfieldInstallerScript
{


	public function postflight($type, $parent)
	{
		// Enable plugin
		if ($type === 'install')
		{
			$this->enablePlugin($parent);
		}

		return true;
	}


	protected function enablePlugin($parent)
	{
		// Prepare plugin object
		$plugin          = new stdClass();
		$plugin->type    = 'plugin';
		$plugin->element = $parent->getElement();
		$plugin->folder  = (string) $parent->getParent()->manifest->attributes()['group'];
		$plugin->enabled = 1;

		// Update record
		Factory::getDbo()->updateObject('#__extensions', $plugin, ['type', 'element', 'folder']);
	}


}
