<div class="row block dealer-search-block">				
	<ul class="breadcrumb">
		<li><a href="#">Admin</a></li>
		<li><a href="#" class="current">Search</a></li>
	</ul>
	<h2>Dealer Administration Reports</h2>
	<p>The search system allows you to search for a dealer by Company Code, First Name, Last Name and Username, all from the one search field. Simply enter the term you want to search on and the system will return a list of dealers that match term. The system will also accept parts of words or names and will return results based on the closest matches that can be found.</p>
	<div class="dealer-search-form">
	<h2>Dealer Administration Reports</h2>
		<form id="dealer-search-form">
			<input type="text" name="search_string" id="dealer-admin-report" value="<?php if(isset($_REQUEST['search_string'])){echo $_REQUEST['search_string'];} ?>">
			<button type="submit" class="btn dealer-admin-report-button">Execute</button>
		</form>
	</div>
	<h2>Search Results</h2>
	<p>This information is sorted alphabetically by the Code and then Username</p>
	<div class="table dealer-list-table">
		<div class="table-header dealer-list-table-header">
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('Users.code','Code') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('Users.username','User Name') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('Users.first_name','First Name') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('Users.last_name','Last Name') ?></div>
		</div> 
		<div class="table-body dealer-list-table-body">
			<?php if(!empty($users)) { foreach($users as $user) { ?>
			<div class="table-row dealer-list-table-row">
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->code; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->username; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->first_name; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->last_name; ?></a></div>
			</div><!-- table row end -->
				<?php
				} } else { echo 'No data found.'; }
				?>
		</div><!-- table body end -->	
	</div><!-- table end -->				
</div>
