<div class="row block tech-block">
	<ul class="breadcrumb">
		<li><?= $this->Html->link(__('Technical Document'), ['action' => 'index']) ?></li>

		<li><a href="#" class="current"><?= h($technical->name) ?></a></li>
	</ul>
	<div class="col-xs-12 ul-list">
		<ul class="columns">
			<?php foreach ($technical->child_technicals as $childTechnicals): 
			if($childTechnicals->archived == 'no'){
			?>
			<li class="column">
                <!--a href="/technicals/edit/<?php echo $this->request->pass[0]; ?>" class="edit-icon"><?php echo $this->Html->image('edit.png', ['alt' => 'A&D Weighing Edit']); ?></a-->
				<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
                <a href="/technicals/edit/<?php echo $childTechnicals->id; ?>" class="edit-icon"><?php echo $this->Html->image('edit.png', ['alt' => 'A&D Weighing Edit']); ?></a>
                <a href="/technicals/delete/<?php echo $childTechnicals->id; ?>" class="delete-icon" onclick="return confirm('Are you sure, you want to delete?')"><?php echo $this->Html->image('bin.png', ['alt' => 'A&D Weighing Delete']); ?></a>
				<?php } ?>
                <a href="<?php echo DS.'webroot'.DS.'uploads'.DS.'techdocs'.DS.$technical->folder_name.DS.$childTechnicals->file_name; ?>" target="_blank"><?= h($childTechnicals->name) ?></a>
            </li>
			<?php } endforeach; ?>
		</ul>
	</div>


</div>
