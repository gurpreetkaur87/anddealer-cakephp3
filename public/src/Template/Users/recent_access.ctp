<div class="row block dealer-search-block">				
	<ul class="breadcrumb">
		<li><a href="#">Admin</a></li>
		<li><a href="#" class="current">Search</a></li>
	</ul>
	<!--h2>Dealer Administration Reports</h2>
	<p>The search system allows you to search for a dealer by Company Code, First Name, Last Name and Username, all from the one search field. Simply enter the term you want to search on and the system will return a list of dealers that match term. The system will also accept parts of words or names and will return results based on the closest matches that can be found.</p>
	<div class="dealer-search-form">
	<h2>Dealer Administration Reports</h2>
		<form id="dealer-search-form">
			<input type="text" name="search_string" id="dealer-admin-report" value="<?php if(isset($_REQUEST['search_string'])){echo $_REQUEST['search_string'];} ?>">
			<input type="hidden" name="access_number" id="dealer-admin-report" value="<?php if(isset($_REQUEST['access_number'])){echo $_REQUEST['access_number'];} ?>">
			<input type="hidden" name="code" value="<?php if(isset($_REQUEST['code'])){echo $_REQUEST['code'];} ?>">
			<button type="submit" class="btn dealer-admin-report-button">Execute</button>
		</form>
	</div>
	<h2>Search Results</h2-->
	<p>This information is sorted alphabetically by the Code and then Username</p>
	<div class="table dealer-list-table">
		<div class="table-header dealer-list-table-header">
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('code','Code') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('username','User Name') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('first_name','First Name') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('last_name','Last Name') ?></div>
			<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('logins','Logins') ?></div>
			<div class="table-cell dealer-list-table-cell">Last Access</div>
			<div class="table-cell dealer-list-table-cell action">Edit</div>
			<div class="table-cell dealer-list-table-cell action">Detail</div>
			<div class="table-cell dealer-list-table-cell action">Delete</div>
		</div> 
		<div class="table-body dealer-list-table-body">
			<?php if(!empty($users)) { foreach($users as $user) { ?>
			<div class="table-row dealer-list-table-row">
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->code; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->username; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->firstname; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->lastname; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->logins; ?></a></div>
				<div class="table-cell dealer-list-table-cell"><a href="#"><?php echo $user->last_access; ?></a></div>
				<div class="table-cell dealer-list-table-cell action"><a href="/users/edit/<?php echo $user->userid; ?>"><?php echo $this->Html->image('edit.png', ['alt' => 'A&D Weighing']); ?></a></div>
				<div class="table-cell dealer-list-table-cell action"><a href="/users/view/<?php echo $user->userid; ?>"><?php echo $this->Html->image('details.png', ['alt' => 'A&D Weighing']); ?></a></div>
				<div class="table-cell dealer-list-table-cell action"><a href="/users/delete/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to delete this?');"><?php echo $this->Html->image('bin.png', ['alt' => 'A&D Weighing']); ?></a></div>
			</div><!-- table row end -->
				<?php
				} } else { echo 'No data found.'; }
				?>
		</div><!-- table body end -->	
	</div><!-- table end -->				
</div>
