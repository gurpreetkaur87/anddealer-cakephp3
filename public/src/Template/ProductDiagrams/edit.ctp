<!-- <nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $productDiagram->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $productDiagram->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Product Diagrams'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Parent Product Diagrams'), ['controller' => 'ProductDiagrams', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Parent Product Diagram'), ['controller' => 'ProductDiagrams', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="productDiagrams form large-9 medium-8 columns content">
    <?= $this->Form->create($productDiagram, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit Product Diagram') ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentProductDiagrams]);
            echo $this->Form->input('name');
			echo 'current PDF File: '.$productDiagram->file_name;
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div> -->
<div class="row block maintain-block add-page-block maintain-add-page">
    <div class="col-xs-12" style="position: relative;">
    <ul class="breadcrumb">
        <li><?= $this->Html->link(__('Product Diagrams'), ['action' => 'index']) ?></li>
        <li><a href="#" class="current"><?= h($productDiagram->name) ?></a></li>
    </ul>
    <div class="delete-button">
        <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $productDiagram->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $productDiagram->id)]
            )
        ?>
    </div>
    </div>
    <div class="col-xs-12 add-form">
        <?= $this->Form->create($productDiagram, ['type' => 'file']) ?>
        <?php
            echo $this->Form->input('parent_id', ['options' => $parentProductDiagrams]);
            echo $this->Form->input('name');
            echo '<div class="input"><label>Current PDF:</label>'.$productDiagram->file_name.'</div>';
            echo $this->Form->input('file_name', ['type' => 'file','required'=>'']);
        ?>

        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>

</div>