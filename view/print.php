<?php
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        /* special ajax here */
        die("this is ajax");
    }

    // require_once 'http://localhost/assetgateway/dist/barcode/src/Milon/Barcode/DNS1D.php';
    // $br = getBarcodePNG('test', 'C128');
?>
<style type="text/css">
	.BACK {
		background-color: yellowgreen;
	}
	.r1, .r2{
		background-color: blueviolet;
	}
	.r1:hover, .r2:hover ,.BACK:hover{
		background-color: darkred;
	}
</style>
<div class="row">
    <div class="col-2">
		<div class="BACK small-box cursor-pointer" onclick="location.href = '/assetgateway/';" id="mint">
			<div class="inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 30px; color:white;">IN</h1>
			</div>
		</div>
	</div>
</div>
<div class="row">
  	<div class="col-12">
    	<div class="card">
			<div class="card-body">
        		<div class="row" style="margin-top:30px;">
					<div class="col-1">
					</div>
					<div class="col-9">
						<input type="text" name="item_code" id="item_code" class="form-control" placeholder="Item Code/Item Name" autofocus="autofocus" >
					</div>
					<div class="col-2">
						<button class="btn btn-secondary" id="cari_pr">
                            <i class="fa fa-search"></i>
                            CARI</button></div>
					</div>
				</div>
				<div id="cari_div">
                    <iframe src="" name="print-barcode" id="print-barcode" height="0" width="0" frameborder="0"></iframe>
					<div class="row" style="margin-top: 30px; overflow: auto;">
						<div class="col-1">
						</div>
						<div class="col-10">
							<table class="table table-striped" id="tblss">
								<thead>
									<tr>
										<th>ITEM CODE</th>
										<th>ITEM NAME</th>
										<th>
                                            <button type="button" class="btn btn-primary" id="btn-print">Clicke here to print</button>
                                            <button type="button" class="btn btn-danger" id="btn-clear">Clear</button>
                                        </th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="col-1">
						</div>
					</div>
				</div>
			</div>
		</div>
    <!-- /.card -->
	</div>
</div>
<div id="myModal" class="modal" style="overflow: auto" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title">Item</h1>
            <button class="close" data-dismiss="modal" onclick="_close_modal()"><i class="fa fa-times"></i></button>
        </div>
        <div class="modal-body">
            <table class="table table-striped" id="table-item">
                <thead>
                    <tr>
                        <th>ITEM CODE</th>
                        <th>ITEM NAME</th>
                        <th>UOM</th>
                        <th>NOTES</th>
                        <th>QTY</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
      </div>
    </div>
</div>
<script src="dist/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="dist/DataTables/datatables.min.js"></script>
<script>

    var t_item = $("#table-item").DataTable({
        // pageLength : 5
    })

    var t_ss = $("#tblss").DataTable({
        searching : false,
        ordering : false,
        paging : false,
    })

    function remove_print(me){
        var tr = $(me).parents("tr")
        tr.remove()
        var tbody = $(".item_code_post")
        console.log(tbody)
        if(tbody.length == 0){
            t_ss.clear().draw()
        }
    }

    function add_to_print(me){
        var tr = $(me).parents("tr")
        var item_code = tr.find(".item_code")
        var qty = tr.find(".qty")
        var qty_val = qty.val()
        var name = tr.find(".name")
        for (var i = 0; i < qty_val; i++) {
            t_ss.row.add([
                "<span class='item_code_post'>"+item_code.text()+"</span>",
                name.text(),
                "<button type='button' onclick='remove_print(this)' class='btn btn-danger'>Remove from Print</button>"
            ]).draw()
        }
        

        // var i = "<i class='fa fa-check'></i>"
        // $(me).html(i)
        // $(me).prop('disabled', true)
        // $(me).removeClass("btn-primary")
        // $(me).addClass("btn-success")
        
    }

    function printDiv(whichFrame) {
        // printFrame(whichFrame)
    }

    function printFrame(div){
        window.frames[div].focus();
        window.frames[div].print();
    }

    function _close_modal(){
        $("#myModal").hide('modal')
    }

    $(document).ready(function(){
        $("#btn-print").click(function(){
            var tbody = $(".item_code_post")
            if(tbody.length == 0){
                return alert("Please select item to print")
            }

            var item_code = []
            for (let i = 0; i < tbody.length; i++) {
                item_code.push($(tbody[i]).text())
            }

            var url = "http://localhost/assetgateway/view/print_barcode_item.php?barcode="+JSON.stringify(item_code)

            $("#print-barcode").attr("src", url)
        })

        $("#item_code").on("keyup", function(e){
            if(e.keyCode === 13){
                $("#cari_pr").click()
            }
        })

        $('#print-barcode').load(function(){
            $(this).show();
            printFrame('print-barcode')
        });

        $("#btn-clear").click(function(){
            t_ss.clear().draw()
        })

        $("#cari_pr").click(function(){
            var item_code = $("#item_code").val()
            if(item_code == ""){
                return alert("Please insert Item Code / Item Name !")
            }
            var post = {
                    act : "search",
                    param : item_code
                }

            $.ajax({
                url : "http://localhost/assetgateway/item/find.php",
                type : "POST",
                dataType : "json",
                contentType: "application/json",
                data : JSON.stringify(post),
                success : function(response){
                    if(response.success){
                        var items = response.items
                        t_item.clear().draw()
                        items.forEach(function(value, index){
                            t_item.row.add([
                                "<span class='item_code'>"+value.item_code+"</span>",
                                "<span class='name'>"+value.name+"</span>",
                                "<span class='uom'>"+value.uom+"</span>",
                                "<span class='uom'>"+value.detail+"</span>",
                                "<input type='text' class='form-control qty' value='1'>" + "<button type='button' onclick='add_to_print(this)' class='btn btn-primary'>Add to Print</button>",
                            ]).draw()
                        })
                    }
                    $("#myModal").show('modal')
                }
            })
        })
        
    })
</script>