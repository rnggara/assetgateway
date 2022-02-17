<style type="text/css">
	.BACK {
		background-color: blue;
	}
    .receiveStyle {
        background-color: green;
    }
	.r1, .r2{
		background-color: blueviolet;
	}
	.r1:hover, .r2:hover ,.BACK:hover , .receiveStyle:hover{
		background-color: darkred;
	}
</style>
<link rel="stylesheet" href="dist/css/select2.css">
<div class="row">
	<div class="col-6">
		<div class="BACK small-box float-left cursor-pointer" onclick="location.href = '/assetgateway/';" id="mint">
			<div class="inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 30px; color:white;">OUT</h1>
			</div>
		</div>
	</div>
    <div class="col-6">
		<div class="receiveStyle small-box float-right cursor-pointer">
			<div class="inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 30px; color:white;">SEND</h1>
			</div>
		</div>
	</div>
</div>
<div class="row">
  	<div class="col-12">
    	<div class="card">
			<div class="card-body">
				<div id="cari_div">
					<form id="insertForm" name="insertForm" enctype="multipart/form-data" method="post" >
					<div class="row" style="margin-top: 30px;">
						<div class="col-12">
							<select id="to_address" class="select2"> </select>
						</div>
					</div>
                    <div class="row" style="margin-top: 30px;">
						<div class="col-6">
							<div class="row">
								<div class="col-8">
									<input type="text" id="icode" name="icode" class="form-control" placeholder="Item Code" autofocus="autofocus">
								</div>
								<div class="col-4">
									<input type='button' id="c2" value='Cek'>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-8">
									<input type="text" id="item_code" name="item_code" class="form-control" placeholder="Item Code / Item Name" autofocus="autofocus">
								</div>
								<div class="col-4">
									<input type='button' id="c1" value='Cek'>
								</div>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top: 30px; overflow: auto;">
						<div class="col-12">
							<table class="table table-striped" id="tblss">
								<thead>
									<tr>
										<th>ITEM</th>
										<th>NAME</th>
										<th>QTY</th>
										<th>PRINT</th>
										<th>#</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
    <!-- /.card -->
	</div>
</div>
<div id="modalItem" class="modal" style="overflow: auto" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
      </div>
    </div>
</div>
<div id="myModal" class="modal modal-md">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id='modal-success'>
        <img src='img/thumbsup.png' width='10%' />
        <br />
        <h5>DO RECEIVED</h5>
        
        </div>
    <div id='modal-failed'>
        <h3>Something went wrong.</h3>
        <br />
        <img src='img/note.png' width='10%' />
        <br />
        <h5>
        Please try again.
        </h5>
    </div>
  </div>

