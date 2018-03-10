
<div class="row block tech-block">
	<ul class="breadcrumb">
		<li><?= $this->Html->link(__('Product Diagrams'), ['action' => 'index']) ?></li>	
		<li><a href="#" class="current"><?= h($productDiagram->name) ?></a></li>
	</ul>
	<div class="col-xs-12 ul-list">
		<ul class="columns">
			<?php foreach ($productDiagram->child_product_diagrams as $childProductDiagrams): 
			if($childProductDiagrams->archived == 'no'){
			?>
			<li class="column">
				<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
                <a href="/product-diagrams/edit/<?php echo $childProductDiagrams->id; ?>" class="edit-icon"><?php echo $this->Html->image('edit.png', ['alt' => 'A&D Weighing Edit']); ?></a>
                <a href="/product-diagrams/delete/<?php echo $childProductDiagrams->id; ?>" class="delete-icon" onclick="return confirm('Are you sure, you want to delete?')"><?php echo $this->Html->image('bin.png', ['alt' => 'A&D Weighing Delete']); ?></a>
				<?php } ?>
                <a href="<?php echo DS.'webroot'.DS.'uploads'.DS.'productdiagrams'.DS.$childProductDiagrams->file_name; ?>" target="_blank"><?= h($childProductDiagrams->name) ?></a></li>
			<?php } endforeach; ?>
		</ul>
	</div> 

</div>
