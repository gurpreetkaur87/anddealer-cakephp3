

<div class="row block pd-block pd-main-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Product Diagrams') ?></li>
        </ul>
		<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
        <div class="add-button"><?= $this->Html->link(__('New Product Diagram'), ['action' => 'add']) ?></div>
		<?php } ?>
    </div>


    <div class="col-xs-12 block-list pd-list category-menu">
        <?php foreach ($productDiagrams as $productDiagram): 
            if($productDiagram->parent_id == 0) {
            ?>
         <div class="col-xs-12 col-md-6 col-lg-4 list-col">
            <div class="wrapper">
               <a class="img numbers" href="/product-diagrams/view/<?= $this->Number->format($productDiagram->id) ?>"></a>

                <a class="word" href="/product-diagrams/view/<?= $this->Number->format($productDiagram->id) ?>"><?= h($productDiagram->name) ?></a>
             </div>
        </div>
        <?php } endforeach; ?>
    </div>
</div> 