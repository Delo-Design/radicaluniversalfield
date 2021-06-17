<?php

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

class ParamsHelper
{


	public static function get($name)
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true)
			->select($db->quoteName(['name', 'params', 'fieldparams']))
			->from('#__fields')
			->where('name=' . $db->quote($name));
		$field = $db->setQuery($query)->loadObject();

		if (!empty($field->fieldparams))
		{
			return new Registry($field->fieldparams);
		}

		return false;
	}


	public static function build($params)
	{
		$params = $params->toArray();

		return self::buildTo($params);
	}


	protected static function buildTo($params)
	{
		$close = false;
		$xml   = '<field ';
		$attrs = [
			'type'        => $params['rtype'],
			'name'        => $params['rname'],
			'label'       => $params['rlabel'],
			'description' => $params['rdescription'],
		];

		if (isset($params['rattrs']) && is_array($params['rattrs']))
		{
			foreach ($params['rattrs'] as $rattr)
			{
				$attrs[$rattr->attr] = $rattr->value;
			}
		}

		$field_attr = $attrs;
		array_walk($field_attr, static function (&$value, $key) {

			if (in_array($key, ['label', 'description']))
			{
				$value = $key . '="' . htmlspecialchars($value) . '"';
			}
			else
			{
				$value = $key . '="' . $value . '"';
			}
		});

		$xml .= implode(' ', $field_attr);

		// если сабформа
		if ($attrs['rtype'] === 'subform')
		{
			$xml   .= '>';
			$xml   .= '<form>';
			$xml   .= self::buildTo($params['rsubform']);
			$xml   .= '</form>';
			$xml   .= '</field>';
			$close = true;
		}

		// если списочный
		if (in_array($attrs['rtype'], ['list', 'radio']))
		{
			$xml .= '>';

			foreach ($params['rvalues'] as $value)
			{
				$xml .= '<option value="' . $value['value'] . '">' . $value['title'] . '</option>';
			}

			$close = true;
		}

		if (!$close)
		{
			$xml .= '/>';
		}

		return $xml;
	}

}