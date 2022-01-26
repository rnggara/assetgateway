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
		<div class="BACK small-box" id="mint">
			<div class="inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 50px; color:white;">IN</h1>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top: 50px;">
	<div class="col-md-6">
		<div class="small-box" id="r1">
			<div class="r1 inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 50px; color:white;">RECEIVE WITH D.O</h1>
			</div>
		</div>
	</div>
	<!-- <div class="col-md-6">
		<div class="r2 small-box" id="r2">
			<div class="inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 50px; color:white;">NO D.O</h1>
			</div>
		</div>
	</div> -->
</div>
<script type="text/javascript">
	(function() {
		var r1 = document.getElementById("r1");
		r1.onclick = () => {
			window.location.href = "http://localhost/assetgateway/?m=receive";
		}
		// var IN2 = document.getElementsByClassName("IN2");
		// IN2.onHover = () => {
		// 	this.style.backgroundColor = "green";
		// }
	})();
</script>