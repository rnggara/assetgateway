<?php 
	include '../config/setting.php';

	$query = "SELECT id from asset_items order by id desc limit 1";
	$rsql = $db->Execute($query);
	$count = $rsql->RecordCount();
	if($count > 0){
		// get from api
		$id = $rsql->fields("id");

		$url = "http://cypher.vesselholding.com/cypher4/public/api/assetgateway/refresh/$id";
		$content = file_get_contents($url);
		$js = json_decode($content, true);
		if($js['success']){
			foreach($js['data'] as $item){
				$category_id = (empty($item['category_id'])) ? "NULL" : "'".$item['category_id']."'";
				$class_id = (empty($item['class_id'])) ? "NULL" : "'".$item['class_id']."'";
				$item_type = (empty($item['item_type'])) ? "NULL" : "'".$item['item_type']."'";
				$uom = (empty($item['uom'])) ? "NULL" : "'".$item['uom']."'";
				$uom2 = (empty($item['uom2'])) ? "NULL" : "'".$item['uom2']."'";
				$sql = "INSERT INTO asset_items (id, item_code, name, item_series, category_id, class_id, item_type, uom, uom2, minimal_stock, notes, specification, serial_number, created_at, company_id) VALUES (";
				$sql .= "".$item['id'].", ";
				$sql .= "'".$item['item_code']."', ";
				$sql .= "'".$item['name']."', ";
				$sql .= "'".$item['item_series']."', ";
				$sql .= "".$category_id.", ";
				$sql .= "".$class_id.", ";
				$sql .= "".$item_type.", ";
				$sql .= "".$uom.", ";
				$sql .= "".$uom2.", ";
				$sql .= "1, ";
				$sql .= "'".$item['notes']."', ";
				$sql .= "'".$item['specification']."', ";
				$sql .= "'".$item['serial_number']."', ";
				$sql .= "'".$item['created_at']."', ";
				$sql .= $item['company_id']."); ";
				// echo $sql."<br>";
				$exec = $db->Execute($sql);
				if(!$exec){
					echo $sql."<br>";
					echo $db->errorMsg();
					break;
				}
			}
		}
	}

	$query = "SELECT * from asset_items where uom2 is not NULL AND deleted_at IS null order by id DESC;";
	$rsql = $db->Execute($query);
	$count = $rsql->RecordCount();

	$id = [];
	$item = [];

	while(!$rsql->EOF){
		$id[] = $rsql->fields("id");
		$item[$rsql->fields('id')] = $rsql->fields;
		$rsql->MoveNext();
	}

	if(count($id) > 0){
		$jsid = json_encode($id);
		$url = "http://cypher.vesselholding.com/cypher4/public/api/assetgateway/refresh1/$jsid";
		$content = file_get_contents($url);
		$js = json_decode($content, true);
		if($js['success']){
			$data = $js['data'];
			for ($i=0; $i < count($id); $i++) { 
				$_id = $id[$i];
				if(isset($data[$_id])){
					if($item[$_id]['item_code'] != $data[$_id]['item_code']){
						$category_id = (empty($item['category_id'])) ? "NULL" : "'".$item['category_id']."'";
						$class_id = (empty($item['class_id'])) ? "NULL" : "'".$item['class_id']."'";
						$item_type = (empty($item['item_type'])) ? "NULL" : "'".$item['item_type']."'";
						$uom = (empty($item['uom'])) ? "NULL" : "'".$item['uom']."'";
						$uom2 = (empty($item['uom2'])) ? "NULL" : "'".$item['uom2']."'";
						$item_code = $data[$_id]['item_code'];
						$notes = $data[$_id]['notes'];
						$specification = $data[$_id]['specification'];
						$item_name = $data[$_id]['name'];
						$serial_number = $data[$_id]['serial_number'];
						$item_series = $data[$_id]['item_series'];
						$sql = "UPDATE asset_items SET uom2 = null, item_code = '$item_code', notes = '$notes', name = '$item_name', serial_number = '$serial_number', item_series = '$item_series', specification = '$specification' ";
						$sql .= "WHERE id = $_id";
						$exec = $db->Execute($sql);
						if(!$exec){
							echo $sql."<br>";
							echo $db->errorMsg();
							break;
						}
					}
				} else {
					$sql = "UPDATE asset_items SET deleted_at = '".date("Y-m-d H:i:s")."' ";
					$sql .= "WHERE id = $_id";
					$exec = $db->Execute($sql);
					if(!$exec){
						echo $sql."<br>";
						echo $db->errorMsg();
						break;
					}
				}
			}
		}
	}
	


	header('Location: http://localhost/assetgateway/?m=a1');



?>