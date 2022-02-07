<style type="text/css">
	.IN2 {
		background-color: yellowgreen;
	}
	.IN2:hover {
		background-color: darkred;
	}
	.OUT2{
		background-color: blueviolet;
	}
	.OUT2:hover {
		background-color: darkred;
	}
	.HOME {
		background-color: skyblue;
	}
	.PRINT {
		background-color: hotpink;
	}
	.HOME:hover {
		background-color: darkred;
	}
	.PRINT:hover {
		background-color: darkred;
	}
</style>
<div class="row">
	<!-- <button class="btn btn-lg btn-primary btn-block" type="button" id="mint">Mint</button> -->
	  <div class="col-6">
	    <!-- small box -->
	    <div class="IN2 small-box cursor-pointer" id="mint" style="height: 200px;">
	      <div class="inner" style="text-align: center;
vertical-align: middle;
line-height: 200px;">
	        <h1 style="font-size: 100px; color:white;">IN</h1>
	       </div>
	      </div>
	  </div>
	  <!-- ./col -->
	  <div class="col-6">
	    <!-- small box -->
	    <div class="OUT2 small-box cursor-pointer" style="height: 200px;" id="mint_out">
	      <div class="inner" style="text-align: center;
vertical-align: middle;
line-height: 200px;">
	        <h1 style="font-size: 100px">OUT</h1>
	       </div>
	    </div>
	  </div>
  <!-- ./col -->
  
</div>
<div class='row'>
	<div class="col-6">
		<div class="HOME small-box cursor-pointer" style="height:70px;" id="do">
	      <div class="inner" style="text-align: center;
vertical-align: middle;">
	        <h1 style="color:white;">LIST DO</h1>
	       </div>
	      </div>
	</div>
	<div class="col-6">
		<div class="PRINT small-box cursor-pointer" id="print" style="height:70px;">
	      <div class="inner" style="text-align: center;
vertical-align: middle;">
	        <h1 style="font-size: 29px;">PRINT BARCODE</h1>
	       </div>
	    </div>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<div class="PRINT small-box cursor-pointer" id="refresh" style="height:70px;">
	      <div class="inner" style="text-align: center;
vertical-align: middle;">
	        <h1 style="font-size: 29px;">REFRESH</h1>
	       </div>
	    </div>
	</div>
</div>
<script type="text/javascript">
	(function() {
		var iN = document.getElementById("mint");
		const ouT =  document.getElementById("mint_out");
		iN.onclick = () => {
			window.location.href = "http://localhost/assetgateway/?m=receive";
		}
		ouT.onclick = () => {
			window.location.href = "http://localhost/assetgateway/?m=out";
		}
		
		var prinT = document.getElementById("print")
		prinT.onclick = () => {
			window.location.href = "http://localhost/assetgateway/?m=print";
		}

		var _do = document.getElementById("do")
		_do.onclick = () => {
			window.location.href = "http://localhost/assetgateway/?m=do";
		}

		var _refresh = document.getElementById("refresh")
		_refresh.onclick = () => {
			window.location.href = "http://localhost/assetgateway/item/refresh.php";
		}
	})();
</script>