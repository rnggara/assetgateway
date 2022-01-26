<?php
include '../config/setting.php';
$barcode = isset($_GET['barcode']) ? $_GET['barcode']: "0";

$js = json_decode($barcode);

// echo count($js);


$row = 0;
$num = 1;
$col = [];

for ($i=0; $i < count($js); $i++) { 
    $icode = $js[$i];
    $r = "SELECT item_code FROM asset_items WHERE";
    $r .= " item_code = '$icode'";
    $dr = $db->Execute($r);
    $col[] = $dr->fields('item_code');
    // while(!$dr->EOF){
    //     $col[] = $dr->fields('item_code');
    //     $num++;
    //     if($num > 2){
    //         $row++;
    //         $num = 1;
    //     }
    //     $dr->MoveNext();
    // }

}
// echo json_encode($col);
// $co = $dr->fields['item_code'];
?>
<!-- <img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo $co;?>&codetype=code128&print=true&size=55" /> -->
<!DOCTYPE html>
<html lang="en">
<body style="margin: -1px 0 0 -1px">
    
</body>
</html>
<?php
for ($i=0; $i < count($col); $i++) {
    $mod = $i % 2;
    ?>
        <img alt="<?= $mod ?>" src="../plugins/phpbarcode/barcode.php?text=<?= $col[$i] ?>&codetype=code128&print=true&size=55" <?= ($mod != 0) ? 'style="margin-left: 20px;"' : '' ?> />
<?php } ?>