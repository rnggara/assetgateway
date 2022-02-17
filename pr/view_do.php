<?php
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        include '../config/setting.php';
        
        if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
            throw new Exception('Only POST requests are allowed');
        }
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (stripos($content_type, 'application/json') === false) {
            throw new Exception('Content-Type must be application/json');
        }
        $body = file_get_contents("php://input");
        $object = json_decode($body, true);
        if (!is_array($object)) {
            throw new Exception('Failed to decode JSON object');
        }
        $act = $object['act'];
        $data = [];
        if($act == "view"){
            $row = [];
            $sql = "SELECT id, no_do_local, (SELECT name FROM asset_wh where asset_wh.id = do.to_id) as wh_destination from do";
            $sql .= " WHERE deleted_at is null";
            $sql .= " AND no_do_local is not null and no_do is null";
            $rsql = $db->Execute($sql);
            if($rsql){
                $count = $rsql->RecordCount();
                if($count > 0){
                    $num = 1;
                    while(!$rsql->EOF){
                        $col = [];
                        $col['num'] = $num++;
                        $col['id'] = $rsql->fields('id');
                        $col['no_do'] = $rsql->fields('no_do_local');
                        $col['wh_destination'] = $rsql->fields('wh_destination');
                        $col['print'] = '<iframe src="view/do_print.php?id='.$rsql->fields('id').'&act=do" name="print-'.$rsql->fields('id').'" id="print-'.$rsql->fields('id').'" height="0" width="0" frameborder="0"></iframe>';
                        $row[] = $col;
                        $rsql->MoveNext();
                    }
                }
                $data = array(
                    "success" => 1,
                    "msg" => "ADA Data",
                    "items" => $row,
                );
            } else {
                $data = array(
                    "success" => 0,
                    "msg" => "Tidak ada Data",
                    "items" => $sql,
                );
            }
        }

        if($act == "delete"){
            $sql = "UPDATE do SET deleted_at = '".date("Y-m-d H:i:s")."'";
            $sql .= " WHERE id = ".$object['id'];
            $rsql = $db->Execute($sql);
            if($rsql){
                $data = array(
                    "success" => 1,
                    "msg" => "Delete",
                );
            } else {
                $data = array(
                    "success" => 0,
                    "msg" => "Tidak ada Data",
                    "items" => $sql,
                );
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>