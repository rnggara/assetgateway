<?php

//UPDATE
if ($_POST['Submit'] == 'Save')
{
	foreach($_POST['transfer_type'] as $transfer_type)
	{
		// echo $transfer_type;
		$record2['note'] = $transfer_type;
		// $db_asset->AutoExecute('do_detail', $record2, 'UPDATE', 'id = ');
		// $db_asset->Execute("UPDATE do_detail SET note = '".$transfer_type."' WHERE id = '".."'");
	}
} 

//MASTER DATA
if ($_REQUEST['id'])
{
	// ambil data dari tabel
	$sql  = "SELECT * FROM `do` WHERE id = '".$_REQUEST['id']."' ";
	$r_sql1 = $db_asset->Execute($sql);

	$sql  = "SELECT * FROM do_detail WHERE do_id = '".$_REQUEST['id']."' ";
	$r_sql2 = $db_asset->Execute($sql);

	while (!$r_sql2->EOF)
	{
		$rows['item_id'][] = $r_sql2->fields['item_id'];
		$rows['qty'][] = $r_sql2->fields['qty'];
		$r_sql2->MoveNext();
	}
	$disabled = 'DISABLED';
}

?>


<h3 class="">
	Edit Delivery Order
</h3>
<hr class="n_button">

<div class="row">
	<div class="col-md-6">
		<h4 class="">
			Details
		</h4>
		<hr>

		<form class="form-horizontal" name="good_moving" id="good_moving" method="post" action="">
			<div class="form-group">
				<label for="" class="col-sm-3 control-label">From</label>
				<div class="col-sm-9">
					<!-- <select class="form-control" id="from" name="from">
						<?php
						foreach($arrWH as $k => $v)
						{
							$selected = ($k == $r_sql1->fields['from_id']) ? 'SELECTED' : '';
							printf("<option value='%s' %s>%s</option>\n", $k, $selected, $v);
						}
						?>
					</select> -->
<select name="from" id="from" class="form-control">
	<option value="">-Choose-</option>
	<?php
	//$db_asset->debug=1;
		$ware_from = "SELECT warehouses.id as id_ware, warehouses.name
									from warehouses order by name asc";
		$sql_ware_from = $db_asset->Execute($ware_from);
		while(!$sql_ware_from->EOF){
			$ware_id_from = $sql_ware_from->fields['id_ware'];
			$ware_from_fields = $sql_ware_from->fields['name'];
			if($ware_id_from == $r_sql1->fields['from_id']){
				?>
				<option value="<?=$ware_id_from?>" selected><?=$ware_from_fields?></option>
			<?php
			}else{
			?>
			<option value="<?=$ware_id_from?>"><?=$ware_from_fields?></option>
		<?php
			}
		$sql_ware_from->MoveNext();
		}
	?>
	<!-- <option value='13' >ART TOWER</option>
	<option value='9' >ART TOWER STORAGE</option>
	<option value='3' >HEXINDO</option>
	<option value='8' >KALIBRATION</option>
	<option value='12' >PABRIKASI</option>
	<option value='2' >PSI TOWER</option>
	<option value='5' >PT VFI</option>
	<option value='11' >Rumah Putih</option>
	<option value='7' >TAPEN</option>
	<option value='10' >TATELY</option>
	<option value='1' >WH CILEUNGSI</option> -->
</select>

				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-3 control-label">To</label>
				<div class="col-sm-9">
					<!-- <select class="form-control" name="to" id="to">
						<?php
						foreach($arrWH as $k => $v)
						{
							$selected = ($k == $r_sql1->fields['to_id']) ? 'SELECTED' : '';
							printf("<option value='%s' %s>%s</option>\n", $k, $selected, $v);
						}
						?>
					</select> -->
<select name="to" id="to" class="form-control">
	<option value="">-Choose-</option>
	<?php
	//$db_asset->debug=1;
		$ware_to = "SELECT warehouses.id as id_ware, warehouses.name
									from warehouses order by name asc";
		$sql_ware_to = $db_asset->Execute($ware_to);
		while(!$sql_ware_to->EOF){
			$ware_id_fields = $sql_ware_to->fields['id_ware'];
			$ware_to_fields = $sql_ware_to->fields['name'];
			if($ware_id_fields == $r_sql1->fields['to_id']){
				?>
				<option value="<?=$ware_id_fields?>" selected><?=$ware_to_fields?></option>
			<?php
			}else{
			?>
			<option value="<?=$ware_id_fields?>"><?=$ware_to_fields?></option>
		<?php
			}
		$sql_ware_to->MoveNext();
		}
	?>
	<!--<option value='13' >ART TOWER</option>
	<option value='9' >ART TOWER STORAGE</option>
	<option value='3' >HEXINDO</option>
	<option value='8' >KALIBRATION</option>
	<option value='12' >PABRIKASI</option>
	<option value='2' >PSI TOWER</option>
	<option value='5' >PT VFI</option>
	<option value='11' >Rumah Putih</option>
	<option value='7' >TAPEN</option>
	<option value='10' >TATELY</option>
	<option value='1' >WH CILEUNGSI</option> -->
</select>

				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-3 control-label">Division</label>
				<div class="col-sm-9">
					<!-- <select class="form-control" name="division_do">
						<?php
						foreach($arrDivision as $k => $v)
						{
							$selected = ($k == $r_sql1->fields['division']) ? 'SELECTED' : '';
							printf("<option value='%s' %s>%s</option>\n", $k, $selected, $v);
						}
						?>
					</select> -->
