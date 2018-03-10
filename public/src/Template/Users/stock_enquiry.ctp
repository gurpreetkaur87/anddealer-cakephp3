<div class="row block stock-block stock-main-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Stock Enquiry') ?></li>
        </ul>
    </div>


    <div class="col-xs-12 col-md-6 form1">
        <form id="stockSearchForm" onsubmit="return stockEnquiry()" method="post">       
            <h2>Stock On Hand Enquiry <span style="font-weight:400;">(Please note: This may take a couple of seconds to load)</span></h2>
            <label>Item ID #:</label>
            <input type="text" name="itemno" value="<?php if(isset($_GET['itemno'])){ echo $_GET['itemno']; } ?>" id="itemno" class="form-input" placeholder="Enter a complete item ID" required />
            <button class="btn search-btn stock-search-btn" type="submit">Find</button>
        </form>
        <div id="stockSearchresult" >
	
		</div>
    </div>


</div>

<script>
	<?php
	if(isset($_GET['itemno'])) {
	?>
	stockEnquiry();
	<?php
	} else if(isset($_GET['cPartNum']) ) {
		?>
	sparePartsEnquiry();
	
	<?php
	}
	
		?>
	function stockEnquiry()
	{
		//var datastring = jQuery("#stockSearchForm").serialize();
		var datastring = 'itemno='+jQuery("#itemno").val();
		//console.log(datastring);
        jQuery.ajax({
            url: "/users/stockSearch",
            type: "post",
            //data: form_data,
            data: datastring,
            dataType: 'json',
			beforeSend: function() {
				jQuery('#stockSearchresult').html('<img class="loader" src="/img/loading2.gif" style="width:20px;">');
			},
            success: function (results)
            {
				var htmlStr1 = '';
				console.log(results.length);
				console.log(results.ret['citemno']);
				console.log(results.ret['cdescript']);
				console.log(results.ret['nprice']);
				console.log(results.ret['available']);
				console.log(results.ret['cdescript']);
				htmlStr1 = '<h2 sytle="margin-top: 20px"></h2><div class="table stock-result-table"><div class="table-header stock-result-table-header"><div class="table-cell stock-result-table-cell">Part Number</div><div class="table-cell stock-result-table-cell">Model</div><div class="table-cell stock-result-table-cell">Price</div><div class="table-cell stock-result-table-cell">Quantity Available</div></div><div class="table-body stock-result-table-body "><div class="table-row stock-result-table-row"><div class="table-cell dealer-list-table-cell">'+results.ret['citemno']+'</div><div class="table-cell dealer-list-table-cell">'+results.ret['cdescript']+'</div><div class="table-cell dealer-list-table-cell">'+results.ret['nprice']+'</div><div class="table-cell dealer-list-table-cell">'+results.ret['available']+'</div></div></div></div>';
					jQuery('#stockSearchresult').html(htmlStr1);
				
				return false;
            },
    error: function(data) {
        jQuery('#stockSearchresult').html('We are unable to locate this particular part number at this time, please call A&D for assistance.');
       return false;
    }
        });
		return false;
	}
	

</script>