<div class="row block dealer-search-block">				
	<ul class="breadcrumb">
		<li><a href="#">Admin</a></li>
		<li><a href="#" class="current">Search</a></li>
	</ul>
	<h2>Dealer Search</h2>
	<p>The search system allows you to search for a dealer by Company Code, First Name, Last Name and Username, all from the one search field. Simply enter the term you want to search on and the system will return a list of dealers that match term. The system will also accept parts of words or names and will return results based on the closest matches that can be found.</p>
	<div class="dealer-search-form">
	<h2>Enter search term</h2>
		<form id="dealer-search-form">
			<input type="text" name="search_string" id="dealer-admin-report" value="<?php if(isset($_REQUEST['search_string'])){echo $_REQUEST['search_string'];} ?>">
			<button type="submit" class="btn dealer-admin-report-button">Execute</button>
		</form>
	</div>
	<div class="dealer-search-result-table">
		<h2>Search Results</h2>
		<p>This information is sorted alphabetically by the Code and then Username</p>
		<div class="table dealer-list-table">
			<div class="table-header dealer-list-table-header">
				<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('code','Code') ?></div>
				<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('username','User Name') ?></div>
				<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('first_name','First Name') ?></div>
				<div class="table-cell dealer-list-table-cell"><?= $this->Paginator->sort('last_name','Last Name') ?></div>
				<div class="table-cell dealer-list-table-cell">Logins</div>
				<div class="table-cell dealer-list-table-cell">Last Access</div>
				<div class="table-cell dealer-list-table-cell action">Edit</div>
				<div class="table-cell dealer-list-table-cell action">Detail</div>
				<div class="table-cell dealer-list-table-cell action">Delete</div>
			</div> 
			<div class="table-body dealer-list-table-body ">
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
					<div class="table-cell dealer-list-table-cell action last"><?= $this->Form->postLink(__(''), ['action' => 'delete', $user->userid], ['confirm' => __('Are you sure you want to delete # {0}?', $user->userid)]) ?></div>
				</div><!-- table row end -->
					<?php
					} } else { echo 'No data found.'; }
					?>
			</div><!-- table body end -->	
		</div><!-- table end -->	
	</div>			
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		var pathname = window.location.href;
		//var pathname_text = pathname.text();
		//console.log(pathname);
		if(pathname.indexOf('?search') > -1){
			//console.log(1);
			jQuery('.dealer-search-result-table').show();
		}else{
			//console.log(2);
		}
	});


</script>
