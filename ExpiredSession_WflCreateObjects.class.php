<?php

/**
 * @package 	Expired plug-in for Enterprise
 * @since 	v8.0
 * @copyright	LEO. All Rights Reserved.
 */

require_once BASEDIR.'/server/interfaces/services/wfl/WflCreateObjects_EnterpriseConnector.class.php';

class ExpiredSession_WflCreateObjects extends WflCreateObjects_EnterpriseConnector {

	final public function getPrio()      { return self::PRIO_DEFAULT; }
	final public function getRunMode()   { return self::RUNMODE_AFTER; }

	final public function runBefore( WflCreateObjectsRequest &$req ) {}

	final public function runAfter( WflCreateObjectsRequest $req, WflCreateObjectsResponse &$resp ) {
		require_once BASEDIR.'/config/plugins/ExpiredSession/ExpiredSessionUtils.class.php';
		$expired = new ExpiredSessionUtils();
		$dbDriver=DBDriverFactory::gen();
        $dbobjects=$dbDriver->tablename("smart_tickets");
        $sql = "SELECT * FROM `smart_tickets`";
        $rs=$dbDriver->query($sql);
		while ( $rows = $dbDriver->fetch($rs) ) {
	        $ticket = $rows['ticketid'];
	        $expired->updatedb($ticket);
	        LogHandler::Log("ExpiredSession","DEBUG","delete ticket $ticket");
	    }    

		
	}

	final public function runOverruled( WflCreateObjectsRequest $req ) {}

}
?>