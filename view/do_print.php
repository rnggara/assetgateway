<?php
session_start();
error_reporting(0);
include '../config/setting.php';
include '../dist/phpqrcode/qrlib.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>

<style>
  @media print {
        body {
          font-size: 9pt;
        }
    }
</style>

</head>

<body <?= ($_GET['act'] == "print") ? "onload='window.print()'" : "" ?>>
<?php
$sql  = "SELECT `do`.id, `do`.no_do_local, `do`.no_do, to_id, deliver_date, deliver_by, notes, ";
$sql .= "`from`.`name` fr_name, `from`.address fr_address, `from`.telephone fr_phone, ";
$sql .= "`to`.`name` to_name, `to`.address to_address, `to`.telephone to_phone ";
$sql .= "FROM `do` ";
$sql .= "LEFT JOIN asset_wh `from` ON `from`.id = `do`.from_id ";
$sql .= "LEFT JOIN asset_wh `to` ON `to`.id = `do`.to_id ";
$sql .= "WHERE `do`.id = '$_GET[id]' ";

$r_sql = $db->Execute($sql);
$deliver_by = $r_sql->fields['deliver_by'];
?>

<table width="100%" border="0" cellpadding="2" cellspacing="1" align="center">
  <tr>
    <td><table width="100%" border="0" cellpadding="2" cellspacing="1">
      <td width="50%" align="center"><img src="../dist/img/logo1.png" height="55px" /><br />
          <h2>VESSEL</h2>
          <div>
              <p style="font-size:9px">
                  Jalan Warung Jati Barat No. 22A, Vessel Tower, Ragunan, Pasar Minggu.
                  <br />
                  Phone : (021) 7822 544<br />
                  Email : admin@vesselholding.com<br />
              </p>
              <h1>DELIVERY ORDER</h1>
              No.: <?= $r_sql->fields('no_do_local') ?>
          </div>
      </td>
      <td align="center">
        <img src="../pr/qrcode.php?text=http://cypher.vesselholding.com/cypher4/public/general/do/detail/appr/gate/<?= $r_sql->fields("id") ?>" style="width: 150px;" alt="">
      </td>
      
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="2" cellspacing="1">
      <tr valign="top">
        <td width="50%">
					<table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
          <tr>
            <td width="100">From: </td>
            <td><?php echo $r_sql->fields['fr_name']; ?>&nbsp;</td>
          </tr>
          <tr>
            <td>Address:</td>
            <td>
							<?php echo $r_sql->fields['fr_address']; ?></td>
          </tr>
          <tr>
            <td>Telp:</td>
            <td><?php echo $r_sql->fields['fr_phone']; ?></td>
          </tr>
          <tr>
            <td>Delivery Date: </td>
            <td><?php echo date("d M Y", strtotime($r_sql->fields['deliver_date'])); ?>&nbsp;</td>
          </tr>
        </table></td>
        <td><table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
          <tr>
            <td width="100">Delivery To: </td>
            <td><?php echo $r_sql->fields['to_name']; ?>&nbsp;</td>
          </tr>
          <tr>
            <td>Address:</td>
            <td><?php echo $r_sql->fields['to_address']; ?></td>
          </tr>
          <tr>
            <td>Telp:</td>
            <td><?php echo $r_sql->fields['to_phone']; ?></td>
          </tr>
          <tr>
            <td>Notes: </td>
            <td><?php echo $r_sql->fields['notes']; ?></td>
          </tr>
        </table>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
		<?php
		$sql  = "SELECT do_detail.id, do_id, item_id, `name`, uom, qty, notes, specification ";
		$sql .= "FROM do_detail ";
		$sql .= "LEFT JOIN asset_items ON asset_items.item_code = do_detail.item_id ";
		$sql .= "WHERE do_id = '$_GET[id]' ";
		$r_sql = $db->Execute($sql);
		
		if ($r_sql->RowCount() > 0) {
		?>
		
		<table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
      <thead>
				<tr bgcolor="#999999">
					<th width="30">No.</th>
					<th width="50">Qty</th>
					<th width="50">Uom</th>
					<th>Description</th>
					<th width="200">Remark</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			while(!$r_sql->EOF) {
			?>
      <tr valign="top">
        <td align="right"><strong><?php echo $i++; ?></strong>&nbsp;</td>
        <td align="center"><strong><?php echo $r_sql->fields['qty']; ?></strong>&nbsp;</td>
        <td align="center"><strong><?php echo $r_sql->fields['uom']; ?></strong>&nbsp;</td>
        <td>
					<strong><?php echo $r_sql->fields['name']; ?></strong>
					<?php if ($r_sql->fields['specification']) { ?>
					<br />
					<span style="font-size:12px;">
					<?php echo stripslashes(nl2br($r_sql->fields['specification'])); ?></span>
					<?php } ?>
					<?php if ($r_sql->fields['notes']) { ?>
					<br /><span style="font-size:12px;"><?php echo stripslashes(nl2br($r_sql->fields['notes'])); ?></span>
					<?php } ?>&nbsp;
				</td>
        <td>&nbsp;</td>
      </tr>
			<?php
			$r_sql->MoveNext();
			}
			?>
			</tbody>
    </table>
		<?php
		}
		?>
		</td>
  </tr>
	<tr>
		<td>
      <table width="100%" border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse">
        <tr valign="top">
          <td width="33%"><br />
            <br />
            <br /></td>
          <td width="33%">Prepared By<br />
            <br />
            <br />
            <?php echo $deliver_by; ?></td>
          <td>Recieved By<br />
            <br />
            <br /></td>
        </tr>
      </table>	
    </td>
	</tr>
</table>
</body>
</html>