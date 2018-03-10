<div class="row block maintain-block maintain-main-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Maintenance Manuals') ?></li>
        </ul>
		<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
        <div class="add-button"><?= $this->Html->link(__('Add New Maintain'), ['action' => 'add']) ?></div>
		<?php } ?>
    </div>


    <div class="col-xs-12 block-list maintains-list category-menu">
        <?php foreach ($maintains as $maintain): 
            if($maintain->parent_id == 0) {
            ?>
        <div class="col-xs-12 col-md-6 col-lg-4 list-col">
            <div class="wrapper">
                <a class="img numbers" href="/maintains/view/<?= $this->Number->format($maintain->id) ?>"></a>            
                <a class="word" href="/maintains/view/<?= $this->Number->format($maintain->id) ?>"><?= h($maintain->name) ?></a>
             </div>
        </div>
        <?php } endforeach; ?>
    </div>
</div>