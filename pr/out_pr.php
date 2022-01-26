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
if($act == "v"){
    // $db->debug=1;
    $item_code = $object['item_code'];
    $r  = "SELECT * FROM asset_items ";
    $r .= "WHERE item_code = '".$item_code."' ";
    $dr = $db->Execute($r);
    $countR = $dr->RecordCount();
    if($countR > 0){
        $data = array(
            "success" => 1,
            "msg" => "data exist",
            "id" => $dr->fields['id'],
            "item_code" => $dr->fields['item_code'],
            "name" => $dr->fields['name']
        );
        
    } else {
        $data = array(
            "success" => 0,
            "msg" => "not found"
        );
    }
} elseif($act == 'e'){
    $item_id = $object['item_id'];
    $item_qty = $object['item_qty'];
    $to_id = $object['to_id'];
    $curDate = date("Y-m-d H:i:s");
    $curId = "gateway";
    if(count($item_id) > 0){
        // create DO
        $d['from_id'] = 2;
        $d['to_id'] = $to_id;
        $d['project'] = 1;
        $d['division'] = "Asset";
        $d['deliver_by'] = $curId;
        $d['deliver_date'] = $curDate;
        $d['created_by'] = $curId;
        $d['created_at'] = $curDate;
        $dd = $db->AutoExecute('do', $d, 'INSERT');
        $lId = $db->Insert_ID();
        // update kode no_do
        $d2['no_do_local'] = $lId."/GATE/DO/".date("m")."/".date("Y");
        $dd = $db->AutoExecute('do', $d2, 'UPDATE', "id='".$lId."'");
        for($i=0; $i<count($item_id); $i++){
            // $db->debug=1;
            $r1 = "select * from asset_items where id = '".$item_id[$i]."'";
            $dr1 = $db->Execute($r1);
            $type = $dr1->fields['type_id'];
            $nama_item_code = $dr1->fields['item_code'];
            $d1['do_id'] = $lId;
            $d1['item_id'] = $nama_item_code;
            $d1['qty'] = $item_qty[$i];
            $d1['type'] = ($type == 1) ? "Transfer & Use" : "Transfer";
            $d1['created_at'] = date("Y-m-d H:i:s");
            $dd1 = $db->AutoExecute('do_detail',$d1,"INSERT");
        }
        $data = array(
            "success" => 1,
            "msg" => "Create DO",
            "id" => $lId
        );
    } else {
        $data = array(
            "success" => 0,
            "msg" => "No Data To Process"
        );
    }
    

}
header('Content-Type: application/json');
echo json_encode($data);
?>