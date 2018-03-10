<div class="row block tech-block">
	<ul class="breadcrumb">
		<li><?= $this->Html->link(__('Maintenance Manual'), ['action' => 'index']) ?></li>
		<li><a href="#" class="current"><?= h($maintain->name) ?></a></li>
	</ul>
	<div class="col-xs-12 ul-list">
		<ul class="columns">
			<?php foreach ($maintain->child_maintains as $childMaintains): 
			if($childMaintains->archived == 'no'){
			?>
			<li class="column">
				<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
                <a href="/maintains/edit/<?php echo $childMaintains->id; ?>" class="edit-icon"><?php echo $this->Html->image('edit.png', ['alt' => 'A&D Weighing Edit']); ?></a>
                <a href="/maintains/delete/<?php echo $childMaintains->id; ?>" class="delete-icon" onclick="return confirm('Are you sure, you want to delete?')"><?php echo $this->Html->image('bin.png', ['alt' => 'A&D Weighing Delete']); ?></a>
                <?php } ?>
				<a href="<?php echo DS.'webroot'.DS.'uploads'.DS.'maintenancemanuals'.DS.$childMaintains->file_name; ?>" target="_blank"><?= h($childMaintains->name) ?></a></li>
			<?php } endforeach; ?>
		</ul>
	</div>
</div>
