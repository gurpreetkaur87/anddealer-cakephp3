<!-- banner start -->
	<div class="row">
		<?php //debug($banner); ?>
		<?php if($banner->link != ''){ ?>
		<a href="<?php echo $banner->link; ?>">
			<?php } ?>
		<div class="col-xs-12 banner home-banner" style="background-image:url('<?php echo DS.'webroot'.DS.'uploads'.DS.'banner'.DS.$banner->image; ?>')" ></div>
			<?php if($banner->link != ''){ ?>
		</a>
			<?php } ?>
			
		
	</div>
<!-- banner end -->

<div class="row block dealer-home-block">
	<div class="divider"></div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6  first">
		<div class="wrapper">
			<a class="col-xs-6 col-sm-6 col-md-6 category-menu category-menu-1" href="/users/stock_enquiry">
				<div class="wrapper">
					<div class="img"><?php echo $this->Html->image('stock.jpg', ['alt' => 'Stock Enquiry']); ?><!--img src="img/stock.jpg" alt="Stock Enquiry" /--></div>
					<span >Stock Enquiry</span>
				</div>
			</a>
			<a class="col-xs-6 col-sm-6 col-md-6 category-menu category-menu-2" href="/technicals/view/10">
				<div class="wrapper">
					<div class="img"><?php echo $this->Html->image('CAL.jpg', ['alt' => 'Quick Calibration Guides']); ?><!--img src="img/spare.jpg" alt="Spare Parts Enquiry" /--></div>
					<span>Quick Calibration Guides</span>
				</div>
			</a>
			<a class="col-xs-6 col-sm-6 col-md-6 category-menu category-menu-3" href="/users/manuals">
				<div class="wrapper">
					<div class="img"><?php echo $this->Html->image('manuals.jpg', ['alt' => 'Manuals']); ?><!--img src="img/manuals.jpg" alt="Manuals" /--></div>
					<span>Manuals</span>
				</div>
			</a>
			<a class="col-xs-6 col-sm-6 col-md-6 category-menu category-menu-4" href="/technicals">
				<div class="wrapper">
					<div class="img"><?php echo $this->Html->image('technical-documents.jpg', ['alt' => 'Technical Documents']); ?><!--img src="img/technical-documents.jpg" alt="Technical Documents"/--></div>
					<span >Technical Documents</span>
				</div>
			</a>
			<a class="col-xs-6 col-sm-6 col-md-6 category-menu category-menu-5" href="/product-diagrams">
				<div class="wrapper">
					<div class="img"><?php echo $this->Html->image('diagrams.png', ['alt' => 'Product Exploded Diagrams']); ?><!--img src="img/diagrams.jpg" alt="Install Diagrams" /--></div>
					<span>Product Exploded Diagrams</span>
				</div>
			</a>
			<a class="col-xs-6 col-sm-6 col-md-6 category-menu category-menu-6" href="/users/priceList">
				<div class="wrapper">
					<div class="img"><?php echo $this->Html->image('price.jpg', ['alt' => 'Price List']); ?><!--img src="img/price.jpg" alt="Price List"/--></div>
					<span>Price List</span>
				</div>
			</a>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 last">
		<div class="wrapper">
			<div class="search-stock">
				<form id="stockSearchForm" action="/users/stock_enquiry" method="get">
					<h2>Stock On Hand Enquiry</h2>
					<label>Item ID #:</label>
					<input type="text" name="itemno" id="itemno" class="form-input" placeholder="Enter a complete item ID" />
					<button class="btn search-btn stock-search-btn" type="submit">Find</button>
				</form>
			</div>
			<div class="search-spare-parts">
				<form id="search-stock-form" action="/users/part_enquiry" method="get">
					<h2>Spare Parts Enquiry</h2>
					<label>Item ID #:</label>
					<input type="text" name="cPartNum" id="cPartNum" class="form-input" placeholder="Enter a complete item ID" />
					<button class="btn search-btn stock-search-btn" type="submit">Find</button>
				</form>
			</div>
			<div class="search-stock" id="stockSearchresult">
				
			</div>

		</div>
	</div>
</div>

<script>
	
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
				htmlStr1 = '<table style="border: 1px solid grey;"><th><td>citemno</td><td>cdescript</td><td>nprice</td><td>available</td></th><tr><td>'+results.ret['citemno']+'</td><td>'+results.ret['cdescript']+'</td><td>'+results.ret['nprice']+'</td><td>'+results.ret['available']+'</td><td></tr></table>';
					jQuery('#stockSearchresult').html(htmlStr1);
				if(results.length > 0) 
				{
					
					
				}
				return false;
            },
    error: function(data) {
        jQuery('#stockSearchresult').html('No Data found');
       return false;
    }
        });
		return false;
	}
</script>