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
<div class="row">
	<div class="col-6">
		<div class="BACK small-box float-left cursor-pointer" onclick="location.href = '/assetgateway/';" id="mint">
			<div class="inner" style="text-align: center; vertical-align: middle;">
				 <h1 style="font-size: 30px; color:white;">BACK</h1>
			</div>
		</div>
	</div>
</div>
<div class="row">
  	<div class="col-12">
    	<div class="card">
			<div class="card-body">
                <div class="row" style="margin-top: 30px; overflow: auto;">
                    <div class="col-12">
                        <table class="table table-striped" id="tblss">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DO#</th>
                                    <th>DESTINATION</th>
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
    <!-- /.card -->
	</div>
</div>
<script type="text/javascript" src="dist/DataTables/datatables.min.js"></script>
<script>
    function printFrame(div){
        window.frames[div].focus();
        window.frames[div].print();
    }

    function _delete(id){
        var _confirm = confirm("Delete ?")
        if(_confirm){
            var post = {
                act : "delete",
                id : id
            }
            $.ajax({
                url : "http://localhost/assetgateway/pr/view_do.php",
                type : "POST",
                dataType : "json",
                contentType: "application/json",
                data : JSON.stringify(post),
                success : function(response){
                    if(response.success){
                        window.location.reload()
                    }
                }
            })
        }
        console.log(_confirm)
    }

    $(document).ready(function(){
        var t_do = $("#tblss").DataTable()
        var post = {
                    act : "view",
                }
        $.ajax({
            url : "http://localhost/assetgateway/pr/view_do.php",
            type : "POST",
            dataType : "json",
            contentType: "application/json",
            data : JSON.stringify(post),
            success : function(response){
                if(response.success){
                    var items = response.items
                    t_do.clear().draw()
                    items.forEach(function(value, index){
                        t_do.row.add([
                            value.num,
                            "<span class='item_code'>"+value.no_do+"</span>",
                            "<span class='name'>"+value.wh_destination+"</span>",
                            "<button type='button' onclick=\"printFrame('print-"+value.id+"')\" class='btn btn-primary btn-sm'>Print</button><button type='button' onclick='_delete("+value.id+")' class='btn btn-danger btn-sm'>Delete</button>" + value.print
                        ]).draw()
                    })
                }
            }
        })
    })
</script>