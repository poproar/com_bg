<?php
/*
 * @package bg
 * @copyright Copyright (c)2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

class BgDispatcher extends FOFDispatcher
{
	public $defaultView = 'profiles';

//	public function onBeforeDispatch()
//	{
//		$result = parent::onBeforeDispatch();
//
//		if ($result)
//		{
//			$strapperInclude = JPATH_ROOT . '/media/akeeba_strapper/strapper.php';
//
//			if (@file_exists($strapperInclude))
//			{
//				@include_once $strapperInclude;
//			}
//
//			if (class_exists('AkeebaStrapper'))
//			{
//				AkeebaStrapper::bootstrap();
//				AkeebaStrapper::jQueryUI();
//			}
//		}
//
//		return $result;
//	}
}
