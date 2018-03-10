
<div class="row block instruction-block instruction-main-page">
    <div class="col-xs-12" style="position: relative;">
        <ul class="breadcrumb">
            <li><?= __('Instruction Manuals') ?></li>
        </ul>
		<?php if($_SESSION['Auth']['User'][0]['type'] == 'admin'){ ?>
        <div class="add-button"><?= $this->Html->link(__('Add New Instruction'), ['action' => 'add']) ?></div>
		<?php } ?>
    </div>


    <div class="col-xs-12 block-list instruction-list category-menu">
        <?php foreach ($instructions as $instruction): 
            if($instruction->parent_id == 0) {
            ?>
        <div class="col-xs-12 col-md-6 col-lg-4 list-col">
            <div class="wrapper">
               <a  class="img numbers" href="/instructions/view/<?php echo $instruction->id; ?>"></a>

             
                <a  class="word" href="/instructions/view/<?php echo $instruction->id; ?>"><?= h($instruction->name) ?></a>
             </div>
        </div>
        <?php } endforeach; ?>
    </div>
</div>