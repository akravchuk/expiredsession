<?php

/**
 * @package 	Expired plug-in for Enterprise
 * @since 	v8.0
 * @copyright	LEO. All Rights Reserved.
 */

require_once BASEDIR . '/server/interfaces/services/wfl/WflGetObjects_EnterpriseConnector.class.php';



class ExpiredSession_WflGetObjects extends WflGetObjects_EnterpriseConnector
{
	final public function getPrio () 	{	return self::PRIO_DEFAULT; 	}
	final public function getRunMode () {	return self::RUNMODE_BEFOREAFTER; }
	
	final public function runBefore (WflGetObjectsRequest &$req) {
		LogHandler::Log("ExpiredSession","DEBUG","ExpiredSession WflGetObjects runBefore");
		LogHandler::Log("ExpiredSession","DEBUG", print_r($req,true));
	} 

	final public function runAfter (WflGetObjectsRequest $req, WflGetObjectsResponse &$resp){
		LogHandler::Log("ExpiredSession","DEBUG","ExpiredSession WflGetObjects runAfter");
		LogHandler::Log("ExpiredSession","DEBUG", print_r($resp,true));

		require_once BASEDIR.'/config/plugins/ExpiredSession/ExpiredSessionUtils.class.php';
		$expired = new ExpiredSessionUtils();
		$dbDriver=DBDriverFactory::gen();
        $dbobjects=$dbDriver->tablename("smart_tickets");
        $sql = "SELECT * FROM `smart_tickets`";
        $rs=$dbDriver->query($sql);
		$current_ticket = $req->Ticket;
		while ( $rows = $dbDriver->fetch($rs) ) {
	        $ticket = $rows['ticketid'];
	        if ( $ticket != $current_ticket ) {
		            $expired->updatedb($ticket);
		        	LogHandler::Log("ExpiredSession","DEBUG","delete ticket $ticket");		        
		    }
	    }  
	} 
	
	final public function runOverruled (WflGetObjectsRequest $req) {}
		
}
