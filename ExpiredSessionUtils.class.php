<?php

require_once BASEDIR.'/config/plugins/ExpiredSession/config.php';
require_once BASEDIR.'/config/configserver.php';

class ExpiredSessionUtils {
   
    final public function updatedb($ticket) {

        $mapping = unserialize( GROUP_DEFS );
        $dbDriver=DBDriverFactory::gen();
        $dbobjects=$dbDriver->tablename("tickets");
        $sqlecho='select * from `smart_tickets` WHERE `ticketid` = '."'".$ticket."'";
        $sthes=$dbDriver->query($sqlecho);
        $rowss = $dbDriver->fetch($sthes);
        $expiredate = $rowss['expire'];
        $usrname = $rowss['usr'];
        $day=date("d");
        $date=date("j");
        $month=date("m");
        $year=date("Y");
        $hours = date("H");
        $minutes = date("i");
        $seconds = date("s");
        $current_time = strtotime($year . "-" . $month . "-" . $day.'T'.$hours.":".$minutes.":".$seconds);
        $expiredate = strtotime($expiredate);
        $logondate = $expiredate - EXPIREDEFAULT;
        $dbobjects=$dbDriver->tablename("users");
        $sqlechos='select * from `smart_users` WHERE `user` = '."'".$usrname."'";
        $sthes=$dbDriver->query($sqlechos);
        $ro = $dbDriver->fetch($sthes);
        $usrid = $ro['id'];
        $dbDriver=DBDriverFactory::gen();
        $dbobjects=$dbDriver->tablename("usrgrp");
        $sqlecho='select * from `smart_usrgrp` WHERE `usrid` = '."'".$usrid."'";
        $sthe=$dbDriver->query($sqlecho);
        $exp = 0;
        $select_section_time = 0;
        while ($row = $dbDriver->fetch($sthe)) {
            $groups = $row['grpid'];
            $dbDriver=DBDriverFactory::gen();
            $sq='select * from `smart_groups` WHERE `id` = '."'".$groups."'";
            $st=$dbDriver->query($sq);
            $rowgrpid = $dbDriver->fetch($st);
            $sectionname = $rowgrpid['name'];
            $exp = $mapping[$sectionname];
            if ( $exp > $select_section_time ) {
                $select_section_time = $exp;
            }
        }
        if (array_key_exists( $sectionname, $mapping )) {
            
            $sum =  $select_section_time + $logondate;
            if (  $sum <  $current_time  ) {    
                    self::deleteticket($ticket);
            } 
        }
    }

    function deleteticket($ticket){
        $dbDriver=DBDriverFactory::gen();
        $sqlecho='DELETE FROM `smart_tickets` WHERE `ticketid` = '."'".$ticket."'";
        $sthe=$dbDriver->query($sqlecho);
    } 
	
}

?>
