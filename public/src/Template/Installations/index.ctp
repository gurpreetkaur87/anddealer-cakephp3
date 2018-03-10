<div class="row block tech-block" >
	<div class="col-xs-12" style="position: relative">
		<ul class="breadcrumb">
			<li><a href="#">Installation Diagrams</a></li>
		</ul>
		<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
	        <div class="add-button"><?= $this->Html->link(__('Add New Installation Diagrams Document'), ['action' => 'add']) ?></div>
			<?php } ?>
	</div>
	<div class="table install-table">
		<div class="table-header install-table-header">
			<div class="table-cell install-table-cell">Drawing</div>
			<div class="table-cell install-table-cell">Comment</div>
		</div>
		<div class="table-body install-table-body">
			<?php foreach ($installations as $installation): ?>
			<div class="table-row install-table-row">
				
				
				
				<div class="table-cell install-table-cell">
					
				<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
                <a href="/installations/edit/<?php echo $installation->id; ?>" class="edit-icon"><?php echo $this->Html->image('edit.png', ['alt' => 'Installation Edit']); ?></a>
                <a href="/installations/delete/<?php echo $installation->id; ?>" class="delete-icon" onclick="return confirm('Are you sure, you want to delete?')"><?php echo $this->Html->image('bin.png', ['alt' => 'Installation Delete']); ?></a>
                <a class="admin_only" href="<?php echo DS.'webroot'.DS.'uploads'.DS.'installdiagrams'.DS.$installation->file_name; ?>" target="_blank" ><?= h($installation->name) ?></a>
				<?php } else {?>
					
				<a class="dealer_only" href="<?php echo DS.'webroot'.DS.'uploads'.DS.'installdiagrams'.DS.$installation->file_name; ?>" target="_blank" ><?= h($installation->name) ?></a>
				<?php } ?>
				</div>
				
				<div class="table-cell install-table-cell ">
				<!-- <p class="msg"><?php if($installation->is_new == 'yes'){ ?><span>New</span> This file has been updated! <?php } ?></p> -->
				<?= h($installation->comment) ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

</div>
