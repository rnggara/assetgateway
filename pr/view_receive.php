<?php
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
$data = array();

// $db->debug=1;
if($act == "v"){
	
	$do_id = isset($object['do_id']) ? $object['do_id']: 0;
	if($do_id){
		$r  = "SELECT id, no_do, no_do_local FROM do ";
		if($object['type'] == "no"){
			$r .= "WHERE (`do`.no_do = '$do_id' OR `do`.no_do_local = '$do_id') ";
		} else {
			$r .= "WHERE `do`.id = '$do_id' ";
		}
		$r .= " AND do.deleted_at is null";
		$dr = $db->Execute($r);
		if($dr->RecordCount() > 0){
			$sql  = "SELECT do_detail.id, item_code, do_id, item_id, `name`, uom, qty, notes, specification, asset_items.name as namaItem , asset_items.id as aId ";
			$sql .= "FROM do_detail ";
			$sql .= "LEFT JOIN asset_items ON asset_items.item_code = do_detail.item_id ";
			$sql .= "WHERE do_id = ".$dr->fields['id']." ";
			$r_sql = $db->Execute($sql);
			if($r_sql){
				$countR = $r_sql->RecordCount();
				if ($countR > 0) {
					$i = 1;
					$no_do = (empty($dr->fields['no_do'])) ? $dr->fields['no_do_local'] : $dr->fields['no_do'];
					while(!$r_sql->EOF) {
						$ddtl[] = array(
							"item" =>  $r_sql->fields['namaItem'],
							"qty" =>  $r_sql->fields['qty'],
							"item_code" => $r_sql->fields['item_id'],
							"print" => $r_sql->fields['aId']
						);
						$r_sql->MoveNext();
					}
					$data = array(
						"success" => 1,
						"msg" => "ADA Data",
						"do_id" => $do_id,
						"do" => $no_do,
						"dtl" => $ddtl,
					);
				}
			} else{
				$data = array(
					"success" => 0,
					"msg" => "No Data 2"
				);
			}
		} else {
			$data = array(
				"success" => 0,
				"msg" => "No Data 2"
			);
		}
	} else {
		$data = array(
			"success" => 1,
			"msg" => "No Data"
		);
	}
} elseif($act == 'r') {
	$do_id = isset($object['do_id']) ? $object['do_id']: 0;
	if($do_id > 0){	
		$r = "SELECT * FROM do WHERE id = '".$do_id."' AND approved_by IS NULL ";
		$dr = $db->Execute($r);
		if($dr->RecordCount() > 0){
			$d['approved_by'] = "gateway";
			$d['approved_time'] = date("Y-m-d H:i:s");
			$dd = $db->AutoExecute('do', $d, 'UPDATE', "id='".$do_id."'");
			$data = array(
				"success" => 1,
				"msg" => "DO RECEIVED"
			);
		} else {
			$data = array(
				"success" => 0,
				"msg" => "DO Already RECEIVED"
			);
		}

	} else{
		$data = array(
			"success" => 0,
			"msg" => "No Do Selected 1"
		);
	}

} else {
	$data = array(
		"success" => 0,
		"msg" => "No Module Fired"
	);
}
header('Content-Type: application/json');
echo json_encode($data);