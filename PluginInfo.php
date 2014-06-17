<?php


/**
 * @package 	Expired plug-in for Enterprise
 * @since 	v8.0
 * @copyright	LEO. All Rights Reserved.
 */


require_once BASEDIR.'/server/interfaces/plugins/EnterprisePlugin.class.php';
require_once BASEDIR.'/server/interfaces/plugins/PluginInfoData.class.php';
 
class ExpiredSession_EnterprisePlugin extends EnterprisePlugin
{
	public function getPluginInfo()
	{ 
		$info = new PluginInfoData(); 
		$info->DisplayName = 'ExpiredSession';
		$info->Version     = 'v2.0'; // don't use PRODUCTVERSION
	    $info->Description = 'ExpiredSession';
		$info->Copyright   = 'Icenter Ukraine, Edited by leo, last_edit_17_06_14';
		return $info;
	}
	
    final public function getConnectorInterfaces() 
		{ 
			return array( 	

				'WflGetObjects_EnterpriseConnector', 
				'WflCreateObjects_EnterpriseConnector',
				'WflSaveObjects_EnterpriseConnector',
				'WflSendTo_EnterpriseConnector',
				'WflSetObjectProperties_EnterpriseConnector'
		
			);
		}
}