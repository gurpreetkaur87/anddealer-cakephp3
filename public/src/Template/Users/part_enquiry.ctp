<div class="row block stock-block stock-main-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Spare Parts Enquiry') ?></li>
        </ul>
    </div>

    <div class="col-xs-12 col-md-6">
        <form id="sparePartsform" onsubmit="return sparePartsEnquiry()" method="post">
            <h2>Spare Parts Enquiry <span style="font-weight:400;">(Please note: This may take a couple of seconds to load)</span></h2>
            <label>Item ID #:</label>
            <input type="text" name="cPartNum" id="cPartNum" value="<?php if(isset($_GET['cPartNum'])){ echo $_GET['cPartNum']; } ?>" class="form-input" placeholder="Enter a complete item ID" />
            <button class="btn search-btn stock-search-btn" type="submit">Find</button>
        </form>
        <div id="spareSearchresult" >
	
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

	function sparePartsEnquiry()
	{
		var datastring = 'itemno='+jQuery("#cPartNum").val();
		//console.log(datastring);
        jQuery.ajax({
            url: "/users/sparePartsSearch",
            type: "post",
            //data: form_data,
            data: datastring,
            dataType: 'json',
			beforeSend: function() {
				jQuery('#spareSearchresult').html('<img class="loader" src="/img/loading2.gif" style="width:20px;">');
			},
            success: function (results)
            {
				var htmlStr2 = '';
				
				//alert(results.ret.length);
				
				if(results.ret.value == false) 
					{
					jQuery('#spareSearchresult').html('We are unable to locate this particular part number at this time, please call A&D for assistance.');
       return false;
					
				}
				else
				{
					
				htmlStr2 = '<h2 sytle="margin-top: 20px"></h2><div class="table spare-result-table"><div class="table-header spare-result-table-header"><div class="table-cell spare-result-table-cell">Part Number</div><div class="table-cell spare-result-table-cell">Model</div><div class="table-cell spare-result-table-cell">Price</div><div class="table-cell spare-result-table-cell">Quantity Available</div></div><div class="table-body spare-result-table-body "><div class="table-row spare-result-table-row"><div class="table-cell dealer-list-table-cell">'+results.ret['citemno']+'</div><div class="table-cell dealer-list-table-cell">'+results.ret['cdescript']+'</div><div class="table-cell dealer-list-table-cell">'+results.ret['nprice']+'</div><div class="table-cell dealer-list-table-cell">'+results.ret['available']+'</div></div></div></div>';
					jQuery('#spareSearchresult').html(htmlStr2);
				}
				
				return false;
            },
    error: function(data) {
        jQuery('#spareSearchresult').html('We are unable to locate this particular part number at this time, please call A&D for assistance.');
       return false;
    }
        });
		return false;
	}
</script>