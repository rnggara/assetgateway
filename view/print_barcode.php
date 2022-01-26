<?php
include '../config/setting.php';
// include "../plugins/phpqrcode/qrlib.php"; 
// $db->debug=1;
$barcode = isset($_GET['barcode']) ? $_GET['barcode']: "0";
$r = "SELECT * FROM asset_items WHERE id = '".$barcode."' AND category_id IS NOT NULL AND class_id IS NOT NULL";
$dr = $db->Execute($r);
$co = $dr->fields['item_code'];

// QRcode::png($co); 
?>
<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50" />

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50"  style="margin-left: 20px;"/>

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50" />

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50"  style="margin-left: 20px;"/>

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50" />

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50"  style="margin-left: 20px;"/>

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50" />

<img alt="123ABC" src="../plugins/phpbarcode/barcode.php?text=<?php echo"$co";?>&codetype=code128&print=true&size=50"  style="margin-left: 20px;"/>