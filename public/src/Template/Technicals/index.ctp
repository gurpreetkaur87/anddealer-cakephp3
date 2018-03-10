<div class="row block tech-block tech-main-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Technicals Documents') ?></li>
        </ul>
		<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
        <div class="add-button"><?= $this->Html->link(__('Add New Technical Document'), ['action' => 'add']) ?></div>
		<?php } ?>
    </div>


    <div class="col-xs-12 block-list technical-list category-menu">
        <?php foreach ($technicals as $technical): 
            /*if($technical->parent_id == 0) {*/
            ?>
        <div class="col-xs-12 col-md-6 col-lg-4 list-col">
            <div class="wrapper">
                <a class="img numbers" href="/technicals/view/<?= $this->Number->format($technical->id) ?>" ></a>

                <a class="word" href="/technicals/view/<?= $this->Number->format($technical->id) ?>"><?= h($technical->name) ?></a>
             </div>
        </div>
        <?php /*}*/ endforeach; ?>
    </div>
</div>

