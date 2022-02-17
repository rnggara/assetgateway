<?php

include '../config/setting.php';

$res = $db->Execute("SELECT last_update FROM last_id_do_server");

$date = "N/A";

if($res->RecordCount() > 0){
    if(!empty($res->fields("last_update"))){
        $date = date("d F Y H:i", strtotime($res->fields("last_update")));
    }
}

echo json_encode($date);

?>