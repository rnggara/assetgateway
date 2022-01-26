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
						<input type="text" name="doNumber" id="doNumber" class="form-control" placeholder="DO Number / DO ID / DO URL" autofocus="autofocus" >
					</div>
					<div class="col-2">
						<button class="btn btn-light" id="cari_pr">CARI</button></div>
					</div>
				</div>
				<div id="cari_div">
					<div class="row" style="margin-top: 30px;">
						<div class="col-1">
						</div>
						<div class="col-9">
							<input type="text" id="bacaDO" readonly class="form-control">
						</div>
						<div class="col-1">
							<button id="receive_btn" class="btn btn-xl btn-success"> Receive</button>
						</div>
					</div>
					<div class="row" style="margin-top: 30px; overflow: auto;">
						<div class="col-1">
						</div>
						<div class="col-10">
							<table class="table table-striped" id="tblss">
								<thead>
									<tr>
										<th>ITEM</th>
										<th>QTY</th>
										<th>PRINT</th>
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
<script type="text/javascript">
	const cari_pr = document.getElementById("cari_pr");
	const cari_div = document.getElementById("cari_div");
	const doNumber = document.getElementById("doNumber");
	var modal = document.getElementById("myModal");
	var btn = document.getElementById("myBtn");
	var span = document.getElementsByClassName("close")[0];
	const ms1 = document.getElementById("modal-success");
	const ms2 = document.getElementById("modal-failed");
	var bacaDO = document.getElementById("bacaDO");
	const receive_btn = document.getElementById("receive_btn");
	var pdo_id = 0;

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

	function do1(v, id){
		$('#tblss').find("tr:gt(0)").remove();
		var _id = id.split("/")
		type = "id"
		if(_id.length > 1){
			if(_id[2] == "DO"){
				type = "no"
				id = id
			} else {
				id = _id[_id.length - 1]
			}
		}
		console.log(id)
		const params = {
			act: "v",
			do_id: id,
			type : type
		};
		const options = {
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			method: 'POST',
			body: JSON.stringify( params )  
		};
		fetch( 'http://localhost/assetgateway/pr/view_receive.php', options )
		.then( response => response.json() )
		.then( response => {
			if(response.success){
				bacaDO.value = response.do;
				for(var i = 0; i < response.dtl.length; i++) {
					var obj = response.dtl[i];
					var row = "<tr><td>" + obj.item + '</td><td>' + obj.qty + "</td><td><input type='button' value='Print' onClick=printReport("+response.dtl[i].print+")></td></tr>";
					// get the current table body html as a string, and append the new row
					var html = document.getElementById("tblss").innerHTML + row;
					// set the table body to the new html code
					document.getElementById("tblss").innerHTML = html;
					// console.log(obj.qty+"<br />");
				}
				cari_div.style.visibility = 'visible';
				receive_btn.disabled = false
			} else {
				toastr.error(response.msg);
				bacaDO.value = "";
				receive_btn.disabled = true
			}
		});
	}

	function approveDO(id){
		const params = {
			act: "r",
			do_id: id
		};
		const options = {
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			method: 'POST',
			body: JSON.stringify( params )  
		};
		fetch( 'http://localhost/assetgateway/pr/view_receive.php', options )
		.then( response => response.json() )
		.then( response => {
			if(response.success == 1){
				modal.style.display = "block";
				ms1.style.display = "block";
				ms2.style.display = "none";
			} else {
				modal.style.display = "block";
				ms1.style.display = "none";
				ms2.style.display = "block";
			}
			// bacaDO.value = response.do;
			// for(var i = 0; i < response.dtl.length; i++) {
			// 	var obj = response.dtl[i];
			// 	var row = '<tr><td>' + obj.item + '</td><td>' + obj.qty + '</td><td>' +  obj.print + '</td></tr>';
			// 	// get the current table body html as a string, and append the new row
			// 	var html = document.getElementById("tblss").innerHTML + row;
			// 	// set the table body to the new html code
			// 	document.getElementById("tblss").innerHTML = html;
			// 	// console.log(obj.qty+"<br />");
			// }
			// cari_div.style.visibility = 'visible';
		});
	}

	(function() {
		doNumber.focus();
		cari_div.style.visibility = "hidden";
		cari_pr.onclick = () => {
			do1("v", doNumber.value);
			pdo_id = doNumber.value;
		};

		doNumber.addEventListener("keyup", function(event) {
			if (event.keyCode === 13) {
				do1("v", doNumber.value);
				pdo_id = doNumber.value;
			}
		});
		receive_btn.onclick = () => {
			approveDO(pdo_id);
		}
		span.onclick = function() {
			modal.style.display = "none";
			window.location.href = "http://localhost/assetgateway/?m=";
		}
	})();
</script>