<select name="division" class="form-control">
	<option value=''>-Choose-</option>
	<option value='Asset' SELECTED>Asset</option>
	<option value='Finance' >Finance</option>
	<option value='GA' >GA</option>
	<option value='HRD' >HRD</option>
	<option value='HSE' >HSE</option>
	<option value='IT' >IT</option>
	<option value='Laboratory' >Laboratory</option>
	<option value='Marketing' >Marketing</option>
	<option value='Operation' >Operation</option>
	<option value='Procurement' >Procurement</option>
	<option value='Production' >Production</option>
	<option value='QC' >QC</option>
	<option value='Receiptionist' >Receiptionist</option>
	<option value='Secretary' >Secretary</option>
	<option value='Technical' >Technical</option>
</select>

				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-3 control-label">Delivery Time</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="deliver_date" name="deliver_date" value="<?php echo $r_sql1->fields['deliver_date']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-3 control-label">Notes</label>
				<div class="col-sm-9">
					<textarea class="form-control" rows="5" name="notes" id="notes"><?php echo stripslashes($r_sql1->fields['notes']); ?></textarea>
				</div>
			</div>

		</div>
		<div class="col-md-6">
			<h4 class="">
				Moving Item
			</h4>
			<hr>
			<form method="post" action="">
				<table class="table table-hover table-responsive" id="list_item">
					<thead>
						<tr>
							<th>#</th>
							<th>Item Code</th>
							<th>Item Name</th>
							<th class="text-center">UoM</th>
							<th class="text-center">Qty</th>
							<th class="text-center">Transfer Type</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<?php
					$sql2  = "SELECT do_detail.*,items.name,items.uom FROM do_detail
					LEFT JOIN items ON items.item_code = do_detail.item_id
					WHERE do_id = '".$_REQUEST['id']."' ";
					$r_sql2 = $db_asset->Execute($sql2);
					$no = 1;
					while (!$r_sql2->EOF)
					{
						if($r_sql2->fields['note'] == "Transfer") $transfer = "selected";
						if($r_sql2->fields['note'] == "Use") $use = "selected";

						$h_2 = $db_asset2->Execute("SELECT * FROM items WHERE items.item_code  = '".$r_sql2->fields['item_id']."'");
						?>
						<tr align="center">
							<td><?php echo $no++; ?></td>
							<td class="text-center">
								<?php echo $r_sql2->fields['item_id']; ?>
								<input type="hidden" name="item_code[]" value="<?php echo $r_sql2->fields['item_id']; ?>">
							</td>
							<td class="text-center"><?php echo $h_2->fields['name']; ?><?php echo $r_sql2->fields['name']; ?></td>
							<td class="text-center"><?php echo $h_2->fields['uom']; ?><?php echo $r_sql2->fields['uom']; ?></td>
							<td><input name="qty[]" type="number" id="qty[]" class="form-control" value="<?php echo $r_sql2->fields['qty']; ?>" /></td>
							<td>
								<select name="transfer_type[]" class="form-control">
									<option value="Transfer" <?= $transfer;?> >Transfer</option>
									<option value="Use" <?= $use;?> >Transfer & Use</option>
								</select>
							</td>
							<td>
								<a href="?m=do_app&del=<?php echo $r_sql2->fields['id']; ?>" onclick="return confirm('Are you sure you want to delete?'); " class="btn btn-default btn-xs"><i class="fa fa-trash"></i> </a>
							</td>
						</tr>
						<?php
						$r_sql2->MoveNext();
					}
					?>
				</table>

				<br/>
				<p><em style="font-size:10pt">*UoM is Unit of Measurement</em></p>
			</div>

		</div>
		<hr>
		<!-- for printing -->
		<?php
		if ($_REQUEST['id'] && $r_sql1->fields['approved_time'] != NULL)
		{
			?>
			<a href="javascript:framePrint('print_frame');" target="_self" class="btn btn-success btn-lg pull-right"><i class="fa fa-print"></i> Print</a>
			<iframe height="0" width="0" name="print_frame" frameborder="0" src="do_print.php?id=<?php echo $_GET['id'];?>" ></iframe>
			<?php
		}
		?>
		<br>
		<br>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#item_id').val('');
		$("#item").autocomplete({
			source: "../../function/fnc_ajax.php?w=fr",
			minLength: 1,
			select: function(event, ui) {
				$('#item_id').val(ui.item.abbrev);
			}
		});
	});

	function deleteRow(o){
		var p = o.parentNode.parentNode;
		p.parentNode.removeChild(p);
	}

	function addInput(trName){
		var newdiv = document.createElement('tr');
		newdiv.innerHTML = "<td align='center'><input type='hidden' name='item[]' value='"+$("#item").val()+"'>"+$("#item").val()+"</td><td align='center'><input type='hidden' name='qty[]' value='"+$("#qty").val()+"'>"+$("#qty").val()+"</td><td align='center'><input type='hidden' name='transfer_type[]' value='"+$("#transfer_type").val()+"'>"+$("#transfer_type").val()+"</td><td align='center'><button type='input' onClick='deleteRow(this)' class='btn btn-default btn-xs'><i class='fa fa-trash'></i></button></td>";
		document.getElementById(trName).appendChild(newdiv);
	}
</script>