</div>
<script type="text/javascript" src="dist/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="dist/js/select2.full.js"></script>
<script type="text/javascript">
	var modal = document.getElementById("myModal");
	var span = document.getElementsByClassName("close")[0];
	const ms1 = document.getElementById("modal-success");
	const ms2 = document.getElementById("modal-failed");
    const toAddress = document.getElementById("to_address");
    const item_code = document.getElementById("item_code");
	const c1 = document.getElementById("c1");
    var datax = [];
	var pdo_id = 0;
	function getItem(me, val){
		const params = {
			act: "v",
			item_code: val
		};
		const options = {
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			method: 'POST',
			body: JSON.stringify( params )  
		};
		fetch( 'http://localhost/assetgateway/pr/out_pr.php', options )
		.then( response => response.json() )
		.then( response => {
			if(response.success == 1){
				if($('#tblss tbody #row'+response.id).length){
					toastr.success("Data Added");
					var T1 = parseFloat($("#res"+response.id).val()) + 1;
					$("#res"+response.id).val(T1);
				} else {
					toastr.success("Data Added");
					$("#tblss tbody").append("<tr>"
					+"<td id='row"+response.id+"'><input type='hidden' name='item_id[]' value='"+response.id+"'>"+response.item_code+"</td>"
					+"<td>"+response.name+"</td>"
					+"<td><input type='text' id='res"+response.id+"' name='item_qty[]' value='1'  class='form-control'></td>"
					+"<td><input type='button' value='Print' onClick=printReport("+String(response.id)+")></td>"
					+'<td><input type="button" class="remove_item" value="remove"/></td>'
					+"</tr>"
					);
				}
			} else {
				toastr.error(response.msg);
			}
			
		});
	}

	function _close_modal(){
        $("#modalItem").hide('modal')
    }

	function printReport(c){
      var printWindow = window.open( "view/print_barcode.php?barcode="+c, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
      printWindow.addEventListener('load', function() {
          if (Boolean(printWindow.chrome)) {
              printWindow.print();
              setTimeout(function(){
                  printWindow.close();
              }, 500);
          } else {
              printWindow.print();
              printWindow.close();
          }
      }, true);
    }
	
	(function() {
		$(".printClass").click(function(){
			alert($(this).attr('id'));
		});

		// receiveStyle.onclick = () => {
		$(".receiveStyle").click(function(){
			// var postData = $("#form_pln").serialize();
			let iD = document.getElementsByName("item_id[]");
			let qt = document.getElementsByName("item_qty[]");	
			var x = [], y = [];
			for(i=0; i < iD.length; i++){
				x.push(iD[i].value);
				y.push(qt[i].value);
			}
			let params = {
				act: "e",
				item_id: x,
				item_qty:  y,
				to_id: $("#to_address").val(),
			};

			let options = {
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				method: 'POST',
				body: JSON.stringify( params )  
			};
			fetch( 'http://localhost/assetgateway/pr/out_pr.php', options )
			.then( response => response.json() )
			.then( response => {
				console.log(response);
				if(response.success == 1){
					var id = response.id
					toastr.success(response.msg);
					var printWindow = window.open( "view/do_print.php?id="+id+"&act=print", 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
					printWindow.addEventListener('load', function() {
						if (Boolean(printWindow.chrome)) {
							printWindow.print();
							setTimeout(function(){
								printWindow.close();
							}, 500);
						} else {
							printWindow.print();
							printWindow.close();
						}
					}, true);
					window.location.href = "http://localhost/assetgateway/?m=out";
				} else {
					toastr.error(response.msg);
				}
			});
		});
		
		// c1.onclick = () => {
		// 	// console.log(no_do.value);
		// 	if(no_do.value){
		// 		getItem(no_do.value);
		// 		no_do.value = "";
		// 		no_do.focus();
		// 	} else {
		// 		alert("nga bboleh kosong");
		// 		no_do.focus();
		// 	}
		// }

		$("#tblss").on('click','.remove_item',function(){
			$(this).parent().parent().remove();
		});

        item_code.addEventListener("keyup", function(event) {
			if (event.keyCode === 13) {
				$("#c1").click()
			}
			
		});
        const params = {
			// act: "v",
			// do_id: "v"
		};
		const options = {
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			method: 'POST',
			body: JSON.stringify( params )  
		};
		fetch( 'http://localhost/assetgateway/pr/view_asset_wh.php', options )
		.then( response => response.json() )
		.then( response => {
			for(var i = 0; i < response.length; i++) {
                let newData = {
                    id: response[i].id,
                    text: response[i].text,
                };
                datax.push(newData)
			}
            $("#to_address").select2({
                data: datax,
                width: "100%"
            });
		});
	})();

	var t_item = $("#table-item").DataTable({
        // pageLength : 5
    })

	$(document).ready(function(){
		console.log("test")
		$("#c2").click(function(){
			var item_code = $("#icode").val()
			getItem($(this), item_code)
		})

		$("#icode").on("keyup", function(e){
			if(e.keyCode === 13){
				$("#c2").click()
			}
		})

		$("#c1").click(function(){
			console.log("test")
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
                                "<button type='button' onclick=\"getItem(this, '"+value.item_code+"')\" class='btn btn-primary'>Add</button>"
                            ]).draw()
                        })
                    }
                    console.log(response)
                    $("#modalItem").show('modal')
                }
            })
        })
	})
</script>