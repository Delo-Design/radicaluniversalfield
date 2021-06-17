<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Class plgFieldsRadicaluniversalfieldInstallerScript
 */
class plgFieldsRadicaluniversalfieldInstallerScript
{

	/**
	 * @param $type
	 * @param $parent
	 *
	 * @throws Exception
	 */
	function postflight($type, $parent)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->update('#__extensions')
			->set('enabled=1')
			->where('type=' . $db->q('plugin'))
			->where('element=' . $db->q('radicaluniversalfield'));
		$db->setQuery($query)->execute();
	}

	/**
	 * @param $type
	 * @param $parent
	 *
	 * @throws Exception
	 */
	function preflight($type, $parent)
	{
		if ((version_compare(PHP_VERSION, '5.6.0') < 0))
		{
			Factory::getApplication()->enqueueMessage(Text::_('PLG_RADICAL_MULTI_FIELD_WRONG_PHP'), 'error');

			return false;
		}
	}
}
