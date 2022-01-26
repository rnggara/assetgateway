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
$searchTerm = isset($object['searchTerm']) ? $object['searchTerm']:"";
$r = "SELECT * FROM asset_wh ";
$r .= "WHERE name LIKE '%$searchTerm%' ";
$dr = $db->Execute($r);
while(!$dr->EOF) {
    $data[] = array(
        "id" => $dr->fields['id'],
        "text" =>$dr->fields['name'],
    );
    $dr->MoveNext();
}
header('Content-Type: application/json');
echo json_encode($data);
?>