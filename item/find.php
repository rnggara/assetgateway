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
        if($act == "search"){
            $param = $object['param'];
            $row = [];
            if(!empty($param)){
                $sql = "SELECT item_code, name from asset_items";
                $sql .= " WHERE (item_code like '%$param%'";
                $sql .= " OR name like '%$param%')";
                $sql .= " AND deleted_at is null";
                $sql .= " AND uom2 is null";
                $rsql = $db->Execute($sql);
                $count = $rsql->RecordCount();
                if($count > 0){
                    while(!$rsql->EOF){
                        $col = [];
                        $col['item_code'] = $rsql->fields('item_code');
                        $col['name'] = $rsql->fields('name');
                        $row[] = $col;
                        $rsql->MoveNext();
                    }
                }
            }
            $data = array(
				"success" => 1,
				"msg" => "ADA Data",
				"items" => $row,
			);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>