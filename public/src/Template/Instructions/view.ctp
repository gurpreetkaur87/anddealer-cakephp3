<div class="row block tech-block">
	<ul class="breadcrumb">
		<li><?= $this->Html->link(__('Instruction Manuals'), ['action' => 'index']) ?></li>
		
		<li><a href="#" class="current"><?= h($instruction->name) ?></a></li>
	</ul>
	<div class="col-xs-12 ul-list">
		<ul class="columns">
			<?php foreach ($instruction->child_instructions as $childInstructions): 
			if($childInstructions->archived == 'no'){
			?>
			<li class="column">
				<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
                <a href="/instructions/edit/<?php echo $childInstructions->id; ?>" class="edit-icon"><?php echo $this->Html->image('edit.png', ['alt' => 'A&D Weighing Edit']); ?></a>
                <a href="/instructions/delete/<?php echo $childInstructions->id; ?>" class="delete-icon" onclick="return confirm('Are you sure, you want to delete?')"><?php echo $this->Html->image('bin.png', ['alt' => 'A&D Weighing Delete']); ?></a>
				<?php } ?>
                <a href="<?php echo DS.'webroot'.DS.'uploads'.DS.'instructionmanuals'.DS.$childInstructions->file_name; ?>" target="_blank"><?= h($childInstructions->name) ?></a>
            </li>
			<?php } endforeach; ?>
		</ul>
	</div>
</div>
